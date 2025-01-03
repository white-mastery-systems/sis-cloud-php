<?php
require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Orderlist.csv');
echo "\xEF\xBB\xBF"; // Add BOM for UTF-8

$status = $_GET['status'] ?? '';
$project_short = $_GET['project_short'] ?? '';
$category = $_GET['category'] ?? '';

$collectionMaster = $db->purchase_master;

        $match = ['cancel_status' => $status === 'usage' ? '0' : '1'];

		if (!empty($project_short)) {
		$match['project_short'] = $project_short;
		}

		// Add the category condition only if it's provided
		if (!empty($category)) {
		$match['category'] = $category;
		}

        $result = $collectionMaster->aggregate([
            ['$match' => $match],
            ['$sort' => ['_id' => -1]]
        ]);

// Mapping project_short to project names
$projectMap = [
    'MK' => 'S.I.S Marakesh',
    'QT' => 'S.I.S Queenstown',
    'H'  => 'Head Office',
    'SN' => 'S.I.S Sintra',
    'AP' => 'S.I.S Acropole',
    'SF' => 'S.I.S Safaa',
    'D'  => 'S.I.S Danube',
    'CT' => 'S.I.S Capetown',
    'FL' => 'S.I.S Florence',
    'CM' => 'Common',
    'SIS'=> 'S.I.S',
    'ME' => 'S.I.S Meridian',
    'IF' => 'S.I.S Isfahan',
    'LX' => 'S.I.S Luxor'
];

// Output CSV header
echo 'PO_no,PO_Date,Project,Company,Grandtotal,Category' . "\n";

$totalGrandTotal = 0;

// Process and output rows
foreach ($result as $row) {
    $project = $projectMap[$row['project_short']] ?? $row['project_short'];
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
