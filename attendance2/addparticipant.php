<?php include "config.php"; ?>
<head>
    <link rel=stylesheet ref="materialize/css/materialize.css">
</head>

<?php


//if(isset($_GET['eventId']) and isset($_GET['']))
$eventId = $_GET['eventId'];
$barcode = $_GET['barcode'];

$firstDigit = substr($barcode, 0,1);
$lastDigit = substr($barcode, -1,1);

if($firstDigit === '*' && $lastDigit === '*')
{
    $barcode = substr($_GET['barcode'], -8,6);
}   
else
{
    $barcode = substr($_GET['barcode'], -7,6);         
}
$q1 = mysqli_query($conn, "SELECT * FROM student where barcode = '".$barcode."'");
if(mysqli_num_rows($q1) <= 0)
{
    alert("Student Existence is Questioned!");
}
else
{
    $q = mysqli_query($conn, "SELECT * FROM attendance WHERE id = '".$barcode."' and eventid = ".$eventId."");

        mysqli_query($conn, "INSERT INTO attendance(eventid, id, etime) values(".$eventId.",'".$barcode."',now())");
}
?>