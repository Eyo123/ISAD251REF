<?php # login page

# set the page title, include header
$page_title = 'Login';

include_once('includes/header.html');

# print any error messages
if (isset($errors) && !empty($errors))
{
 echo '<div class="container"><div class="row">';
 echo '<p class="text-info">There was a problem:<br>' ;
 foreach ( $errors as $msg ) { echo " - $msg<br>" ; }
 echo 'Please try again or <a href="register.php">Register</a></p></div></div>' ;
}

?>

<section role="contentinfo" aria-label="Flight crew login page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Login</h2>
    </div>
    <div class="row">
        <form action="login_action.php" method="post">
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="pass">Password:</label>
            <input type="password" class="form-control" name="pass" id="pass" required>
        </div>
        <div class="form-group">
            <p><input type="submit" value="Login">
                <input type="reset" value="Clear"></p>
        </div>
            <br><p><a href="register.php" class="btn btn-info btn-sm">Create Account</a></p>
        </form>
    </div>
</div>
</section>

<?php 

include_once('includes/footer.html');

?>
