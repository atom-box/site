<!-- This is page 2, after user clicks form submit -->

<?php
    // todo remove multiple ini_set after debugging
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    require './templates/top.php';
?>


<div class="container" style="margin-top:30px">
<!-- will be closed by bottom.php -->

    <?php
    // Load the form placeholder
    require './templates/successCard.php';
    ?>

    <?php
    // Load the form placeholder
    require './core/controllers/successController.php';
    ?>

<?php
// Load the form placeholder
require './templates/bottom.php';
?>

