<?php
session_start();
if (!isset($_SESSION['authed'])) {
  http_response_code(403);
  echo 'Forbidden';
  exit;
}

$db = new PDO('sqlite:' . __DIR__ . '/private_data/feedback.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$rows = $db->query("SELECT created_at, salesperson, customer_name, customer_email, customer_phone, rating, comments
                    FROM feedback ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="feedback_export.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Timestamp','Salesperson','Customer Name','Customer Email','Customer Phone','Rating','Comments']);
foreach ($rows as $row) {
    fputcsv($out, [
        $row['created_at'],
        $row['salesperson'],
        $row['customer_name'],
        $row['customer_email'],
        $row['customer_phone'],
        $row['rating'],
        $row['comments'],
    ]);
}
fclose($out);
