<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$useremail = $_POST['useremail'];
$sqlloadcart = "SELECT tbl_carts.cart_id, tbl_carts.subject_id, tbl_carts.cart_qty, tbl_subjects.subject_name, tbl_subjects.subject_price FROM tbl_carts INNER JOIN tbl_subjects ON tbl_carts.subject_id = tbl_subjects.subject_id WHERE tbl_carts.user_email = '$useremail' AND tbl_carts.cart_status IS NULL";
$result = $conn->query($sqlloadcart);
$number_of_result = $result->num_rows;
if ($result->num_rows > 0) {
    $total_payment = 0;
    $carts["cart"] = array();
    while ($rows = $result->fetch_assoc()) {
        $subjectslist = array();
        $subjectslist['cart_id'] = $rows['cart_id'];
        $subjectslist['subject_name'] = $rows['subject_name'];
        $subjectprice = $rows['subject_price'];
        $subjectslist['cart_qty'] = $rows['cart_qty'];
        $subjectslist['subject_id'] = $rows['subject_id'];
        $total_payment = $total_payment + $subjectprice;
        $subjectslist['totalprice'] = number_format((float)$subjectprice, 2, '.', ''); 
        array_push($carts["cart"],$subjectslist);
    }
    $response = array('status' => 'success', 'data' => $carts, 'total' => $total_payment);
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