<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$results_per_page = 5;
$pageno = (int)$_POST['pageno'];
$search = $_POST['search'];
$page_first_result = ($pageno - 1) * $results_per_page;

$sqlloadsubjects = "SELECT * FROM tbl_subjects WHERE subject_name LIKE '%$search%'";
$result = $conn->query($sqlloadsubjects);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadsubjects = $sqlloadsubjects . " LIMIT $page_first_result , $results_per_page";
$result = $conn->query($sqlloadsubjects);
if ($result->num_rows > 0){
    $subjects["subjects"] = array();
    while($row = $result->fetch_assoc()){
        $subjectslist = array();
        $subjectslist['subject_id'] = $row['subject_id'];
        $subjectslist['subject_name'] = $row['subject_name'];
        $subjectslist['subject_description'] = $row['subject_description'];
        $subjectslist['subject_price'] = $row['subject_price'];
        $subjectslist['tutor_id'] = $row['tutor_id'];
        $subjectslist['subject_sessions'] = $row['subject_sessions'];
        $subjectslist['subject_rating'] = $row['subject_rating'];
        array_push($subjects["subjects"], $subjectslist);
    }
    $response = array('status' => 'success', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page", 'data' => $subjects);
    sendJsonResponse($response);
} else{
    $response = array('status' => 'failed', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page",'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>
