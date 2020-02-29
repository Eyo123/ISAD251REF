<?php

$page_title = "Encrypt Message";

include_once('includes/admin_header.html');
require_once ('secure_input.php');

# if registration form submitted need to check it
if($_SERVER['REQUEST_METHOD']=='POST')
{ 
  $message = secure_input($_POST['message']);
  $action = secure_input($_POST['action']);
  $errors = array();

  if(empty($message))
  {
    $errors[] = 'Enter a message.';
  }
  
  # encrypt or decrypt the message
  if(empty($errors))
  {
	if($action = "encrypt")
	{
		
	}
	else if($action = "decrypt")
	{
		
	}
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
    echo '<h3 class="text-info">Result</h3>';
	echo $result;
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
        <form action="admin_encrypt_message.php" method="post" id="encrypt_message">
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea rows="10" cols="50" name="message" class="form-control" id="message" required></textarea>
            </div>
			<div class="form-group">
                <label for="encrypt">Encypt:</label>
                <input type="radio" name="action" class="form-control" id="encrypt" value="encrypt">
				<label for="decrypt">Decrypt:</label>
                <input type="radio" name="action" class="form-control" id="decrypt" value="decrypt">
            </div>
            <div class="form-group">
                <p><input type="submit" value="Calculate">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
