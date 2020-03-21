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

if(isset($_POST['delete']))
{
    try {
        $pdoConnect = new PDO("mysql:host=localhost;dbname=test_db","root","");
    } catch (PDOException $exc) {
        echo $exc->getMessage();
        exit();
    }



    // SQL to delete the query
    $id = $_POST['id'];

    $pdoQuery = "DELETE FROM `users` WHERE `column name` = :column name";

    $pdoResult = $pdoConnect->prepare($pdoQuery);

    $pdoExec = $pdoResult->execute(array(":column"=>$column));



}

?>


<?php

include_once('includes/footer.html');

?>