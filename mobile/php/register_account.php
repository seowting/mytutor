<?php
if(!isset($_POST)){
    $response = array('status' =>'failed', 'data'=>null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");

$username = addslashes($_POST['name']);
$useremail = $_POST['email'];
$userpassword = sha1($_POST['password']);
$userphoneno = $_POST['phoneno'];
$userhomeaddress = addslashes($_POST['homeaddress']);
$base64Image = $_POST['image'];


$sqlinsert = "INSERT INTO `tbl_users` (`user_name`, `user_email`, `user_password`, `user_phoneno`, `user_homeaddress`) 
VALUES ('$username', '$useremail', '$userpassword', '$userphoneno', '$userhomeaddress')";

if($conn->query($sqlinsert) === TRUE){
    $response = array('status' => 'success', 'data' => null);
    $filename = mysqli_insert_id($conn);
    $decoded_string = base64_decode($base64Image);
    $path = '../assets/useraccounts/' . $filename . '.jpg';
    $is_written = file_put_contents($path, $decoded_string);
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