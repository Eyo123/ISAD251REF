<?php

$page_title = "Template";

include_once('includes/header.html');
require_once('database_classes/database.php');

?>

<section role="contentinfo" aria-label="Flight Crew Template Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Home</h2>
    </div>
    <div class="row">
        <p>Please have a look at our latest flights</p>
    </div>
	<div class="row">
		<?php
		$database = new Database();
		# book a flight
		$customerID = 1;
		$journeyID = 1;
		#$bookingID = $database->pr_bookFlight($customerID,journeyID);
		?>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
