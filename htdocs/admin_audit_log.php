<?php

$page_title = "Flightcrew Home";

require_once('database_classes/database.php');
include_once('includes/admin_header.html');

?>

<section role="contentinfo" aria-label="Flight Crew Audit Log Page">
<div class="container">
    <div class="row">
        <h2 class="bg-dark text-white">Audit Log</h2>
    </div>
    <div class="row">
        <p>Records</p>
        <?php
        $database = new Database();
        # retrieve products which are for sale
        $rowSet = $database->vw_auditLogRecords();

        if($rowSet)
        {
            print "<table class=\"table table-bordered\">";
            print "<thead><tr><th scope='col'>ID</th>";
            print "<th scope='col'>Timestamp</th>";
            print "<th scope='col'>Record</th></tr></thead>";

            foreach ($rowSet as $row)
            {
                print "<tbody><tr><td>".$row['AuditLogID']."</td>";
                print "<td>".$row['AuditLogTimeStamp']."</td>";
                print "<td>".$row['AuditLogRecord']."</td></tr></tbody>";
            }
            print "</table>";
        }

        ?>
    </div>
</div>
</section>

<?php

include_once('includes/admin_footer.html');

?>
