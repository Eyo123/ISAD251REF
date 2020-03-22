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
print '<td><a href="delete.php?id=" class="btn btn-info">Delete your account</a></td></tr></tbody>';
?>


<?php

    $customerID = $_SESSION['customerId'];
    $database = new Database();
    $database->deleteCustomer($customerId);

    echo '<div class="container"><div class="row">';

    echo '<h3 class="text-info">your account has been deleted</h3>';

?>


<?php



?>
