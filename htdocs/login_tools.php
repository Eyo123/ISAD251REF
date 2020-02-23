<?php # functions to help login

require_once('database_classes/database.php');

# load a page or login page by default
function load($page = 'login.php')
{
  # create a url
  $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

  # tidy in case trailing slashes, then add page
  $url = rtrim($url,'/\\');
  $url .= '/'.$page;

  # relocate and exit
  header("Location: $url");
  exit() ;
}

# validate user email and password
function validate($email='',$pwd='')
{
  # error array
  $errors = array() ;

  # is email field empty? syntax validation already done in the login form
  if (empty($email)) 
  {
	  $errors[] = 'Enter your email address.';
  } 

  # is password field empty?
  if (empty($pwd))
  {
	  $errors[] = 'Enter your password.';
  } 

  # if no errors, check the email and password
  if (empty($errors)) 
  {
      # retrieve the customerId and hashed password for this customer email
	$database = new Database();
	# if email is not found customerId will be set to 0
	list($hashedPassword,$customerId) = $database->customerLogin($email);
	
    if($customerId != 0 && password_verify($pwd,$hashedPassword))
    {
        # return true and customerId in an array
        return array(true,$customerId);
    }

    # otherwise add an error that they were not found
    $errors[] = 'Email address and password not found.' ;
	
  }
  # if errors, return false and errors
  return array(false,$errors); 
}
