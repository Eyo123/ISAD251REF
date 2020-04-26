<?php

$page_title = 'Flight Search';

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');


$database = new Database();
$originAirports = $database->vw_AirportCodes();#array("London", "New York", "Geneva");
$destinationAirports = $database-> vw_AirportCodes();
 

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
