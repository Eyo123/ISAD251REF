<?php # login page

# set the page title, include header
$page_title = 'Admin Login';

include_once('includes/admin_header.html');

# print any error messages
if (isset($errors) && !empty($errors))
{
 echo '<div class="container"><div class="row">';
 echo '<p class="text-info">There was a problem:<br>' ;
 foreach ( $errors as $msg ) { echo " - $msg<br>" ; }
 echo 'Please try again</p></div></div>' ;
}
?>

<section role="contentinfo" aria-label="Decaf Team Room admin login page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Admin Login</h2>
    </div>
    <div class="row">
        <form action="admin_login_action.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>
        <div class="form-group">
            <p><input type="submit" value="Login">
                <input type="reset" value="Clear"></p>
        </div>
    </div>
</div>
</section>

<?php 

include_once('includes/admin_footer.html'); 

?>
