<?php

if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

if (!isset($_SESSION['user_name'])){
	
		header("location: ../index.php");
    }    

require '../config/dbh.inc.php';

$data = array();

$query = "SELECT * FROM events ORDER BY id";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

foreach($result as $row)
{
 $data[] = array(
    'id' => $row["id"],
    'title' => $row["title"],
    'start' => $row["start_event"],
    'end' => $row["end_event"],
    'overlap' => $row["overlap"],
    'rendering' => $row["rendering"],
    'textColor' => $row["color"],
    'description' => $row["name_of_user"],
    'color' => $row["color_background"],
 );
}

echo json_encode($data);

?>
