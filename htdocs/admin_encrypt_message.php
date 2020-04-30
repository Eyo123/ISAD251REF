<?php

$page_title = "Generate a Secure Message";

include_once('includes/admin_header.html');
require_once ('secure_input.php');
require_once ('caesar_cipher.php');
require_once('admin_login_tools.php');

# redirect to login page if not logged in
if (!isset($_SESSION['admin']))
{
	load();
}

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
	if($action == "encrypt")
	{
		$result=encrypt($message,3);
	}
	else if($action == "decrypt")
	{
		$result=encrypt($message,26-3);
	}
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
	echo '<div class="container"><div class="row">';
    echo '<h3 class="text-info">Result</h3></div>';
	echo '<div class="row"><h4>';
	echo $result;
	echo '</h4>';
	echo '</div></div>';
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

<section role="contentinfo" aria-label="Tempest Flights Generate a Secure Message Admin Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Generate a Secure Message</h2>
    </div>
    <div class="row">
        <form action="admin_encrypt_message.php" method="post" id="encrypt_message">
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea rows="10" name="message" class="form-control" id="message" required></textarea>
            </div>
			<div class="radio">
				<label><input type="radio" name="action" value="encrypt" checked>Encrypt</label>
            </div>
			<div class="radio">
				<label><input type="radio" name="action" value="decrypt">Decrypt</label>
            </div>
            <div class="form-group">
                <p><input type="submit" class="btn-info" value="Calculate">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
</div>
</section>

<?php

include_once('includes/admin_footer.html');

?>
