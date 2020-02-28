<?php

$page_title = 'Create Account';

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');

$customerId = $_SESSION['customerId'];
$database = new Database();
$originAirports = $database->vw_originAirports();#array("London", "New York", "Geneva");
$destinationAirports = $database-> vw_destinationAirports();


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

  if (!empty($destination))
  {
      $errors[] = 'Enter the flight arrival place.';
  }

  if (!empty($date))
  {
      $errors[] = 'Enter the flight date.';
  }
  
  # check if email already registered, provided there were no form submission errors
  if(empty($errors))
  {
	$database = new Database();
	$results = $database->registerCustomer($email,$firstName,$lastName,$pass1);
	
    if($results == 'already registered')
    {
        $errors[] = 'Email address already registered. <a href="login.php">Login</a>';
    }
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
    $database = new Database();
    $results = $database->registerCustomer($email,$firstName,$lastName,$pass1);
    echo '<h3 class="test-info">You are now registered</h3><h4><a href="login.php">Login</a></h4>';
    include_once('includes/footer.html');
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

<section role="contentinfo" aria-label="Flightcrew search page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Flight Search</h2>
    </div>
    <div class="row">
        <form action="search_results.php" method="post">
            <div class="form-group">
                <label for="origin">Origin:</label>
                
                <select name="origin" class="form-control" id="origin" width="50"
                value="<?php if (isset($_POST['origin'])) echo $_POST['origin']; ?>" required>>
                        <?php
                            foreach ($originAirports as $currentAirport){
                                echo '<option value="'.$currentAirport.'">'.$currentAirport.'</option>';
                            }
                        ?>
                </select>
            </div>

            <div class="form-group">
                <label for="destination">Destination:</label>
                <select name="destination" class="form-control" id="destination" width="50"
                value="<?php if (isset($_POST['destination'])) echo $_POST['destination"']; ?>" required>>
                        <?php
                            foreach ($destinationAirports as $currentAirport){
                                echo '<option value="'.$currentAirport.'">'.$currentAirport.'</option>';
                            }
                        ?>
                </select>
      
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" class="form-control" id="date" size="50"
                       value="<?php if (isset($_POST['date'])) echo $_POST['date']; ?>" required>
            </div>
            <div class="form-group">
                <p><input type="submit" value="Search">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
</div>
</section>

<?php 

include_once('includes/footer.html'); 

?>
