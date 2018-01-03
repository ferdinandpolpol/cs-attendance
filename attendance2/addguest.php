<?php include "config.php"; ?>
<head>
    <link rel=stylesheet ref="materialize/css/materialize.css">
</head>

<?php 
$eventId = $_GET['eventId'];
$guestname = $_GET['guestname'];
$guestschool = $_GET['guestschool'];
mysqli_query($conn, "INSERT INTO guest_attendance(guest_name, guest_school, eventid, time_in) VALUES('".$guestname."','".$guestschool."','".$eventId."', now())");
?>