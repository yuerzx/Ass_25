<?php
include_once('./template/header.php');
//load navbar

include_once('./template/navbar.php');

?>
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>Client List</h3>
        </div>
        <div class="col-md-12">
            <a href="client.php?action=add" class="btn btn-default">Add New Record</a>
        </div>

    </div>

<?php
if (isset($_GET['action']) && !empty($_GET['action'])) {

    if (isset($_GET['id']) && intval($_GET['id'])) {
        //clean the client id
        $clientID = intval($_GET['id']);
    } else {
        $clientID = 1;
    }


    $action = htmlspecialchars($_GET['action']);

    //choose the right action
    switch ($action) {
        case 'add':
            addClient();
            break;
        case 'edit':
            editClient($clientID);
            break;
        case 'del':
            delClient($clientID);
            break;
        case 'confirmDel':
            delConfirm($clientID);
            break;
        default:
            viewClientList();
            break;
    }
} else {
    viewClientList();
}


function delConfirm($id)
{
    global $dataClass;
    $sql = "DELETE FROM client WHERE client_id = " . $id;
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults();
    if ($res) {
        ?>
        <div class="bg-success text-center">
            <h2>Record success removed!</h2>
            <a href="client.php" class="btn btn-default">Back to List</a>
        </div>
        <?php
    } else {
        ?>
        <div class="bg-warning text-center">
            <h2>Record Failed to delete!!</h2>
            <a href="client.php" class="btn btn-default">Back to List</a>
        </div>
        <?php
    }
}

function delClient($id)
{
    global $dataClass;
    $sql = "SELECT * FROM client WHERE client_id = " . $id;
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults('ARRAY');
    if (sizeof($res) == 1) {
        ?>
        <div class="alert-warning text-center">
            <h3>You Sure You Want to Delete</h3>
            <h5><?= $res[0]['CLIENT_STREET'] . $res[0]['CLIENT_SUBURB'] ?></h5>
            <a href="?action=confirmDel&id=<?= $id ?>" class="btn btn-danger">Confirm</a>
            <a href="client.php" class="btn btn-default">Cancel</a>
        </div>
        <?php
    }

}

function viewClientList()
{
    global $dataClass;
    $sql = '
    SELECT 
     * 
    FROM CLIENT 
    ORDER BY client_id';
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults('ARRAY');
    if (sizeof($res)) { ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $dataClass->generateTableFromArray($res, 'CLIENT_ID');
                ?>
            </div>
        </div>
    <?php } else {
        ?>
        <a href="?action=add">
            <button>Add A New Listing to Start</button>
        </a>

        <?php
    }
}

function editClient($id)
{
    if (!empty($_POST)) {
        // prepare the Query for adding the client
        //var_dump($_POST);

        global $dataClass;

        $sql = "UPDATE client SET 
                client_street = :pp_street, 
                client_suburb = :pp_suburb,
                client_state = :pp_state,
                client_pc = :pp_pc,
                client_type = :pp_type
                WHERE client_id = :pp_id
                ";

        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_street', $_POST['client_street'], 100);
        $dataClass->bind(':pp_suburb', $_POST['client_suburb'], 50);
        $dataClass->bind(':pp_state', $_POST['client_state'], 5);
        $dataClass->bind(':pp_pc', $_POST['client_pc'], 6);
        $dataClass->bind(':pp_type', $_POST['type'], 100);
        $dataClass->bind(':pp_id', $id, 30);
        $res = $dataClass->getResults();
        if ($res) {
            ?>
            <div class="bg-success text-center">
                <h2>Record success updated!</h2>
                <a href="client.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        } else {
            ?>
            <div class="bg-warning text-center">
                <h2>Record Failed to updated!!</h2>
                <a href="client.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        }
    } else {

        global $dataClass;
        $sql = "SELECT * FROM client WHERE client_id = :pp_id";
        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_id', $id, 30);
        $res = $dataClass->getResults('ARRAY');
        IF (sizeof($res) == 1):
            ?>

            <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>?action=edit&id=<?= $id ?>">
                <?php
                $arrs = [
                    'familyname' => 50,
                    'givenname' => 50,
                    'street' => 100,
                    'suburb' => 50,
                    'state' => 50,
                    'pc' => 4,
                    'email' => 50,
                    'mobile' => 12
                ];
                foreach ($arrs as $key => $value) {
                    $upperKey = "CLIENT_" . strtoupper($key);
                    ?>
                    <div class="form-group">
                        <label for="client_<?= trim($key) ?>">Client <?= strtoupper($key) ?></label>
                        <input
                            type="text"
                            class="form-control"
                            name="client_<?= trim($key) ?>"
                            maxlength="<?= $value ?>>"
                            value="<?= $res[0][$upperKey] ?>"
                            required>
                    </div>

                    <?php
                }
                ?>

                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               name="client_mailinglist"
                               value="1"
                            <?php if ($res[0]['CLIENT_MAILINGLIST']) {
                                echo "checked";
                            } ?>
                        > Mailing List?
                    </label>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
                <a href="client.php" class="btn btn-default">Cancel</a>
            </form>

            <?php
        else:
            echo "Wrong ID, please check and try again";
        endif; // end of size check
    } // end of if condition

}

function addClient()
{
    if (!empty($_POST)) {
        // prepare the Query for adding the client
        var_dump($_POST);

        global $dataClass;

        $sql = "INSERT INTO 
                client (
                CLIENT_ID, 
                CLIENT_FAMILYNAME,
                CLIENT_GIVENNAME,
                CLIENT_STREET,
                CLIENT_SUBURB,
                CLIENT_STATE,
                CLIENT_PC,
                CLIENT_EMAIL,
                CLIENT_MOBILE,
                CLIENT_MAILINGLIST
                )
                VALUES (
                CLIENT_auto_incr.nextval,
                :pp_familyname,
                :PP_givenname,
                :pp_street, 
                :pp_suburb, 
                :pp_state, 
                :pp_pc, 
                :pp_email, 
                :pp_mobile,
                :pp_mailinglist
                )
                ";

        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_familyname', $_POST['client_familyname'], 50);
        $dataClass->bind(':pp_givenname', $_POST['client_givenname'], 100);
        $dataClass->bind(':pp_street', $_POST['client_street'], 100);
        $dataClass->bind(':pp_suburb', $_POST['client_suburb'], 50);
        $dataClass->bind(':pp_state', $_POST['client_state'], 5);
        $dataClass->bind(':pp_pc', $_POST['client_pc'], 6);
        $dataClass->bind(':pp_email', $_POST['client_email'], 50);
        $dataClass->bind(':pp_mobile', $_POST['client_mobile'], 50);
        if (!isset($_POST['client_mailinglist'])) {
            $dataClass->bind(':pp_mailinglist', 0, 1);
        } else {
            $dataClass->bind(':pp_mailinglist', 1, 1);
        }

        $res = $dataClass->getResults();
        if ($res) {
            ?>
            <div class="bg-success text-center">
                <h2>Record success inserted!</h2>
                <a href="client.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        } else {
            ?>
            <div class="bg-warning text-center">
                <h2>Record Failed to insert!</h2>
                <a href="client.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        }
    } else {
        ?>


        <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>?action=add">
            <?php

            $arrs = [
                'familyname' => 50,
                'givenname' => 50,
                'street' => 100,
                'suburb' => 50,
                'state' => 50,
                'pc' => 4,
                'email' => 50,
                'mobile' => 12
            ];
            foreach ($arrs as $key => $value) {
                ?>
                <div class="form-group">
                    <label for="client_<?= trim($key) ?>">Client <?= strtoupper($key) ?></label>
                    <input
                        type="text"
                        class="form-control"
                        name="client_<?= trim($key) ?>"
                        maxlength="<?= $value ?>>"
                        required>
                </div>

                <?php
            }
            ?>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="client_mailinglist" value="1"> Mailing List?
                </label>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <a href="client.php" class="btn btn-default">Cancel</a>
        </form>

        <?php
    } // end of if condition
}


include_once('./template/footer.php'); ?>