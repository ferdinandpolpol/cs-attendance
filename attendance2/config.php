<?php
    $servername = "localhost";
    $username ="itweek";
    $password="cssec123";
    $db="itweek_attendance";
    $conn = new mysqli($servername, $username , $password);
    $db_selected = mysqli_select_db($conn, $db);

    function alert($string){
        echo"<script>alert('$string')</script>";
    }
    function show_error($conn)
    {
        echo mysqli_error($conn);
        exit();
    }
	session_save_path("/home/itweek");
	session_start();
?>