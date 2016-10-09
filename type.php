<?php
include_once('./template/header.php');
//load navbar

include_once('./template/navbar.php');

?>
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>Type List</h3>
        </div>
        <div class="col-md-12">
            <a href="type.php?action=add" class="btn btn-default">Add New Record</a>
        </div>

    </div>

<?php
if (isset($_GET['action']) && !empty($_GET['action'])) {

    if (isset($_GET['id']) && intval($_GET['id'])) {
        //clean the type id
        $typeID = intval($_GET['id']);
    } else {
        $typeID = 1;
    }


    $action = htmlspecialchars($_GET['action']);

    //choose the right action
    switch ($action) {
        case 'add':
            addType();
            break;
        case 'edit':
            editType($typeID);
            break;
        case 'del':
            delType($typeID);
            break;
        case 'confirmDel':
            delConfirm($typeID);
            break;
        default:
            viewTypeList();
            break;
    }
} else {
    viewTypeList();
}

function delConfirm($id){
    global $dataClass;
    $sql = "DELETE FROM type WHERE type_id = ".$id;
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults();
    if($res){
        ?>
        <div class="bg-success text-center">
            <h2>Record success removed!</h2>
            <a href="type.php" class="btn btn-default">Back to List</a>
        </div>
        <?php
    }else{
      ?>
      <div class="bg-warning text-center">
          <h2>Record Failed to delete!!</h2>
          <a href="type.php" class="btn btn-default">Back to List</a>
      </div>
        <?php
    }
}

function delType($id){
    global $dataClass;
    $sql = "SELECT * FROM type WHERE type_id = ".$id;
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults('ARRAY');
    if(sizeof($res) == 1){
        ?>
            <div class="alert-warning text-center">
                <h3>You Sure You Want to Delete</h3>
                <h5><?= $res[0]['TYPE_NAME'] ?></h5>
                <a href="?action=confirmDel&id=<?= $id ?>" class="btn btn-danger">Confirm</a>
                <a href="type.php" class="btn btn-default">Cancel</a>
            </div>
        <?php
    }

}

function viewTypeList()
{
    global $dataClass;
    $sql = '
    SELECT * 
    FROM type 
    ORDER BY type_id';
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults('ARRAY');
    if (sizeof($res)) { ?>
        <div class="row">

            <div class="col-md-12">
                <?php
                $dataClass->generateTableFromArray($res, 'TYPE_ID');
                ?>

            </div>
        </div>
    <?php } else {
        ?>
        <a href="type.php">
            <button>Please Add A New Listing to Start</button>
        </a>
        <?php
    }
}

function editType($id)
{
    if (!empty($_POST)) {
        // prepare the Query for adding the type
        //var_dump($_POST);

        global $dataClass;

        $sql = "UPDATE type SET type_name = :pt_name WHERE type_id = :pt_id
                ";

        $dataClass->setQuery($sql);
        $dataClass->bind(':pt_name', $_POST['type_name'], 30);
        $dataClass->bind(':pt_id', $id, 30);
        $res = $dataClass->getResults();
        if ($res) {
           ?>
            <div class="bg-success text-center">
                <h2>Record success updated!</h2>
                <a href="type.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        } else {
            ?>
            <div class="bg-warning text-center">
                <h2>Record Failed to updated!!</h2>
                <a href="type.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        }
    } else {

        global $dataClass;
        $sql = "SELECT * FROM type WHERE type_id = :pp_id";
        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_id', $id, 30);
        $res = $dataClass->getResults('ARRAY');
        IF (sizeof($res) == 1):
            ?>

            <form method="post" action="?action=edit&id=<?= $id ?>">
                <div class="form-group">
                    <label for="type_id">Type ID</label>
                    <input disabled type="text" class="form-control" name="type_id" id="type_id"
                           value="<?= $res[0]['TYPE_ID'] ?>">
                </div>
                <div class="form-group">
                    <label for="type_street">Type Name</label>
                    <input type="text" class="form-control" name="type_name" maxlength="30"
                           value="<?= trim($res[0]['TYPE_NAME']) ?>">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
                <a href="type.php" class="btn btn-default">Cancel</a>
            </form>

            <?php
        else:
            echo "Wrong ID, please check and try again";
        endif; // end of size check
    } // end of if condition

}

function addType()
{
    if (!empty($_POST)) {
        // prepare the Query for adding the type
        //var_dump($_POST);

        global $dataClass;

        $sql = "INSERT INTO 
                type (TYPE_ID, TYPE_NAME)
                VALUES (TYPE_auto_incr.nextval, :pp_name)
                ";

        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_name', $_POST['type_name'], 30);
        $res = $dataClass->getResults();
        if ($res) {
            ?>
            <div class="bg-success text-center">
                <h2>Record success inserted!</h2>
                <a href="type.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        } else {
            ?>
            <div class="bg-warning text-center">
                <h2>Record Failed to insert!</h2>
                <a href="type.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        }
    } else {
        ?>


        <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>?action=add">
            <div class="form-group">
                <label for="type_street">Type Name</label>
                <input type="text" class="form-control" name="type_name" maxlength="30">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <a href="type.php" class="btn btn-default">Cancel</a>
        </form>

        <?php
    } // end of if condition
}


include_once('./template/footer.php'); ?>