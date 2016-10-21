<?php

include_once('./template/header.php');

//load navbar
include_once('./template/navbar.php');

// start the database part
global $dataClass;


?>


    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h3>Property Search</h3>
            </div>
        </div>
        <div class="col-md-12">
            <form class="form-horizontal" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <fieldset>
                    <!-- Text input-->
                    <div class="form-group">
                        <label for="property_suburb">Suburb</label>
                        <input id="s_suburb" name="property_suburb" type="text" placeholder=""
                               class="form-control input-md">
                    </div>

                    <div class="form-group">
                        <label for="type_name">Property Type</label>
                        <input id="type_name" name="type_name" type="text" placeholder="" class="form-control input-md">
                    </div>

                    <!-- Multiple Checkboxes -->
                    <div class="form-group">
                        <label for="s_features">Property Features</label>
                        <?php
                        $dataClass->generateChoiceList('FEATURE');
                        ?>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <label for="submit"></label>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>

                </fieldset>
            </form>
        </div>
    </div>

<?php
if (isset($_GET)) {

    displaySearchResult($_GET);

}
?>

<?php

function displaySearchResult($get)
{
    global $dataClass;
    $sql = "
        SELECT * 
        FROM PROPERTY
        JOIN type 
        ON property.property_type = type.type_id
        ";
    $whereClause = [];
    $featureClaus = [];
    $whereSearch = '';
    $featureSearch = '';
    foreach ($get as $key => $value) {
        if ($value && $key != 's_feature') {
            if($key == 'type_name'){
                $whereClause[] = "TYPE.".$key . " LIKE '%" . strtolower(trim($value)) . "%'";
            }elseif($key == 'property_suburb'){
                $whereClause[] = "PR.".$key . " LIKE '%" . strtolower(trim($value)) . "%'";
            }

        } else {
            $featureClaus[] = "'{$value}'";
        }

    }
    var_dump($whereClause);
    if (count($whereClause) > 0 && count($featureClaus) == 0) {
        $whereSearch .= " WHERE " . implode(' AND ', $whereClause);
        $sql = $sql . $whereSearch;
        $dataClass->setQuery($sql);
        $res = $dataClass->getResults("ARRAY");
        $dataClass->freeResults();
    } elseif (count($whereClause) > 0 && count($featureClaus) > 0) {
        // if the size of featureClaus is greater than 1, then we can search it
        if (sizeof($featureClaus) > 2) {
            $featureSearch = implode(' , ', $featureClaus);
        } else {
            $featureSearch = $featureClaus[0];
        }
        $whereSearch .= " WHERE 1=1 AND " . implode(' AND ', $whereClause);
        $sql = "
            SELECT SS.PROPERTY_ID, SS.RES, PR.PROPERTY_SUBURB , TYPE.TYPE_NAME FROM(
                  SELECT
                  PROPERTY_ID, FEATURE_ID,COUNT(*) OVER (PARTITION BY PROPERTY_ID) AS RES FROM FEATURE
                  WHERE FEATURE_NAME IN ({$featureSearch})
                  GROUP BY PROPERTY_ID, FEATURE_ID
                  ) SS
            JOIN PROPERTY PR ON SS.PROPERTY_ID = PR.PROPERTY_ID
            JOIN TYPE ON TYPE.TYPE_ID = PR.PROPERTY_TYPE
            {$whereSearch}
            GROUP BY SS.PROPERTY_ID, SS.RES, PR.PROPERTY_SUBURB, TYPE.TYPE_NAME;
            ";
        var_dump($sql);
        $dataClass->setQuery($sql);
        $res = $dataClass->getResults("ARRAY");
        $dataClass->freeResults();
    }

    var_dump($whereSearch);


    var_dump($res);
}



