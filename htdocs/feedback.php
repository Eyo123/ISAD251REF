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
	Please see the -<a href="documents/HCI.pdf"> HCI Document </a>- for feedback questions
	</div>
    <div class="row">
        <form action="feedback.php" method="post" id="feedback">
            <div class="form-group">
                <label for="record">Feedback:</label>
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
