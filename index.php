<?php
// include_once 'header.php';
?>
<!-- Will Need to make this usable or remove. -->
<div class="greeting">
    <?php
        if (isset($_SESSION["userid"])) {
            echo "<p>Welcome<br>" .$_SESSION["name"]. " </p>";
        } else {
            header("Location: dashboard.php");
            exit();
        }
    ?>
</div>

<?php
// include_once 'footer.php';
?>

<?php 
    // echo phpinfo(); 
?>