<?php
include '../config/config.php';
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=subscribers_'.date('Y-m-d H:i:s').'.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('SL', 'Email'));  						
$statement = $pdo->prepare("SELECT * FROM subscribers WHERE status=? ORDER BY id ASC");
$statement->execute([1]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$i=0;
foreach ($result as $row) {
    $i++;
	fputcsv($output, array($i,$row['email']));
} 
fclose($output);