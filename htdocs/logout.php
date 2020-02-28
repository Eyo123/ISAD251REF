<?php

$page_title = "Logout";

include_once('includes/header.html');
require ('login_tools.php');

# start the session
if(!isset($_SESSION))
{
	session_start();
}

# redirect to login page if not logged in
if (!isset($_SESSION['customerId']))
{
	load();
}

# clear session variables
$_SESSION = array();
  
# destroy the session
session_destroy();

?>

<section role="contentinfo" aria-label="Decaf Team Room logout page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">You have successfully logged out</h2>
    </div>
</div>
</section>

<?php

include_once('includes/footer.html');

?>
