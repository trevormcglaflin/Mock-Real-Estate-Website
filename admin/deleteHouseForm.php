<?php
include 'top.php';

// if user is not the admin, prevent access
//if (!($isAdmin)) {
    //$message = "This page does not exist, please go away!";
    //die($message);
//}

$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;

$sql = 'SELECT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldDateListed, fldNickName, fldImageUrl ';
$sql .= 'FROM tblHouse ';
$sql .= 'WHERE pmkHouseId = ? ';
$sql .= 'ORDER BY pmkHouseId'; 

$data = array($houseId);
$houses = $thisDatabaseReader->select($sql, $data);

if (is_array($houses) && $houseId != 0) {
    $house = $houses[0];
}
else {
    $house = NULL;
}

// initialize save data to true
$saveData = true;

// TODO: make this simplier
// figure out how many records are in table
$sqlNumRecords = 'SELECT COUNT(*) FROM tblHouse';
$numRecords = $thisDatabaseReader->select($sqlNumRecords);
$numRecords = $numRecords[0]['COUNT(*)'];

function getData($field) {
    if (!isset($_POST[$field])) {
       $data = "";
    }
    else {
       $data = trim($_POST[$field]);
       $data = htmlspecialchars($data);
    }
    return $data;
}

if(isset($_POST['btnSubmit'])) {
    // sanitize
    $houseId = (int) getData('hdnHouseId');

    // validate
    if ($houseId >= $numRecords) {
        print '<p class="mistake">Invalid house id. Id does not exist in table!</p>';
        $saveData = false;
    }

    // delete record if field is valid
    if ($saveData) {
        $sql = 'DELETE FROM tblHouse ';
        $sql .= 'WHERE pmkHouseId = ?';

        $data = array();
        $data[] = $houseId;

        # delete record
        $houseTableSuccess = $thisDatabaseWriter->delete($sql, $data);
    }

    // display message
    if ($houseTableSuccess) {
        print '<h2 class="success-message">Record has been deleted!</h2>';
    }
    else {
        print '<p class="error-message">Something went wrong, record was not deleted from database.</p>';
    }
}
?>
<main>
    <form action="<?php print PHP_SELF; ?>" id="deleteHouseForm" method="post">
         <?php print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; ?>
        <fieldset>
            <p><input type="submit" value="Delete Record" tabindex="999" name="btnSubmit"></p>
        </fieldset>
    </form>

<?php
// show the information about the animal
if(is_array($houses)) {
    foreach($houses as $house) {
        print '<p>' . $house['fldNickName'] . '</p>';
        print '<figure><img src=../images/' . $house['fldImageUrl'] . ' alt=housePic></figure>';
        print nl2br($house['fldDescription']);
        print '<h3><b>Price</b></h3>';
        print $house['fldPrice'];
        print '<h3><b>Address</b></h3>';
        print $house['fldAddress'];
        print '<h3><b>District</b></h3>';
        print $house['fldDistrict'];
        print '<h3><b>Square Feet</b></h3>';
        print $house['fldSquareFeet'];
        print '<h3><b>Date Listed</b></h3>';
        print $house['fldDateListed'];
        print '<h3><b>Image Url</b></h3>';
        print $house['fldImageUrl'];
        
    }
}
?>
</main>

<?php
    include "footer.php";
?>