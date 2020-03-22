<?php

$page_title = "delete";

include_once('includes/header.html');
require_once('database_classes/database.php');
require_once('login_tools.php');

# redirect to login page if not logged in
if (!isset($_SESSION['customerId']))
{
    load();
}

?>
<?php

//  this function delete data in the sql

if (isset($_GET['id']))
{
    $customerID = $_GET['id'];
    $database = new Database();
    $database->deleteCustomer($customerId);

    echo '<div class="container"><div class="row">';
    echo '<h3 class="text-info">your account has been deleted</h3></div></div>';

}
else
{
    echo '<h3 class="text-info">No </h3>';

}






?>


<?php

include_once('includes/footer.html');

?>
