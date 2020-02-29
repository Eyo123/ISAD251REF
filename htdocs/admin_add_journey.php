<?php

$page_title = "Add Journey";

include_once('includes/admin_header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');

$database = new Database();
$flightPlanCodes = $database->vw_flightPlanCodes();

# if registration form submitted need to check it
if($_SERVER['REQUEST_METHOD']=='POST')
{ 
  $code = secure_input($_POST['code']);
  $date = secure_input($_POST['date']);
  $departureTime = secure_input($_POST['departureTime']);
  $arrivalTime = secure_input($_POST['arrivalTime']);
  $availableSeats = secure_input($_POST['availableSeats']);
  $price = secure_input($_POST['price']);
  $errors = array();

  if(empty($code))
  {
    $errors[] = 'Enter a code for the flight.';
  }
  
  if(empty($date))
  {
    $errors[] = 'Enter the date for the journey.';
  }
  
  if(empty($departureTime))
  {
    $errors[] = 'Enter the departure time.';
  }
  
  if(empty($arrivalTime))
  {
    $errors[] = 'Enter the arrival time.';
  }
  
  if(empty($availableSeats))
  {
    $errors[] = 'Enter the number of available seats.';
  }
  
  if(empty($price))
  {
    $errors[] = 'Enter the journey price.';
  }
  
  # check if email already registered, provided there were no form submission errors
  if(empty($errors))
  {
	$database = new Database();
	$database->addJourney($code,$date,$departureTime,$arrivalTime,$availableSeats,$price);
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
    echo '<h3 class="text-info">Journey Added</h3>';
    include_once('includes/admin_footer.html');
    exit();
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

<section role="contentinfo" aria-label="Flight Crew Add Journey Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Add Journey</h2>
    </div>
    <div class="row">
        <form action="admin_add_journey.php" method="post" id="journey">
		
			<div class="form-group">
                <label for="code">Flight Plan Code:</label>
				<select name="code" class="form-control" id="code" width="50"
                value="<?php if (isset($_POST['code'])) echo $_POST['code']; ?>" required>
                        <?php
                            foreach ($flightPlanCodes as $flightPlanCode){
                                echo '<option value="'.$flightPlanCode.'">'.$flightPlanCode.'</option>';
                            }
                        ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Journey Date:</label>
                <input type="date" name="date" class="form-control" id="date" size="50"
                       value="<?php if (isset($_POST['date'])) echo $_POST['date']; ?>" required>
            </div>
			<div class="form-group">
                <label for="departureTime">Departure Time:</label>
                <input type="text" name="departureTime" class="form-control" id="departureTime" size="50"
                       value="<?php if (isset($_POST['departureTime'])) echo $_POST['departureTime']; ?>" required>
            </div>
			<div class="form-group">
                <label for="arrivalTime">Arrival Time:</label>
                <input type="text" name="arrivalTime" class="form-control" id="arrivalTime" size="50"
                       value="<?php if (isset($_POST['arrivalTime'])) echo $_POST['arrivalTime']; ?>" required>
            </div>
			<div class="form-group">
                <label for="availableSeats">Available Seats:</label>
                <input type="text" name="availableSeats" class="form-control" id="availableSeats" size="50"
                       value="<?php if (isset($_POST['availableSeats'])) echo $_POST['availableSeats']; ?>" required>
            </div>
			<div class="form-group">
                <label for="price">Price:</label>
                <input type="text" name="price" class="form-control" id="price" size="50"
                       value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>" required>
            </div>
            <div class="form-group">
                <p><input type="submit" value="Add">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
	<div class="row">
        <h3 class="text-info">Current Journeys</h3>
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
            print "<th scope='col'>Date</th>";
			print "<th scope='col'>Departure Time</th>";
			print "<th scope='col'>Arrival Time</th>";
			print "<th scope='col'>Available Seats</th>";
			print "<th scope='col'>Price</th></tr></thead>";

            foreach ($rowSet as $row)
            {
                print "<tbody><tr><td>".$row['FlightPlanCode']."</td>";
                print "<td>".$row['FlightPlanOrigin']."</td>";
                print "<td>".$row['FlightPlanDestination']."</td>";
                print "<td>".$row['JourneyDate']."</td>";
				print "<td>".$row['JourneyDepartureTime']."</td>";
				print "<td>".$row['JourneyArrivalTime']."</td>";
				print "<td>".$row['JourneyAvailableSeats']."</td>";
				print "<td>".$row['JourneyPrice']."</td></tr></tbody>";
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
