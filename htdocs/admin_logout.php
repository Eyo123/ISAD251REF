<?php

$page_title = "Admin Logout";

include_once('includes/admin_header.html');

# start the session
if(!isset($_SESSION))
{
	session_start();
}

# redirect to login page if not logged in
if (!isset($_SESSION['admin']))
{
	require_once('admin_login_tools.php');
	load();
}

# clear session variables
$_SESSION = array();
  
# destroy the session
session_destroy();

?>

<section role="contentinfo" aria-label="Decaf Team Room admin logout page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">You have successfully logged out</h2>
    </div>
</div>
</section>

<?php

include_once('includes/admin_footer.html');

?>
