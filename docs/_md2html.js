// Self-contained Markdown -> HTML / Word(.doc) converter for PROJECT_DOCUMENTATION.md
// No external deps. Handles: headings, fenced code, GFM tables, lists, blockquotes,
// hr, paragraphs, inline bold/code/links. Strips HTML comments.
const fs = require('fs');
const path = require('path');

const SRC = path.join(__dirname, 'PROJECT_DOCUMENTATION.md');
const md = fs.readFileSync(SRC, 'utf8');

const esc = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

function inline(text) {
  // escape first, then apply inline markup
  let t = esc(text);
  // inline code
  t = t.replace(/`([^`]+)`/g, (m, c) => `<code>${c}</code>`);
  // bold
  t = t.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
  // links [text](url)
  t = t.replace(/\[([^\]]+)\]\(([^)]+)\)/g, (m, txt, url) => `<a href="${url}">${txt}</a>`);
  return t;
}

const lines = md.split(/\r?\n/);
let html = '';
let i = 0;

function flushParagraph(buf) {
  if (buf.length) { html += `<p>${inline(buf.join(' '))}</p>\n`; }
  return [];
}

let para = [];

while (i < lines.length) {
  let line = lines[i];

  // strip HTML comment lines
  if (/^\s*<!--/.test(line)) { para = flushParagraph(para); while (i < lines.length && !/-->/.test(lines[i])) i++; i++; continue; }

  // fenced code block
  if (/^```/.test(line)) {
    para = flushParagraph(para);
    i++;
    let code = [];
    while (i < lines.length && !/^```/.test(lines[i])) { code.push(lines[i]); i++; }
    i++; // closing fence
    html += `<pre><code>${esc(code.join('\n'))}</code></pre>\n`;
    continue;
  }

  // horizontal rule
  if (/^---\s*$/.test(line)) { para = flushParagraph(para); html += '<hr>\n'; i++; continue; }

  // heading
  let h = line.match(/^(#{1,6})\s+(.*)$/);
  if (h) { para = flushParagraph(para); const lvl = h[1].length; html += `<h${lvl}>${inline(h[2])}</h${lvl}>\n`; i++; continue; }

  // blockquote
  if (/^>\s?/.test(line)) {
    para = flushParagraph(para);
    let quote = [];
    while (i < lines.length && /^>\s?/.test(lines[i])) { quote.push(lines[i].replace(/^>\s?/, '')); i++; }
    html += `<blockquote>${inline(quote.join(' '))}</blockquote>\n`;
    continue;
  }

  // table: header row + separator
  if (/^\s*\|.*\|\s*$/.test(line) && i + 1 < lines.length && /^\s*\|?[\s:|-]+\|?\s*$/.test(lines[i + 1]) && lines[i+1].includes('-')) {
    para = flushParagraph(para);
    const splitRow = r => r.trim().replace(/^\|/, '').replace(/\|$/, '').split('|').map(c => c.trim());
    const header = splitRow(line);
    i += 2; // skip header + separator
    let rows = [];
    while (i < lines.length && /^\s*\|.*\|\s*$/.test(lines[i])) { rows.push(splitRow(lines[i])); i++; }
    html += '<table>\n<thead><tr>' + header.map(c => `<th>${inline(c)}</th>`).join('') + '</tr></thead>\n<tbody>\n';
    for (const r of rows) { html += '<tr>' + r.map(c => `<td>${inline(c)}</td>`).join('') + '</tr>\n'; }
    html += '</tbody>\n</table>\n';
    continue;
  }

  // unordered list
  if (/^\s*[-*]\s+/.test(line)) {
    para = flushParagraph(para);
    let items = [];
    while (i < lines.length && /^\s*[-*]\s+/.test(lines[i])) { items.push(lines[i].replace(/^\s*[-*]\s+/, '')); i++; }
    html += '<ul>\n' + items.map(it => `<li>${inline(it)}</li>`).join('\n') + '\n</ul>\n';
    continue;
  }

  // ordered list
  if (/^\s*\d+\.\s+/.test(line)) {
    para = flushParagraph(para);
    let items = [];
    while (i < lines.length && /^\s*\d+\.\s+/.test(lines[i])) { items.push(lines[i].replace(/^\s*\d+\.\s+/, '')); i++; }
    html += '<ol>\n' + items.map(it => `<li>${inline(it)}</li>`).join('\n') + '\n</ol>\n';
    continue;
  }

  // blank line ends paragraph
  if (/^\s*$/.test(line)) { para = flushParagraph(para); i++; continue; }

  // default: paragraph text
  para.push(line.trim());
  i++;
}
para = flushParagraph(para);

const css = `
body{font-family:'Segoe UI',Arial,sans-serif;line-height:1.55;color:#1f2328;max-width:1080px;margin:32px auto;padding:0 24px;}
h1{font-size:30px;border-bottom:3px solid #00bcd4;padding-bottom:8px;}
h2{font-size:23px;border-bottom:1px solid #d0d7de;padding-bottom:6px;margin-top:34px;}
h3{font-size:18px;margin-top:26px;}
h4{font-size:15px;color:#444;}
code{background:#f0f3f6;padding:1px 5px;border-radius:4px;font-family:Consolas,'Courier New',monospace;font-size:90%;}
pre{background:#0d1117;color:#e6edf3;padding:14px 16px;border-radius:8px;overflow:auto;}
pre code{background:none;color:inherit;padding:0;font-size:13px;}
table{border-collapse:collapse;width:100%;margin:14px 0;font-size:14px;}
th,td{border:1px solid #d0d7de;padding:7px 10px;text-align:left;vertical-align:top;}
th{background:#00bcd4;color:#fff;}
tr:nth-child(even) td{background:#f6f8fa;}
blockquote{border-left:4px solid #00bcd4;background:#f6fdff;margin:14px 0;padding:8px 16px;color:#444;}
a{color:#0969da;text-decoration:none;}a:hover{text-decoration:underline;}
hr{border:none;border-top:1px solid #d0d7de;margin:24px 0;}
`;

// Plain HTML
const htmlDoc = `<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8">
<title>SIS Cloud — Project Documentation</title>
<style>${css}</style></head>
<body>
${html}
</body></html>`;
fs.writeFileSync(path.join(__dirname, 'PROJECT_DOCUMENTATION.html'), htmlDoc, 'utf8');

// Word-compatible .doc (HTML + Word XML namespaces; Word opens this natively)
const docDoc = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head><meta charset="utf-8">
<title>SIS Cloud — Project Documentation</title>
<!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom></w:WordDocument></xml><![endif]-->
<style>${css}
@page{size:A4;margin:2cm;}
table{font-size:10pt;}
body{font-size:11pt;}
</style></head>
<body>
${html}
</body></html>`;
fs.writeFileSync(path.join(__dirname, 'PROJECT_DOCUMENTATION.doc'), docDoc, 'utf8');

console.log('Wrote PROJECT_DOCUMENTATION.html and PROJECT_DOCUMENTATION.doc');
