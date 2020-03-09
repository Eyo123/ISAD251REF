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

<section role="contentinfo" aria-label="Flightcrew booking cancelled page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Bookings</h2>
    </div>
	<div class="row">
        <?php
            echo($_GET['id']);

            # if the customer wishes to cancel
            if (isset($_GET['id']))
            {
                

                $bookingID = $_GET['id'];

                $database = new Database();
                $database->cancelBooking($bookingID);

                echo '<h3 class="text-info">You have successfully cancelled your booking.</h3>';
            }
            else
            {
                echo '<h3 class="text-info">We could not cancel that booking. Please check the booking exists.</h3>';
            }
            ?>
    
</div>
</section>

<?php

include_once('includes/footer.html');

?>
