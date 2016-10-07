<?php
    include_once('./template/header.php');

    //load navbar
    include_once('./template/navbar.php');

    include ('./class/dbConn.php');
    $dataClass = new dbConn('a');
?>
<div class="row">
    <div class="col-md-12 text-center">
        <h3>Property List</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table">

        </table>
    </div>
</div>

<?php include_once ('./template/footer.php'); ?>