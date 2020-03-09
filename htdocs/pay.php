<?php

$page_title = "Book";

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once('login_tools.php');

# redirect to login page if not logged in
if (!isset($_SESSION['customerId']))
{
    load();
}

?>

<section role="contentinfo" aria-label="Flightcrew booking">
    <div class="container">
        <div class="row">
            <h2 class="bg-dark text-white">complete your payment</h2>
        </div>
        <div class="row">
            <?php
            # if the customer wish to pay
            if (isset($_GET['id']))
            {
                $journeyId = $_GET['id'];
                $customerId = $_SESSION['customerId'];

                $database = new Database();
                $database->bookFlight($customerId,$journeyId);

                echo '<h3 class="text-info">you have confirm your booking</h3>';
            }
            else
            {
                echo '<h3 class="text-info">No flight booked</h3>';
            }
            ?>
        </div>
    </div>
</section>

<?php

include_once('includes/footer.html');

?>
