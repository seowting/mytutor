<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$email = $_POST['user_email'];
$receiptid = $_POST['receipt_id'];

$sqlloadcart = "SELECT tbl_carts.cart_id, tbl_carts.subject_id, tbl_carts.cart_qty, tbl_subjects.subject_name, tbl_subjects.subject_description, tbl_subjects.subject_price, tbl_subjects.tutor_id, tbl_subjects.subject_sessions, tbl_subjects.subject_rating FROM tbl_carts INNER JOIN tbl_subjects ON tbl_carts.subject_id = tbl_subjects.subject_id WHERE tbl_carts.user_email = '$email' AND tbl_carts.receipt_id = '$receiptid'";


$result = $conn->query($sqlloadcart);
$number_of_result = $result->num_rows;
if ($result->num_rows > 0) {
    //do something
    $total_payment = 0;
    $carts["cart"] = array();
    while ($rows = $result->fetch_assoc()) {
        
        $subjectslist = array();
        $subjectslist['cart_id'] = $rows['cart_id'];
        $subjectslist['subject_name'] = $rows['subject_name'];
        $subjectprice = $rows['subject_price'];
        $subjectslist['cart_qty'] = $rows['cart_qty'];
        $subjectslist['subject_id'] = $rows['subject_id'];
        $subjectslist['totalprice'] = number_format((float)$subjectprice, 2, '.', ''); 
        array_push($carts["cart"],$subjectslist);
    }
    $response = array('status' => 'success', 'data' => $carts);
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