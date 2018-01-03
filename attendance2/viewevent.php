<?php include "config.php";

// Validates if page is pressed first time

if(isset($_POST['viewEvent']))
    $_SESSION['eventId'] = $_POST['viewEvent'];
?>
<head>
    <link rel="stylesheet" href="materialize/css/materialize.css">
</head>

<script type="text/javascript" src="js/jquery.min.js"> </script>
<!--<script type="text/javascript">
    $(document).ready(function(){
        $("#submit").click(function(){
            var eventId = <?php echo "".$_SESSION['eventId']."" ?>;
            var barcode = $("#barcode").val();
            $.ajax({
                type: "GET",
                url: "addparticipant.php?barcode="+barcode+"&eventId="+eventId,
                dataType: "html",
                success: function(data){
                    $("#participantList").html(data);
                }
            });       
        }); 
    });
	
	$(document).ready(function(){
        $("#submitguest").click(function(){
            var eventId = <?php echo "".$_SESSION['eventId']."" ?>;
            var guestname = $("#guestname").val();
            var guestschool = $("#guestschool").val();
            $.ajax({
                type: "GET",
                url: "addguest.php?guestname="+guestname+"&guestschool="+guestschool+"&eventId="+eventId,
                dataType: "html",
                success: function(data){
                    $("#guestList").html(data);
                }
            });       
        }); 
    });
</script>-->
<?php

if(isset($_POST['submit']))
{
    //if(isset($_GET['eventId']) and isset($_GET['']))
$eventId = $_SESSION['eventId'];
$barcode = $_POST['barcode'];
$firstDigit = substr($barcode, 0,1);
$lastDigit = substr($barcode, -1,1);

if($firstDigit === '*' && $lastDigit === '*')
{
    $barcode = substr($_POST['barcode'], -8,6);
}   
else
{
    $barcode = substr($_POST['barcode'], -7,6);         
}
$q1 = mysqli_query($conn, "SELECT * FROM student where barcode = '".$barcode."'");
if(mysqli_num_rows($q1) <= 0)
{
    alert("Student Existence is Questioned!");
}
else
{
    $q = mysqli_query($conn, "SELECT * FROM attendance WHERE id = '".$barcode."' and eventid = ".$eventId."");
    if(mysqli_num_rows($q) > 0)
    {
?>
<script>alert("Student Already Added!");</script>
<?php
    }
    else
    {
        mysqli_query($conn, "INSERT INTO attendance(eventid, id, etime) values(".$eventId.",'".$barcode."',now())");
    }
}
    echo "<meta http-equiv='refresh' content='0'>";
}
else if(isset($_POST['submitguest']))
{
$eventId = $_SESSION['eventId'];
$guestname = $_POST['guestname'];
$guestschool = $_POST['guestschool'];
mysqli_query($conn, "INSERT INTO guest_attendance(guest_name, guest_school, eventid, time_in) VALUES('".$guestname."','".$guestschool."','".$eventId."', now())");
    echo "<meta http-equiv='refresh' content='0'>";
}
else{
    
?>


<div class="container">
    <table class="highlight">
        <div class="row">
            <?php
                $q = mysqli_query($conn, "SELECT name from event where eventid = ".$_SESSION['eventId']."");
                $event_name = mysqli_fetch_assoc($q);
            ?>
            <h1> <?php echo $event_name['name']; ?></h1>
            <form class="col s12" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                <div class="row">
                    <div class="input-field col s12">
                        <input name="barcode" type="text" class="validate" autofocus>
                        <label class="active" for="barcode">Barcode</label>
                    </div>
                    
                    <script type="text/javascript" src="materialize/js/materialize.js"></script>
                    <div>
                        <button class="col s12 btn-large waves-effect waves-purple purple" type="submit" name="submit">Submit</button>
                    </div>
                </div>
            </form>
            
        </div>
    </table>
    
    <div class="row">
        <!--<form class="col s12" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
            <div class="row">
                <div>  
                    <button class="col s12 btn-large waves-effect waves-light" type="submit" name="generateReport" >Generate Report</button>
                    
                </div>
            </div>
        </form>-->
        <form class="col s12" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                <div class="row">
                    <div class="input-field col s12">
                        <input name="guestname" type="text" class="validate">
                        <label class="active" for="guestname">Guest Name</label>
                    </div>
					
                    <div class="input-field col s12">
                        <input name="guestschool" type="text" class="validate">
                        <label class="active" for="guestschool">School</label>
                    </div>
					
                    <script type="text/javascript" src="materialize/js/materialize.js"></script>
                    <div>
                        <button class="col s12 btn-large waves-effect waves-purple purple" type="submit" name="submitguest">Guest Login</button>
                    </div>
                </div>
        </form>
        <script>
            $(document).ready(function() {
                $('select').material_select();
            });
        </script>
        <?php
    /*      
    if(isset($_POST['generateReport']))
    {
        ?>
        <form method="POST" action="report_attendance.php">
                                
            <input type="hidden" value="<?php echo $_SESSION['eventId'] ?>" name="eventId">
                            
            <div class="input-field col s12">
                <select name="course">
                    <option value="BS-IT">Information Technology</option>
                    <option value="BS-IS">Information Systems</option>
                    <option value="BS-COMPSC">Computer Science</option>
                </select>
                <label>Course</label>
            </div>
            <div class="input-field col s12">
                <select name="yearlevel">
                    <option value="1">1ST</option>
                    <option value="2">2ND</option>
                    <option value="3">3RD</option>
                    <option value="4">4TH</option>
                </select>
                <label>Year Level</label>
            </div>
            <button class="col s12 btn-large waves-effect waves-light" type="submit" name="submitParticipants">Submit</button>
                            
        </form>
        <?php
    }
      */  ?>
    </div>
    <table class="highlight"> 
        <tr>
        <td> Student Count </td>
        <td> 
            <?php  $studentCount = mysqli_query($conn, "select count(*) from attendance where eventid = ".$_SESSION['eventId']."");
                    $stud = 0;
        /*$studentList = mysqli_query($conn, "SELECT * FROM STUDENT where barcode = ");*/
        while($row = mysqli_fetch_array($studentCount)){
            $stud += $row[0];
            echo $row[0];
        }?>
            </td>
        <td> Guest Count</td>
        <td><?php  $studentCount = mysqli_query($conn, "select count(*) from guest_attendance where eventid = ".$_SESSION['eventId']."");
        /*$studentList = mysqli_query($conn, "SELECT * FROM STUDENT where barcode = ");*/
        while($row = mysqli_fetch_array($studentCount)){
            $stud += $row[0];
            echo $row[0];
        }?></td>
        </tr>
        <tr>
            <td> Total Participants </td>
            <td> <?php echo $stud ?> </td>
        </tr>
        
    </table>
    <h3> Students </h3>
    <div id="participantList">
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
    WHERE p.id = s.barcode and eventid = ".$_SESSION['eventId']." order by p.etime desc limit 20");
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
    </div>
<br>
    <h3> Guests </h3>
    <div id="guestList">
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
        WHERE eventid = ".$_SESSION['eventId']." order by s.time_in desc limit 15");
        while($row = mysqli_fetch_array($guestList))
        {                    
            echo "<tr><td>".$row[0]."</td>";
            echo "<td>".$row[1]."</td>";
            echo "<td>".$row[2]."</td></tr>";
        }
        ?>
</table>
    </div>
    <input type="hidden" value="<?php $_SESSION['viewEvent'] ?>" name="eventId">
</div>
<div class="container">
<table class="highlight">
<form method="POST" action="raffle.php">  
     <div class="row">                         
     <input type="hidden" value="<?php echo $_SESSION['eventId'] ?>" name="eventId">
     <button class="col s12 btn-large waves-effect waves-light" type="submit" name="raffle">Generate Raffle</button>  
     </div>                      
</form>
</table>
</div>
<br><br><br>

<style>
    .page-footer{
        background-color: #6d1f72;
    }
</style>
<footer class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">CSSEC Systems</h5>
                <p class="grey-text text-lighten-4">Attendance System Created by CSSEC Source Code.</p>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
                © 2017 Ferdinand Polpol 
        </div>
    </div>
</footer>
<?php
            
}
?>