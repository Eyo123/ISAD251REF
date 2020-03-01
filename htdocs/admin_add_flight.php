<?php

$page_title = "Add Flight";

include_once('includes/admin_header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');
require_once('admin_login_tools.php');

# redirect to login page if not logged in
if (!isset($_SESSION['admin']))
{
	load();
}

# if registration form submitted need to check it
if($_SERVER['REQUEST_METHOD']=='POST')
{ 
  $code = secure_input($_POST['code']); 
  $origin = secure_input($_POST['origin']);
  $destination = secure_input($_POST['destination']);
  $errors = array();

  if(empty($code))
  {
    $errors[] = 'Enter a code for the flight.';
  }
  
  if(empty($origin))
  {
    $errors[] = 'Enter the flight origin.';
  }
  
  if(empty($destination))
  {
    $errors[] = 'Enter the flight destination.';
  }
  
  # check if email already registered, provided there were no form submission errors
  if(empty($errors))
  {
	$database = new Database();
	$database->addFlightPlan($code,$origin,$destination);
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
	echo '<div class="container"><div class="row">';
    echo '<h3 class="text-info">Flight Plan Added</h3></div></div>';
    include_once('includes/admin_footer.html');
    exit();
  }
  else 
  {
	echo '<div class="container"><div class="row">';
    echo '<p class="text-info">The following error(s) occurred:<br>' ;
    foreach ($errors as $msg)
    {
        echo " - $msg<br>";
    }
    echo 'Please try again.</p></div></div>';
  }  
}

?>

<section role="contentinfo" aria-label="Flight Crew Add Flight Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Add Flight Plan</h2>
    </div>
    <div class="row">
        <form action="admin_add_flight.php" method="post" id="addFlightPlan">
            <div class="form-group">
                <label for="origin">Origin:</label>
                <input type="text" name="origin" class="form-control" id="origin" size="50"
                       value="<?php if (isset($_POST['origin'])) echo $_POST['origin']; ?>" required>
            </div>
			<div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" name="destination" class="form-control" id="destination" size="50"
                       value="<?php if (isset($_POST['destination'])) echo $_POST['destination']; ?>" required>
            </div>
			<div class="form-group">
                <label for="code">Flight Plan Code:</label>
                <input type="text" name="code" class="form-control" id="code" size="50"
                       value="<?php if (isset($_POST['code'])) echo $_POST['code']; ?>" required>
            </div>
            <div class="form-group">
                <p><input type="submit" value="Add">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
	<div class="row">
        <h3 class="text-info">Current Flight Plans</h3>
        <?php
        $database = new Database();
        # retrieve products which are for sale
        $rowSet = $database->vw_flightPlans();

        if($rowSet)
        {
            print "<table class=\"table table-bordered\">";
            print "<thead><tr><th scope='col'>Flight Plan Origin</th>";
            print "<th scope='col'>Flight Plan Destination</th>";
            print "<th scope='col'>Flight Plan Code</th></tr></thead>";

            foreach ($rowSet as $row)
            {
                print "<tbody><tr><td>".$row['FlightPlanOrigin']."</td>";
                print "<td>".$row['FlightPlanDestination']."</td>";
                print "<td>".$row['FlightPlanCode']."</td></tr></tbody>";
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
