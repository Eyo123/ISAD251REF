<?php

$page_title = 'Create Account';

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once ('secure_input.php');

# if registration form submitted need to check it
if($_SERVER['REQUEST_METHOD']=='POST')
{ 
  $email = secure_input($_POST['email']); 
  $pass1 = secure_input($_POST['pass1']);
  $pass2 = secure_input($_POST['pass2']);
  $firstName = secure_input($_POST['firstName']);
  $lastName = secure_input($_POST['lastName']);
  $address1 = secure_input($_POST['address1']);
  $address2 = secure_input($_POST['address2']);
  $postcode = secure_input($_POST['postcode']);
  $errors = array();

  if(isset($_POST['termsAndConditions']) == false)
  {
      $errors[] = 'Terms and conditions not accepted';
  }

  if(empty($email))
  {
    $errors[] = 'Enter your email address.';
  }

  # validate e-mail for valid syntax
  else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $errors[] = 'Invalid e-mail address';
  }

  if(empty($address1))
  {
      $errors[] = 'Enter your address.';
  }

  if(empty($postcode))
  {
      $errors[] = 'Enter your postcode.';
  }

  if (!empty($pass1))
  {
    if ($pass1!=$pass2)
    {
        $errors[]='Passwords do not match.';
    }
  }
  else
  {
    $errors[]='Enter your password.';
  }
  
  # check if email already registered, provided there were no form submission errors
  if(empty($errors))
  {
	$database = new Database();
	$results = $database->registerCustomer($email,$firstName,$lastName,$pass1,$address1,$address2,$postcode);
	
    if($results == 'already registered')
    {
        $errors[] = 'Email address already registered. <a href="login.php">Login</a>';
    }
  }
  
  # if registration was successful
  if (empty($errors)) 
  {
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

<section role="contentinfo" aria-label="Flight crew register page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Create Account</h2>
    </div>
    <div class="row">
        <form action="register.php" method="post" id="register">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" class="form-control" id="email" size="50"
                       value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" class="form-control" id="firstName" size="50"
                       value="<?php if (isset($_POST['firstName'])) echo $_POST['firstName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" class="form-control" id="lastName" size="50"
                       value="<?php if (isset($_POST['lastName'])) echo $_POST['lastName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address1">Address1:</label>
                <input type="text" name="address1" class="form-control" id="address1" size="50"
                       value="<?php if (isset($_POST['address1'])) echo $_POST['address1']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address2">Address2:</label>
                <input type="text" name="address2" class="form-control" id="address2" size="50"
                       value="<?php if (isset($_POST['address2'])) echo $_POST['address2']; ?>">
            </div>
            <div class="form-group">
                <label for="postcode">Postcode:</label>
                <input type="text" name="postcode" class="form-control" id="postcode" size="50"
                       value="<?php if (isset($_POST['postcode'])) echo $_POST['postcode']; ?>" required>
            </div>
            <div class="form-group">
                <label for="pass1">Password:</label>
                <input type="password" name="pass1" minlength="10" class="form-control" id="pass1" size="20"
                       id="password_to_check" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" required>
            </div>
            <div class="form-group">
                <label for="pass2">Confirm Password:</label>
                <input type="password" name="pass2" minlength="10" class="form-control" id="pass2" size="20"
                       value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" required>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="termsAndConditions" id="acceptTermsAndConditions">
                <label class="form-check-label" for="acceptTermsAndConditions">I agree to the <a href="terms_and_conditions.php" class="btn btn-info">terms and conditions</a></label>
            </div>
            <div class="form-group">
                <p><input type="submit" value="Register">
                    <input type="reset" value="Clear"></p>
            </div>
            <p id="check_password_result"></p>
        </form>
    </div>
</div>
</section>

<?php 

include_once('includes/footer.html'); 

?>
