    <?php

    include_once ('./template/header.html');

    //load navbar
    include_once('./template/navbar.php');
    ?>


      <!-- Example row of columns -->
        <div class="row">
            <div class="col-md-12">
                <?php
                $login = false;

                if($login){

                }else{
                    ?>
                    <div class="alert alert-warning text-center" role="alert">
                        Please login to continue
                    </div>
                    <?php
                    include_once ('./template/loginForm.php');
                }

                ?>
            </div>
        </div>


    <?php include_once ('./template/footer.php'); ?>


