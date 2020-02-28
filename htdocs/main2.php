<?php

$page_title = "THE FLIGHT CREW";

include_once('includes/header.html');
require_once('database_classes/database.php');

?>

<body>

    <form action="search_results.php" method="post">
    <div class="booking-form-box">


        <div class ="booking-form">
            <label>FROM</label>
            <input type="text" name="origin" class="form-control" placeholder="your actual country">

            <div class ="booking-form">
                <label>TO</label>
                <input type="text" name="destination" class="form-control" placeholder="visited country">

            <div class ="input-grp">
                <label>Outbound</label>
                <input type="date" name="date" class="form-control select-date">
            </div>

            <div class="input-grp">
                <button type="submit"  class="btn btn-primary flight">Find Times and prices</button>

            </div>



            </div>


        </div>

    </div>
    </form>

<?php

    include_once('includes/footer.html');

?>



