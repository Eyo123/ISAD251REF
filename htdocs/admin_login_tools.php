<?php # functions to help login

# load a page or login page by default
function load($page = 'admin_login.php')
{
  # create a url
  $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

  # tidy in case of trailing slashes, then add page
  $url = rtrim($url,'/\\');
  $url .= '/'.$page;

  # relocate and exit
  header("Location: $url") ; 
  exit() ;
}

# validate user email and password
function validate($username='',$password='')
{
  # error array
  $errors = array() ; 

  # is email field empty? syntax validation already done in the login form
  if (empty($username)) 
  {
	  $errors[] = 'Enter your username.';
  } 

  # is password field empty?
  if (empty($password))
  {
	  $errors[] = 'Enter your password.';
  } 

  # if no errors, check the email and password
  if (empty($errors))
  {
      # only one login for administrators
    if ($username == 'admin' && $password == 'pass1')
    {
        # return true in an array, second parameter redundant here
        return array(true,$errors);
    }

    # otherwise add an error that they were not found
    $errors[] = 'Username and password were not validated.' ;
	
  }
  # if errors, return false and errors
  return array(false,$errors); 
}
