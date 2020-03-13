<?php

$page_title = 'Search Results';

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');

?>

<section role="contentinfo" aria-label="Flightcrew search results page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Flight Search Results</h2>
    </div>
    <div class="row">
        <form action="bookings.php" method="post">
            <?php

            # if registration form submitted need to check it
            if($_SERVER['REQUEST_METHOD']=='POST')
            {
                $origin = secure_input($_POST['origin']);
                $destination = secure_input($_POST['destination']);
                $date = secure_input($_POST['date']);
                $errors = array();

                if(empty($origin))
                {
                    $errors[] = 'Enter the flight departure place.';
                }

                if (empty($destination))
                {
                    $errors[] = 'Enter the flight arrival place.';
                }

                if (empty($date))
                {
                    $errors[] = 'Enter the flight date.';
                }

                # echo "Post Variables: <br> $origin <br> $destination <br> $date <br>";

                # if registration was successful
                if (empty($errors))
                {
                    $database = new Database();
                    $rowSet = $database->searchFlights($origin,$destination,$date);

                    if($rowSet)
                    {
                        print "<table class=\"table table-bordered\">";
                        print "<thead><tr><th scope='col'>Flight Code</th>";
                        print "<th scope='col'>Departure Place</th>";
                        print "<th scope='col'>Arrival Place</th>";
                        print "<th scope='col'>Date</th></tr></thead>";

                        foreach ($rowSet as $row)
                        {
                            print "<tbody><tr><td>".$row['FlightPlanCode']."</td>";
                            print "<td>".$row['FlightPlanOrigin']."</td>";
                            print "<td>".$row['FlightPlanDestination']."</td>";
                            print "<td>".$row['JourneyDateFormatted']."</td>";
                            print '<td><a href="book.php?id='.$row['JourneyID'].'" class="btn btn-info">Book</a></td></tr></tbody>';
                        }
                        print "</table>";
                    }

                }
                else
                {
                    echo '<p class="text-info">The following error(s) occurred:<br>' ;
                    foreach ($errors as $msg)
                    {
                        echo " - $msg<br>";
                    }
                    echo 'Please try again.</p>';
                }
            }
            ?>
        </form>
    </div>
</div>
</section>

<?php 

include_once('includes/footer.html'); 

?>
