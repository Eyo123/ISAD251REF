<?php

$page_title = "Bookings";

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once('login_tools.php');

# redirect to login page if not logged in
if (!isset($_SESSION['customerId']))
{
	load();
}

?>

<section role="contentinfo" aria-label="Flightcrew bookings page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Bookings</h2>
    </div>
	<div class="row">
        <?php
		$customerId = $_SESSION['customerId'];
		$database = new Database();
		# retrieve bookings by customerID
		$rowSet = $database->bookings($customerId);

		if($rowSet)
		{
            print "<table class=\"table table-bordered\">";
            print "<thead><tr><th scope='col'>Flight Code</th>";
            print "<th scope='col'>Departure Place</th>";
            print "<th scope='col'>Arrival Place</th>";
            print "<th scope='col'>Date</th>";
            print "<th scope='col'>Departure Time</th>";
            print "<th scope='col'>Arrival Time</th></tr></thead>";

            foreach ($rowSet as $row)
            {
                print "<tbody><tr><td>".$row['FlightPlanCode']."</td>";
                print "<td>".$row['FlightPlanOrigin']."</td>";
                print "<td>".$row['FlightPlanDestination']."</td>";
                print "<td>".$row['JourneyDate']."</td>";
                print "<td>".$row['JourneyDepartureTime']."</td>";
                print "<td>".$row['JourneyArrivalTime']."</td>";
                print '<td><a href="pay.php?id='.$row['BookingID'].'" class="btn btn-info">Pay</a></td>';
                print '<td><a href="cancel.php?id='.$row['BookingID'].'" class="btn btn-info">Cancel</a></td></tr></tbody>';
            }
            print "</table>";
		}
		else
		{
			print 'You do not have any bookings.';
		}
		
		?>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
