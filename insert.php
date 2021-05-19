<?php

 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

if (!isset($_SESSION['user_name'])){
	
		header("location: ../index.php");
    }    

require '../config/dbh.inc.php';

if(isset($_POST["title"]))
{
    $title = test_input($_POST['title']);    
    $start = test_input($_POST['start']);    
    $end = test_input($_POST['end']);    
    $user_id = test_input($_SESSION['id_users']);
    $name_of_user = test_input($_SESSION['user_name']); 

    $color = "";
    if($_POST['color'])
    {
        $color = test_input($_POST['color']); 
    } else {
        $color = null; 
    }

    $overlap ="";
    if($_POST['overlap'])
    {
        $overlap = test_input($_POST['overlap']); 
    } else {
        $overlap = null; 
    }

    $rendering ="";
    if($_POST['rendering'])
    {
        $rendering = test_input($_POST['rendering']); 
    } else {
        $rendering = null; 
    }
    
    $color_background ="";
    if($_POST['color_background'])
    {
        $color_background = test_input($_POST['color_background']); 
    } else {
        $color_background = null; 
    }
    
    $sql = "INSERT INTO events (title, start_event, end_event, user_id, name_of_user, color, overlap, rendering, color_background ) VALUES (?,?,?,?,?,?,?,?,?)";   
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt,$sql) ) {
        header("location: ../calendar.php?error=sqlerror");
        exit();  
    }
    else
    {
        mysqli_stmt_bind_param($stmt, "sssssssss", $title,$start,$end,$user_id, $name_of_user, $color,$overlap,$rendering,$color_background);      
        mysqli_stmt_execute($stmt);
    }
    $_SESSION['status'] = "5";
    mysqli_stmt_close($stmt);   
	mysqli_close($conn);
}

?>
