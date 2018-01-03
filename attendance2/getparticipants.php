<?php include "config.php"; ?>
<head>
    <link rel=stylesheet ref="materialize/css/materialize.css">
</head>

<?php

$eventId = $_GET['eventId'];

?>
<table class="highlight">
    <thead>
        <tr>
            <th>Name</th> 
            <th>Course</th>
            <th>Year</th>
            <th>Time In</th>
        </tr>
    </thead>
    
    <tbody>
    <?php

    $studentList = mysqli_query($conn, "SELECT CONCAT( s.fname,  ' ', s.lname ) as  fullname, s.course, s.year, p.etime
    FROM attendance p, student s
    WHERE p.id = s.barcode and eventid = ".$eventId." order by p.etime desc limit 5");
        /*$studentList = mysqli_query($conn, "SELECT * FROM STUDENT where barcode = ");*/
        while($row = mysqli_fetch_array($studentList))
        {                    
            echo "<tr><td>".$row[0]."</td>";
            echo "<td>".$row[1]."</td>";
            echo "<td>".$row[2]."</td>";
            echo "<td>".$row[3]."</td></tr>";
        }
    ?>
    </tbody>
</table>