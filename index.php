    <?php

    include_once('./template/header.php');

    //load navbar
    include_once('./template/navbar.php');
    ?>


      <!-- Example row of columns -->
        <div class="row">
            <div class="col-md-12">
                <?php
                if(loginStatus()){
                    echo "<div class='text-center'>";
                    echo "<h1>Welcome to the management system</h1>";
                    echo "</div>";
                }else{
                    include_once ('./template/loginForm.php');
                }

                ?>
            </div>
        </div>


    <?php include_once ('./template/footer.php'); ?>


