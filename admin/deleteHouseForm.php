<?php
include 'top.php';

if ($adminPermissionLevel < 3) {
    $message = "You do not have permission to this page!";
    die($message);
}

$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;

// if a house has been sold, you can not delete it from database
$sql = 'SELECT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldDateListed, fldNickName, fldImageUrl ';
$sql .= 'FROM tblHouse ';
$sql .= 'LEFT JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'WHERE fpkHouseId IS NULL AND ';
$sql .= 'pmkHouseId = ? ';

$data = array($houseId);
$houses = $thisDatabaseReader->select($sql, $data);

if (sizeof($houses) > 0) {
    $house = $houses[0];
}
else {
    $house = NULL;
    $houseId = 0;
}

// initialize save data to true
$saveData = true;

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
    $imageUrl = filter_var($_POST['hdnImageUrl'], FILTER_SANITIZE_STRING);
    

    // delete record if field is valid
    if ($saveData) {
        // first, delete the HouseRealtor record if one exists for that house
        // NOTE: because of my foreign key constraints, this must get deleted first or else the house can't get deleted
        $sql = 'DELETE FROM tblHouseRealtor ';
        $sql .= 'WHERE fpkHouseId = ?';
        $data = array();
        $data[] = $houseId;
        $houseRealtorTableSuccess = $thisDatabaseWriter->delete($sql, $data);
        
        // now, delete from house table
        $sql = 'DELETE FROM tblHouse ';
        $sql .= 'WHERE pmkHouseId = ?';
        $houseTableSuccess = $thisDatabaseWriter->delete($sql, $data);
    }
    // display message
    if ($houseTableSuccess) {
        $deleteImage = shell_exec('rm ../images/' . $imageUrl);
        print $imageUrl;
        
        print '<h2 class="success-message">House record has been deleted!</h2>';
    }
    else {
        print '<h2 class="error-message">Something went wrong, record was not deleted from database.</h2>';
    }
    if ($houseRealtorTableSuccess) {
        print '<p class="success-message">The realtor assigned to this house was unassigned from it, since it is no longer in database.</p>';
    }
}
?>
<main>
<?php
// only show delete button if the record exists
if($houseId != 0) {
    print '<form action="' .PHP_SELF . '" id="deleteHouseForm" method="post">';
    print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; 
    print '<input type="hidden" id="hdnImageUrl" name="hdnImageUrl" value="' . $house['fldImageUrl'] . '">'; 
    print '<fieldset>';
    print '<p><input type="submit" value="Delete Record" tabindex="999" name="btnSubmit"></p>';
    print '</fieldset>';
    print '</form>';
    
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
else {
    print '<p class = "error-message">We cannot delete the house with that ID.</p>';
    print '<p>This is because of one of these reasons: </p>';
    print '<ol>';
    print '<li>The house never existed in the database</li>';
    print '<li>The house exists in database but has been sold. You cannot delete a house that has been sold.</li>';
}
?>
</main>

<?php
    include "footer.php";
?>