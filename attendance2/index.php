<?php include "config.php"; ?>
<html>

    <head>
        <link rel="stylesheet" href="materialize/css/materialize.css">
    </head>
    
    <body>
        <script type="text/javascript" src="materialize/js/materialize.js"></script>

        <?php
        if(isset($_POST['createEvent']))
        {
        ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
            Event Name  <input type="text" name="eventName"><br>
            Description        <input type="text" name="eventDesc"><br>
            System Type 
            <select name="eventSystem">
                <option>Attendance</option>
                <option>Voting</option>
            </select>

            <br>
            <button name="submitEvent" type="submit">Submit Event</button>
        </form>
            <?php
        }
        else
        {
            if(isset($_POST['submitEvent']))
            {
                $eventName = $_POST['eventName'];
                $eventDesc = $_POST['eventDesc'];

                mysqli_query($conn, "INSERT INTO event(name, description) values('".$eventName."','".$eventDesc."')");

            }
            ?>
        <div class="container">
            <table class="highlight">
                <div class="row">
                    <form class="col s12" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                        <div class="row">
                            <div>
                                
                                <button class="col s12 btn-large waves-effect waves-purple purple" type="submit" name="createEvent">Create New Event</button>
                            </div>
                        </div>
                    </form>
                </div>
            </table>
        
            <form method="POST" action="viewevent.php">
                
                <table class="col s6 bordered centered">
                    <tr>
                        <th colspan=2 style='width:20%;text-align:left'><center>Events</center></th>
                    </tr>
                    <?php

                    $query = mysqli_query($conn, "SELECT * FROM event");    
                    
                    while($row = mysqli_fetch_assoc($query))
                    {
                        $en = $row['name'];
                        echo "<tr>";
                        echo "<td>".$row['name']."<td>";
                        //echo "<input type='hidden' name='eventName' value='".$row['event_name']."'>";
                        echo "<button type='submit' name='viewEvent' value='".$row['eventid']."' class='btn waves-effect waves-purple purple'>View</button>";        
                        echo "</tr>";
                    }
                    
                    ?>
                </table>
            </form>
        </div>
            <?php
        }
        ?>
    </body>
</html>