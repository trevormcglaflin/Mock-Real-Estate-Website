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
$sql .= 'LEFT JOIN tblBuyerHouse ON pmkHouseId = fpkHouseId ';
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
    $realtorId = filter_var($_POST['dwnRealtorId'], FILTER_SANITIZE_STRING);

    // validate
    if (strlen($realtorId) > 10) {
        print '<p class="mistake">Please enter a valid realtor id (10 characters or less).</p>';
        $saveData = false;
    }

    // check to see if realtor id exists
    $sql = 'SELECT pmkNetId FROM tblRealtor ';
    $sql .= 'WHERE pmkNetId = ?';
    $data = array($realtorId);
    $realtors = $thisDatabaseReader->select($sql, $data);
    if (sizeof($realtors) == 0) {
        print '<p class="mistake">Uh oh, that realtor net id does not exist!.</p>';
        $saveData = false;
    }
    
    if ($houseId < 0) {
        print '<p class="mistake">House ID must be greater than 0.</p>';
        $saveData = false;
    }

    // delete record if field is valid
    if ($saveData) {
        // first, delete the HouseRealtor record that already exists using the house id foreign key
        $sql = 'DELETE FROM tblHouseRealtor ';
        $sql .= 'WHERE fpkHouseId = ?';
        $data = array();
        $data[] = $houseId;
        $houseRealtorTableSuccess1 = $thisDatabaseWriter->delete($sql, $data);
        
        // now, add a new record using the new realtor id and house id
        $sql = 'INSERT INTO tblHouseRealtor SET ';
        $sql .= 'fpkHouseId = ?, ';
        $sql .= 'fpkNetId = ? ';
        $data[] = $realtorId;
        $houseRealtorTableSuccess2 = $thisDatabaseWriter->insert($sql, $data);
    }
    // display message
    if ($houseRealtorTableSuccess1 && $houseRealtorTableSuccess2) {
        print '<h2 class="success-message">House has been assigned to ' . $realtorId .  '</h2>';
    }
    else {
        print '<h2 class="error-message">Something went wrong, house was not assigned to ' . $realtorId. '</h2>';
    }
}
?>
<main>
<?php
// only show form if the house id exists
if($houseId != 0) {
    $sql = 'SELECT fpkNetId FROM tblHouseRealtor WHERE fpkHouseId = ?';
    $data = array($houseId);
    $realtors = $thisDatabaseReader->select($sql, $data);
    print '<p>Currently assigned to: ' . $realtors[0]['fpkNetId'];

    // get this query for the drop down field
    $sql = 'SELECT pmkNetId, fldFirstName, fldLastName FROM tblRealtor';
    $realtors = $thisDatabaseReader->select($sql);
    print '<form action="' .PHP_SELF . '" id="deleteHouseForm" method="post">';
    print '<p>';
    print '<label for="dwnRealtorId">New House Assignee</label>';
    print '<select id="dwnRealtorId" name="dwnRealtorId">';
    foreach ($realtors as $realtor) {
        print '<option value="' . $realtor['pmkNetId'] . '">' . $realtor['pmkNetId'] . '</option>';
    }
    print '</select>';
    print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; 
    print '<fieldset>';
    print '<p><input type="submit" value="Re-assign House" tabindex="999" name="btnSubmit"></p>';
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
    print '<p class = "error-message">We cannot reassign the house with that ID.</p>';
    print '<p>This is because of one of these reasons: </p>';
    print '<ol>';
    print '<li>The house never existed in the database</li>';
    print '<li>The house exists in database but has been sold. You cannot reassign a house that has been sold.</li>';
}
?>
</main>

<?php
    include "footer.php";
?>