<?php
include_once('./template/header.php');
//load navbar

include_once('./template/navbar.php');

?>
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>Property List</h3>
        </div>
        <div class="col-md-12">
            <a href="property.php?action=add" class="btn btn-default">Add New Record</a>
        </div>

    </div>

<?php
if (isset($_GET['action']) && !empty($_GET['action'])) {

    if (isset($_GET['id']) && intval($_GET['id'])) {
        //clean the property id
        $propertyID = intval($_GET['id']);
    } else {
        $propertyID = 1;
    }


    $action = htmlspecialchars($_GET['action']);

    //choose the right action
    switch ($action) {
        case 'add':
            addProperty();
            break;
        case 'edit':
            editProperty($propertyID);
            break;
        case 'del':
            delProperty($propertyID);
            break;
        case 'confirmDel':
            delConfirm($propertyID);
            break;
        default:
            viewPropertyList();
            break;
    }
} else {
    viewPropertyList();
}

function delConfirm($id){
    global $dataClass;
    $sql = "DELETE FROM property WHERE property_id = ".$id;
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults();
    if($res){
        ?>
        <div class="bg-success text-center">
            <h2>Record success removed!</h2>
            <a href="property.php" class="btn btn-default">Back to List</a>
        </div>
        <?php
    }else{
      ?>
      <div class="bg-warning text-center">
          <h2>Record Failed to delete!!</h2>
          <a href="property.php" class="btn btn-default">Back to List</a>
      </div>
        <?php
    }
}

function delProperty($id){
    global $dataClass;
    $sql = "SELECT * FROM property WHERE property_id = ".$id;
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults('ARRAY');
    if(sizeof($res) == 1){
        ?>
            <div class="alert-warning text-center">
                <h3>You Sure You Want to Delete</h3>
                <h5><?= $res[0]['PROPERTY_STREET'].$res[0]['PROPERTY_SUBURB'] ?></h5>
                <a href="?action=confirmDel&id=<?= $id ?>" class="btn btn-danger">Confirm</a>
                <a href="property.php" class="btn btn-default">Cancel</a>
            </div>
        <?php
    }

}

function viewPropertyList()
{
    global $dataClass;
    $sql = '
    SELECT 
      property.property_id, 
      property.property_street, 
      property.property_suburb, 
      property.property_state,
      property.property_pc, 
      type.type_name
    FROM PROPERTY 
    JOIN type 
    ON property.property_type = type.type_id
    ORDER BY property_id';
    $dataClass->setQuery($sql);
    $res = $dataClass->getResults('ARRAY');
    if (sizeof($res)) { ?>
        <div class="row">

            <div class="col-md-12">
                <?php
                $dataClass->generateTableFromArray($res, 'PROPERTY_ID');
                ?>

            </div>
        </div>
    <?php } else {
        ?>
        <a href="property-edit.php">
            <button>Add A New Listing</button>
        </a>

        <?php
    }
}

function editProperty($id)
{
    if (!empty($_POST)) {
        // prepare the Query for adding the property
        //var_dump($_POST);

        global $dataClass;

        $sql = "UPDATE property SET 
                property_street = :pp_street, 
                property_suburb = :pp_suburb,
                property_state = :pp_state,
                property_pc = :pp_pc,
                property_type = :pp_type
                WHERE property_id = :pp_id
                ";

        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_street', $_POST['property_street'], 100);
        $dataClass->bind(':pp_suburb', $_POST['property_suburb'], 50);
        $dataClass->bind(':pp_state', $_POST['property_state'], 5);
        $dataClass->bind(':pp_pc', $_POST['property_pc'], 6);
        $dataClass->bind(':pp_type', $_POST['type'], 100);
        $dataClass->bind(':pp_id', $id, 30);
        $res = $dataClass->getResults();
        if ($res) {
           ?>
            <div class="bg-success text-center">
                <h2>Record success updated!</h2>
                <a href="property.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        } else {
            ?>
            <div class="bg-warning text-center">
                <h2>Record Failed to updated!!</h2>
                <a href="property.php" class="btn btn-default">Back to List</a>
            </div>
            <?php
        }
    } else {

        global $dataClass;
        $sql = "SELECT * FROM property WHERE property_id = :pp_id";
        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_id', $id, 30);
        $res = $dataClass->getResults('ARRAY');
        IF (sizeof($res) == 1):
            ?>

            <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>?action=edit&id=<?= $id ?>">
                <div class="form-group">
                    <label for="property_id">Property ID</label>
                    <input disabled type="text" class="form-control" name="property_id" id="property_id"
                           value="<?= $res[0]['PROPERTY_ID'] ?>"
                    required>
                </div>
                <div class="form-group">
                    <label for="property_street">Property Street</label>
                    <input type="text" class="form-control" name="property_street" maxlength="100"
                           value="<?= trim($res[0]['PROPERTY_STREET']) ?>"
                    required>
                </div>
                <div class="form-group">
                    <label for="property_suburb">Property Suburb</label>
                    <input type="text" class="form-control" name="property_suburb" maxlength="50"
                           value="<?= trim($res[0]['PROPERTY_SUBURB']) ?>"
                    required>
                </div>
                <div class="form-group">
                    <label for="property_state">Property State</label>
                    <input type="text" class="form-control" name="property_state" maxlength="5"
                           value="<?= trim($res[0]['PROPERTY_STATE']) ?>"
                    required>
                </div>
                <div class="form-group">
                    <label for="property_pc">Property Post Code</label>
                    <input type="number" class="form-control" name="property_pc" maxlength="6"
                           value="<?= trim($res[0]['PROPERTY_PC']) ?>"
                    required>
                </div>
                <div class="form-group">
                    <?php global $dataClass;
                    $dataClass->generateOptionList('type', $res[0]['PROPERTY_TYPE']); ?>
                </div>
                <div class="form-group">
                    <label for="property_photo">Photo Upload</label>
                    <input type="file" id="property_photo">
                    <p class="help-block">Only jpg, png,bmp are allowed!</p>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
                <a href="property.php" class="btn btn-default">Cancel</a>
            </form>

            <?php
        else:
            echo "Wrong ID, please check and try again";
        endif; // end of size check
    } // end of if condition

}

function addProperty()
{
    if (!empty($_POST)) {
        // prepare the Query for adding the property
        //var_dump($_POST);

        global $dataClass;

        $sql = "INSERT INTO 
                property (PROPERTY_ID, PROPERTY_STREET,PROPERTY_SUBURB,PROPERTY_STATE,PROPERTY_PC,PROPERTY_TYPE)
                VALUES (PROPERTY_auto_incr.nextval, :pp_street, :pp_suburb, :pp_state, :pp_pc, :pp_type)
                ";

        $dataClass->setQuery($sql);
        $dataClass->bind(':pp_street', $_POST['property_street'], 100);
        $dataClass->bind(':pp_suburb', $_POST['property_suburb'], 50);
        $dataClass->bind(':pp_state', $_POST['property_state'], 5);
        $dataClass->bind(':pp_pc', $_POST['property_pc'], 6);
        $dataClass->bind(':pp_type', $_POST['type'], 100);
        $res = $dataClass->getResults();
        if ($res) {
            echo "Successful insert data";
        } else {
            echo "Something wrong with the data, please check and try again.";
        }
    } else {
        ?>


        <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>?action=add">
            <!--            <div class="form-group">-->
            <!--                <label for="property_id">Property ID</label>-->
            <!--                <input type="text" class="form-control" name="property_id" id="property_id">-->
            <!--            </div>-->
            <div class="form-group">
                <label for="property_street">Property Street</label>
                <input type="text" class="form-control" name="property_street" maxlength="100">
            </div>
            <div class="form-group">
                <label for="property_suburb">Property Suburb</label>
                <input type="text" class="form-control" name="property_suburb" maxlength="50">
            </div>
            <div class="form-group">
                <label for="property_state">Property State</label>
                <input type="text" class="form-control" name="property_state" maxlength="5">
            </div>
            <div class="form-group">
                <label for="property_pc">Property Post Code</label>
                <input type="number" class="form-control" name="property_pc" maxlength="6">
            </div>
            <div class="form-group">
                <?php global $dataClass;
                $dataClass->generateOptionList('type'); ?>
            </div>
            <div class="form-group">
                <label for="property_photo">Photo Upload</label>
                <input type="file" id="property_photo">
                <p class="help-block">Only jpg, png,bmp are allowed!</p>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <a href="property.php" class="btn btn-default">Cancel</a>
        </form>

        <?php
    } // end of if condition
}


include_once('./template/footer.php'); ?>