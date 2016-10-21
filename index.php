<?php

include_once('./template/header.php');

//load navbar
include_once('./template/navbar.php');
?>


<!-- Example row of columns -->
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <h3>Property Search</h3>
        </div>
    </div>
    <div class="col-md-12">
        <?php
        if (loginStatus()) {

            // start the database part
            global $dataClass;


            ?>

            <form class="form-horizontal">
                <fieldset>
                    <!-- Text input-->
                    <div class="form-group">
                        <label for="s_suburb">Suburb</label>
                        <input id="s_suburb" name="s_suburb" type="text" placeholder="" class="form-control input-md">
                    </div>

                    <div class="form-group">
                        <label for="s_type">Property Type</label>
                        <input id="s_type" name="s_type" type="text" placeholder="" class="form-control input-md">
                    </div>

                    <!-- Multiple Checkboxes -->
                    <div class="form-group">
                        <label for="s_features">Property Features</label>
                        <div class="checkbox">
                            <label for="s_features-0">
                                <input type="checkbox" name="s_features" id="s_features-0" value="1">
                                Option one
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="s_features-1">
                                <input type="checkbox" name="s_features" id="s_features-1" value="2">
                                Option two
                            </label>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <label for="submit"></label>
                        <button id="submit" name="submit" class="btn btn-primary">Button</button>
                    </div>

                </fieldset>
            </form>

            <?php
        } else {
            include_once('./template/loginForm.php');
        }

        ?>
    </div>
</div>


<?php include_once('./template/footer.php'); ?>


