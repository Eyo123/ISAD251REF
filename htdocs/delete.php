<?php

$page_title = "delete";

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once('login_tools.php');
require_once ('secure_input.php');

# redirect to login page if not logged in
if (!isset($_SESSION['customerId']))
{
    load();
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
	$confirm = secure_input($_POST['confirm']);
	
	if(empty($confirm))
	{
		$errors[] = 'Enter the confirmation word.';
	}
	
	if($confirm != 'DELETE')
	{
		$errors[] = 'The confirmation word was incorrect.';
	}
	
	if(empty($errors))
	{
		$customerId = $_SESSION['customerId'];
		$database = new Database();
		$database->deleteCustomer($customerId);

		load('logout.php');
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

<section role="contentinfo" aria-label="Flightcrew delete account page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Delete Account</h2>
    </div>
    <div class="row">
        <form action="delete.php" method="post">
            <div class="form-group">
                <label for="confirm">Please enter the word DELETE to confirm account deletion:</label>
                <input type="text" name="confirm" class="form-control" id="confirm" size="50"
                       value="<?php if (isset($_POST['confirm'])) echo $_POST['confirm']; ?>" required>
            </div>
			<div class="form-group">
                <p><input type="submit" class="btn-info" value="Delete Account">
                    <input type="reset" value="Clear"></p>
            </div>
        </form>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
