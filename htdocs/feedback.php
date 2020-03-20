<?php

$page_title = "Send feedback";

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');

# if registration form submitted need to check it
if($_SERVER['REQUEST_METHOD']=='POST')
{ 
  $record = secure_input($_POST['record']);
  $name = secure_input($_POST['name']);
  $errors = array();

  if(empty($record))
  {
    $errors[] = 'Enter a message.';
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
	$database = new Database();
	$results = $database->addFeedback($name,$record);
	
	echo '<div class="container"><div class="row">';
    echo '<h3 class="text-info">Thank you for your feedback which has been recorded</h3></div></div>';
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

<section role="contentinfo" aria-label="Tempest Flights Feedback Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Feedback Page</h2>
    </div>
	<div class="row">
	Please randomly select one of the three following HCI assessment documents, when completed send to: <a href="mailto:flightcrew2020@protonmail.com">flightcrew2020@protonmail.com</a>
	</div>
	<div class="row">
	<a href="documents/HCI_1.pdf">HCI assessment document 1</a>
	</div>
	<div class="row">
	<a href="documents/HCI_2.pdf">HCI assessment document 2</a>
	</div>
	<div class="row">
	<a href="documents/HCI_3.pdf">HCI assessment document 3</a>
	</div>
    <div class="row">
        <form action="feedback.php" method="post" id="feedback">
            <div class="form-group">
                <label for="record">Any Further Feedback:</label>
                <textarea rows="10" name="record" maxlength="10000" class="form-control" id="record" required></textarea>
            </div>
			<div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" id="name" size="50"
                       value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
            </div>
            <div class="form-group">
                <p><input type="submit" value="Submit">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
