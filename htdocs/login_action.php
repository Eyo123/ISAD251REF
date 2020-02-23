<?php # attempt to login

require_once('login_tools.php');
require_once ('secure_input.php');

# has the form been submitted?
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $email = secure_input($_POST['email']);
  $pass = secure_input($_POST['pass']);

  # validate the user
  list($check,$data) = validate($email,$pass) ;

  # if the password was correct
  if($check)
  {
    # start the session, set session email value, and load the index page
    session_start();
    $_SESSION['customerId'] = $data;
    load('index.php') ;
  }
  # or load the error array with the errors
  else
   {
      $errors = $data;
   }
}

# if no form was submitted or the user was not validated load the login page
include ('login.php') ;
