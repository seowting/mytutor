<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");
$results_per_page = 5;
$pageno = (int)$_POST['pageno'];
//$search = $_POST['search'];
$page_first_result = ($pageno - 1) * $results_per_page;

$sqlloadtutors = "SELECT `tbl_tutors`.`tutor_id`, `tbl_tutors`.`tutor_email`, `tbl_tutors`.`tutor_phone`, 
`tbl_tutors`.`tutor_name`, `tbl_tutors`.`tutor_password`, `tbl_tutors`.`tutor_description`, `tbl_tutors`.`tutor_datereg`, 
GROUP_CONCAT(DISTINCT `tbl_subjects`.`subject_name` SEPARATOR ',\n') 
AS `subject_list` FROM `tbl_tutors` INNER JOIN `tbl_subjects` ON `tbl_tutors`.`tutor_id` = `tbl_subjects`.`tutor_id` 
GROUP BY `tbl_tutors`.`tutor_id`";

$result = $conn->query($sqlloadtutors);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadtutors = $sqlloadtutors . " LIMIT $page_first_result , $results_per_page";
$result = $conn->query($sqlloadtutors);

if ($result->num_rows > 0){
    $tutors["tutors"] = array();
    while($row = $result->fetch_assoc()){
        $tutorslist = array();        
        $tutorslist['tutor_id'] = $row['tutor_id'];
        $tutorslist['tutor_email'] = $row['tutor_email'];
        $tutorslist['tutor_phone'] = $row['tutor_phone'];
        $tutorslist['tutor_name'] = $row['tutor_name'];
        $tutorslist['tutor_password'] = $row['tutor_password'];
        $tutorslist['tutor_description'] = $row['tutor_description'];
        $tutorslist['tutor_datereg'] = $row['tutor_datereg'];
        $tutorslist['subject_list'] = $row['subject_list'];
        array_push($tutors["tutors"], $tutorslist);
    }
    $response = array('status' => 'success', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page", 'data' => $tutors);
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