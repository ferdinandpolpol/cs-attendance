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
                <th style='width:20%;text-  align:left;'>Name</th> 
                <th style='width:20%;text-align:left'>School</th>
                <th style='width:20%;text-align:left'>Time In</th>
            </tr>
        </thead>
        <?php

        $guestList = mysqli_query($conn, "SELECT s.guest_name, s.guest_school, s.time_in
        FROM guest_attendance s
        WHERE eventid = ".$eventId." order by s.time_in desc limit 3");
        while($row = mysqli_fetch_array($guestList))
        {                    
            echo "<tr><td>".$row[0]."</td>";
            echo "<td>".$row[1]."</td>";
            echo "<td>".$row[2]."</td></tr>";
        }
        ?>
</table>