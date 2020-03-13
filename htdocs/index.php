<?php

$page_title = "Flightcrew Home";

require_once('database_classes/database.php');
include_once('includes/header.html');

?>

<section role="contentinfo" aria-label="Flight Crew Home Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Home Page</h2>
    </div>
    <div class="row">
        <p>Our airline has the best safety record and uses advanced AI to ensure best customer service for our customers
        </p>
    </div>
    <div class="row">
        <p>Flights</p>
        <?php
        $database = new Database();
        # retrieve products which are for sale
        $rowSet = $database->vw_availableFlights();

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

        ?>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
