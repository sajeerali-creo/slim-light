<?php
require_once __DIR__ . '/../services/commonService.php';

$data = [
    'PersonId' => $_SESSION['user']['UserId'],
    'ReceiptEntryNo' => $_POST['ReceiptEntryNo']
];

$respData = GetReceiptPdf($data);

echo $respData;
exit;
?>