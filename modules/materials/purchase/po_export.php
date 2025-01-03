<?php
require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";

header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Orderlist.csv');
echo "\xEF\xBB\xBF";

$status = $_GET['status'];
$project_short = $_GET['project_short'];

$collectionMaster = $db->purchase_master;

// Define project names mapping
$projectMapping = [
    'MK' => 'S.I.S Marakesh',
    'QT' => 'S.I.S Queenstown',
    'H' => 'Head Office',
    'SN' => 'S.I.S Sintra',
    'AP' => 'S.I.S Acropole',
    'SF' => 'S.I.S Safaa',
    'D' => 'S.I.S Danube',
    'CT' => 'S.I.S Capetown',
    'FL' => 'S.I.S Florence',
    'CM' => 'Common',
    'SIS' => 'S.I.S',
    'ME' => 'S.I.S Meridian',
    'IF' => 'S.I.S Isfahan',
    'LX' => 'S.I.S Luxor'
];

// Calculate the date range as strings
$startDate = date('Y-m-d H:i:s', strtotime('-1 years'));
$endDate = date('Y-m-d H:i:s');

// Build the match condition
$match = [
    'cancel_status' => $status === 'usage' ? '0' : '1',
    'date_time' => [
        '$gte' => $startDate,
        '$lte' => $endDate
    ]
];

if ($project_short != '') {
    $match['project_short'] = $project_short;
}

$result = $collectionMaster->aggregate([
    ['$match' => $match],
    ['$sort' => ['_id' => -1]]
]);

// Output CSV headers
echo 'PO_no,PO_Date,Project,Company,Grandtotal,Category' . "\n";

$totalGrandTotal = 0;

// Process and output rows
foreach ($result as $row) {
    $project = $projectMapping[$row['project_short']] ?? $row['project_short'];
    $grandTotal = $row['grand_total'];
    $totalGrandTotal += $grandTotal;

    echo implode(',', [
        $row['po_number'],
        $row['po_date'],
        $project,
        $row['company'],
        $grandTotal,
        $row['category'],
    ]) . "\n";
}

// Output total sum in the last row
echo ",,,Total," . $totalGrandTotal . ",";
?>
