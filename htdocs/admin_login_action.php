<?php # attempt to login

require_once('admin_login_tools.php');
require_once('secure_input.php');

# has the form been submitted?
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    # remove dangerous characters
  $username = secure_input($_POST['username']);
  $password = secure_input($_POST['password']);
  
   # validate the user
  list($check,$data) = validate($username,$password);

  # if password is correct
  if($check)
  {
    # start the session, set session email value, and load the index page
    session_start();
	$_SESSION['admin'] = 1;
    load('admin_index.php');
  }
  # or load the error array with the errors
  else
    {
      $errors = $data;
    }
}

# if no form was submitted or the user was not validated load the login page
include_once('admin_login.php');
