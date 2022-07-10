<?php
if(!isset($_POST)){
    echo "failed";
}

include_once("dbconnect.php");
$email = $_POST['email'];
$password = sha1($_POST['password']);
$sqllogin = "SELECT * FROM tbl_users WHERE user_email = '$email' AND user_password = '$password'";
$result = $conn->query($sqllogin);
$num_rows = $result->num_rows;

if($num_rows>0){
    while($row = $result->fetch_assoc()){
        $user['id'] = $row['user_id'];
        $user['name'] = $row['user_name'];
        $user['email'] = $row['user_email'];
        $user['password'] = $row['user_password'];
        $user['phoneno'] = $row['user_phoneno'];
        $user['homeaddress'] = $row['user_homeaddress'];
    }
    $sqlgetqty = "SELECT * FROM tbl_carts WHERE user_email = '$email' AND cart_status IS NULL";
    $result = $conn->query($sqlgetqty);
    $number_of_result = $result->num_rows;
    $carttotal = 0;
    while($row = $result->fetch_assoc()) {
        $carttotal = $row['cart_qty'] + $carttotal;
    }
    $mycart = array();
    $user['cart'] =$carttotal;

    $response = array('status' => 'success', 'data' => $user);
    sendJsonResponse($response);
}else{
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray){
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>