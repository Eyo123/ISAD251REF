<?php

$page_title = "Add Airport";

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
  $name = secure_input($_POST['name']);
  $country = secure_input($_POST['country']);
  $lat = secure_input($_POST['lat']); 
  $long = secure_input($_POST['long']);
  $errors = array();

  if(empty($code))
  {
    $errors[] = 'Enter a code for the airport.';
  }
  
  if(empty($name))
  {
    $errors[] = 'Enter a name for the airport.';
  }
  
  if(empty($country))
  {
    $errors[] = 'Enter the airport country.';
  }
  
  if(empty($lat))
  {
    $errors[] = 'Enter the latutude.';
  }
  
  if(empty($long))
  {
    $errors[] = 'Enter the longitude.';
  }
  
  # check if email already registered, provided there were no form submission errors
  if(empty($errors))
  {
	$database = new Database();
	$database->addAirport($code,$name,$country,$lat,$long);
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
	echo '<div class="container"><div class="row">';
    echo '<h3 class="text-info">Airport Added</h3></div></div>';
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

<section role="contentinfo" aria-label="Flight Crew Add Airport Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Add Airport</h2>
    </div>
    <div class="row">
        <form action="admin_add_airport.php" method="post" id="addAirport">
            <div class="form-group">
                <label for="code">Code:</label>
                <input type="text" name="code" class="form-control" id="code" size="50"
                       value="<?php if (isset($_POST['code'])) echo $_POST['code']; ?>" required>
            </div>
			<div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" id="name" size="50"
                       value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" required>
            </div>
			<div class="form-group">
                <label for="country">Country:</label>
                <input type="text" name="country" class="form-control" id="country" size="50"
                       value="<?php if (isset($_POST['country'])) echo $_POST['country']; ?>" required>
            </div>
			<div class="form-group">
                <label for="lat">Latitude:</label>
                <input type="number" step="0.0001" name="lat" class="form-control" id="lat" size="50"
                       value="<?php if (isset($_POST['lat'])) echo $_POST['lat']; ?>" required>
            </div>
			<div class="form-group">
                <label for="long">Longitude:</label>
                <input type="number" step="0.0001" name="long" class="form-control" id="long" size="50"
                       value="<?php if (isset($_POST['long'])) echo $_POST['long']; ?>" required>
            </div>
            <div class="form-group">
                <p><input type="submit" class="btn-info" value="Add">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
	<div class="row">
        <h3 class="text-info">Current Airports</h3>
        <?php
        $database = new Database();

        $rowSet = $database->vw_airports();

        if($rowSet)
        {
            print "<table class=\"table table-bordered\">";
            print "<thead><tr><th scope='col'>Code</th>";
            print "<th scope='col'>Name</th>";
            print "<th scope='col'>Country</th>";
			print "<th scope='col'>Latitude</th>";
			print "<th scope='col'>Longitude</th>";

            foreach ($rowSet as $row)
            {
                print "<tbody><tr><td>".$row['AirportCode']."</td>";
                print "<td>".$row['AirportName']."</td>";
				print "<td>".$row['AirportCountry']."</td>";
                print "<td>".$row['AirportLatitude']."</td>";
                print "<td>".$row['AirportLongitude']."</td></tr></tbody>";
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
