"""Purchase order reports for a project.

Writes three CSVs per project (active POs only, i.e. cancel_status '0'):
  Orderlist_<CODE>_<tag>.csv               one row per PO
  Orderlist_<CODE>_items_<tag>.csv         one row per PO item, plus transport / plain charge rows
  <CODE>_monthwise_quantities_<tag>.csv    item quantities per month (by PO date)

Amounts follow modules/materials/purchase/po_pdf.php, so each PO's rows add up to its grand_total.
The database connection is read from include/conn.php. Read-only.

Usage:
  pip install pymongo
  python scripts/po_reports.py SN                 # full history -> exports/Sintra/*_SN_complete.csv
  python scripts/po_reports.py SN IF FL           # several projects
  python scripts/po_reports.py all                # every project that has POs
  python scripts/po_reports.py FL --from 2025-11-01 --to 2026-10-31
  python scripts/po_reports.py --list             # show project codes and PO counts
"""
import argparse
import csv
import os
import re
import sys
from collections import defaultdict
from datetime import datetime

import pymongo

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))


def connect():
    with open(os.path.join(ROOT, 'include', 'conn.php')) as f:
        uri = re.search(r'"(mongodb://[^"]+)"', f.read()).group(1)
    return pymongo.MongoClient(uri, serverSelectionTimeoutMS=15000)['sis_crm_DB']


def num(v):
    try:
        return float(v or 0)
    except (TypeError, ValueError):
        return 0.0


def txt(v):
    return '' if v is None else ' '.join(str(v).split())


def po_month(p):
    try:
        return datetime.strptime(p['po_date'], '%d-%m-%Y').strftime('%Y-%m')
    except (KeyError, TypeError, ValueError):
        return str(p.get('date_time', ''))[:7]


def month_range(months):
    ms = sorted(months)
    y, m = map(int, ms[0].split('-'))
    out = []
    while f'{y:04d}-{m:02d}' <= ms[-1]:
        out.append(f'{y:04d}-{m:02d}')
        y, m = (y + 1, 1) if m == 12 else (y, m + 1)
    return out


def month_label(m):
    return datetime.strptime(m, '%Y-%m').strftime('%b-%Y')


def fmt_qty(x):
    return '' if x == 0 else (int(x) if x == int(x) else round(x, 3))


def project_name(db, code):
    # project_details has one row per block; prefer the plain name (e.g. "Head Office" over "Head Office - IT")
    names = {d['project_name'] for d in db.project_details.find({'project_short': code}, {'project_name': 1}) if d.get('project_name')}
    return min(names, key=len) if names else code


def report(db, code, out_dir, date_from=None, date_to=None):
    match = {'cancel_status': '0', 'project_short': code}
    if date_from or date_to:
        match['date_time'] = {}
        if date_from:
            match['date_time']['$gte'] = date_from + ' 00:00:00'
        if date_to:
            match['date_time']['$lte'] = date_to + ' 23:59:59'
    pos = list(db.purchase_master.aggregate([
        {'$match': match},
        {'$sort': {'_id': -1}},
        {'$lookup': {'from': 'purchase_table', 'localField': 'po_number', 'foreignField': 'po_number', 'as': 'items'}},
    ]))
    if not pos:
        print(f'{code}: no active POs found, nothing written')
        return

    pname = project_name(db, code)
    if out_dir is None:
        # exports/<project> folder, e.g. "S.I.S Sintra" -> exports/Sintra
        out_dir = os.path.join(ROOT, 'exports', re.sub(r'^S\.?I\.?S\.?\s+', '', pname, flags=re.I).strip() or code)
    os.makedirs(out_dir, exist_ok=True)
    tag = 'complete' if not (date_from or date_to) else f"{date_from or 'start'}_to_{date_to or 'today'}"
    paths = {
        'summary': os.path.join(out_dir, f'Orderlist_{code}_{tag}.csv'),
        'items': os.path.join(out_dir, f'Orderlist_{code}_items_{tag}.csv'),
        'monthwise': os.path.join(out_dir, f'{code}_monthwise_quantities_{tag}.csv'),
    }
    grand_total = sum(num(p.get('grand_total')) for p in pos)

    # 1. PO summary
    with open(paths['summary'], 'w', newline='', encoding='utf-8-sig') as f:
        w = csv.writer(f)
        w.writerow(['PO_no', 'PO_Date', 'Project', 'Company', 'Grandtotal', 'Category'])
        for p in pos:
            w.writerow([p.get('po_number'), p.get('po_date'), pname, txt(p.get('company')), p.get('grand_total'), txt(p.get('category'))])
        w.writerow(['', '', '', 'Total', round(grand_total, 2), ''])

    # 2. Line items, collecting month-wise quantities on the way
    qty = defaultdict(lambda: defaultdict(float))
    value = defaultdict(float)
    label = {}
    po_count = defaultdict(set)
    months = set()
    n_items = 0
    total_amount = total_rows = 0.0
    mismatched = []
    with open(paths['items'], 'w', newline='', encoding='utf-8-sig') as f:
        w = csv.writer(f)
        w.writerow(['PO_no', 'PO_Date', 'Project', 'Company', 'Category', 'Item_No', 'HSN_Code', 'Item', 'Description', 'Make', 'Size', 'CFT',
                    'Qty', 'Unit', 'Price', 'Amount', 'GST_%', 'SGST_Amt', 'CGST_Amt', 'Item_Total', 'PO_Grandtotal'])
        for p in pos:
            head = [p.get('po_number'), p.get('po_date'), pname, txt(p.get('company')), txt(p.get('category'))]
            month = po_month(p)
            months.add(month)
            door = p.get('type') == 'Door'
            po_sum = 0.0
            first = True
            for i, it in enumerate(sorted(p['items'], key=lambda d: d['_id']), 1):
                q = num(it.get('quantity'))
                amount = (num(it.get('cft')) if door else q) * num(it.get('price'))  # Door POs are priced per cft
                sgst = num(it.get('sgst')) / 100 * amount
                cgst = num(it.get('cgst')) / 100 * amount
                total = amount + sgst + cgst
                po_sum += total
                total_amount += amount
                total_rows += total
                n_items += 1
                w.writerow(head + [i, txt(it.get('code')), txt(it.get('item')), txt(it.get('desc')), txt(it.get('make')), txt(it.get('size')),
                                   txt(it.get('cft')) if door else '', it.get('quantity'), txt(it.get('unit')), it.get('price'), round(amount, 2),
                                   num(it.get('sgst')) + num(it.get('cgst')), round(sgst, 2), round(cgst, 2), round(total, 2),
                                   p.get('grand_total') if first else ''])
                first = False
                key = (txt(p.get('category')).lower(), txt(it.get('item')).lower(), txt(it.get('unit')).lower())
                label.setdefault(key, (txt(p.get('category')), txt(it.get('item')), txt(it.get('unit'))))
                qty[key][month] += q
                value[key] += total
                po_count[key].add(p['po_number'])
            # PO-level charges; plain charge is a rate per cft
            for name, amount_key, tax_key in (('Transport charges', 'trans_amount', 'trans_tax'), ('Plain charges', 'plain_amount', 'plain_tax')):
                amount = num(p.get(amount_key)) * (num(p.get('sum_cft')) if amount_key == 'plain_amount' else 1)
                if not amount:
                    continue
                tax = num(p.get(tax_key)) / 100 * amount
                po_sum += amount + tax
                total_amount += amount
                total_rows += amount + tax
                w.writerow(head + ['', '', name, '', '', '', '', '', '', '', round(amount, 2), num(p.get(tax_key)),
                                   round(tax / 2, 2), round(tax / 2, 2), round(amount + tax, 2), p.get('grand_total') if first else ''])
                first = False
            if first:
                w.writerow(head + [''] * 15 + [p.get('grand_total')])
            if abs(po_sum - num(p.get('grand_total'))) > 1:
                mismatched.append(p['po_number'])
        w.writerow([''] * 14 + ['Total', round(total_amount, 2), '', '', '', round(total_rows, 2), round(grand_total, 2)])

    # 3. Month-wise quantities, every month in the range gets a column
    months = month_range(months)
    with open(paths['monthwise'], 'w', newline='', encoding='utf-8-sig') as f:
        w = csv.writer(f)
        w.writerow(['Category', 'Item', 'Unit'] + [month_label(m) for m in months] + ['Total_Qty', 'No_of_POs', 'Total_Value_incl_GST'])
        for key in sorted(qty, key=lambda k: (label[k][0].lower(), label[k][1].lower())):
            row = [qty[key].get(m, 0) for m in months]
            w.writerow(list(label[key]) + [fmt_qty(x) for x in row] + [fmt_qty(sum(row)), len(po_count[key]), round(value[key], 2)])

    print(f'{code} ({pname}): {len(pos)} POs, {n_items} items, {month_label(months[0])} to {month_label(months[-1])}, '
          f'grand total {grand_total:,.2f}')
    if mismatched:
        print(f'  WARNING: {len(mismatched)} POs where item rows do not add up to the grand total: {", ".join(mismatched[:20])}')
    for path in paths.values():
        print('  ' + os.path.abspath(path))


def main():
    ap = argparse.ArgumentParser(description='Export purchase order reports (summary, items, month-wise quantities) per project.')
    ap.add_argument('projects', nargs='*', help='project codes such as SN IF FL, or "all"')
    ap.add_argument('--from', dest='date_from', help='only POs created on or after this date (YYYY-MM-DD)')
    ap.add_argument('--to', dest='date_to', help='only POs created on or before this date (YYYY-MM-DD)')
    ap.add_argument('--out', help='output folder (default: exports/<project name>/, e.g. exports/Sintra)')
    ap.add_argument('--list', action='store_true', help='list project codes with active PO counts')
    args = ap.parse_args()

    for d in (args.date_from, args.date_to):
        if d:
            try:
                datetime.strptime(d, '%Y-%m-%d')
            except ValueError:
                ap.error(f'invalid date {d!r}, expected YYYY-MM-DD')

    db = connect()
    counts = {r['_id']: r['n'] for r in db.purchase_master.aggregate([
        {'$match': {'cancel_status': '0'}}, {'$group': {'_id': '$project_short', 'n': {'$sum': 1}}}]) if r['_id']}
    if args.list or not args.projects:
        for code in sorted(counts):
            print(f'{code:5} {counts[code]:6}  {project_name(db, code)}')
        if not args.projects:
            print('\nRun with one or more project codes, e.g.: python scripts/po_reports.py SN')
        return

    codes = sorted(counts) if [c.lower() for c in args.projects] == ['all'] else [c.upper() for c in args.projects]
    for code in codes:
        if code not in counts:
            print(f'{code}: unknown project code (use --list to see codes)', file=sys.stderr)
            continue
        report(db, code, args.out, args.date_from, args.date_to)


if __name__ == '__main__':
    main()
