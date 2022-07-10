<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$email = $_POST['user_email'];

$sqlloadsubscribe = "SELECT * FROM tbl_orders WHERE user_email = '$email' ORDER BY order_date DESC";

$result = $conn->query($sqlloadsubscribe);
$number_of_result = $result->num_rows;
if ($result->num_rows > 0) {
    $orders["orders"] = array();
    while ($rows = $result->fetch_assoc()) {
        $subslist = array();
        $subslist['order_id'] = $rows['order_id'];
        $subslist['receipt_id'] = $rows['receipt_id'];
        $subslist['order_status'] = $rows['order_status'];
        $subslist['order_date'] = $rows['order_date'];
        $subslist['order_paid'] = number_format((float)$rows['order_paid'], 2, '.', '');
        array_push($orders["orders"],$subslist);
    }
    $response = array('status' => 'success', 'data' => $orders);
    sendJsonResponse($response);
} else {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}

?>