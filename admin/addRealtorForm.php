<?php
include 'top.php';

$realtorId = (isset($_GET['rid'])) ? htmlspecialchars($_GET['rid']) : "";

$sql = 'SELECT pmkNetId, fldFirstName, fldLastName, fldRealtorEmail, fldPhoneNumber, ';
$sql .= 'fldProfile, fldIsActive ';
$sql .= 'FROM tblRealtor ';
$sql .= 'WHERE pmkNetId = ?';
$sql .= 'ORDER BY pmkNetId';

$data = array($realtorId);
$realtors = $thisDatabaseReader->select($sql, $data);
print $realtorId;

if (is_array($realtors) && $realtorId != "") {
    $realtor = $realtors[0];
}
else {
    $realtor = NULL;
}
print $realtor['fldFirstName'];

// intitialize default form values (set to existing values if updating)
if (!is_null($realtor)) {
    $firstName = $realtor['fldFirstName'];
    $lastName = $realtor['fldLastName'];
    $realtorEmail = $realtor['fldRealtorEmail'];
    $phoneNumber = $realtor['fldPhoneNumber'];
    $profile = $realtor['fldProfile'];
    $isActive = $realtor['fldIsActive'];
}
else {
    $realtorId = "";
    $firstName = "";
    $lastName = "";
    $realtorEmail = "";
    $phoneNumber = "";
    $profile = "";
    $isActive = 1;
}

// set save data to true
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
    if(DEBUG) {
        print '<p>POST array:<p><pre>';
        print_r($_POST);
        print '</pre>';
    }

    //sanitize data
    $realtorId = filter_var($_POST['txtRealtorId'], FILTER_SANITIZE_STRING);
    $firstName = filter_var($_POST['txtFirstName'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['txtLastName'], FILTER_SANITIZE_STRING);
    $realtorEmail = filter_var($_POST['txtRealtorEmail'], FILTER_SANITIZE_EMAIL);
    $phoneNumber = filter_var($_POST['txtPhoneNumber'], FILTER_SANITIZE_STRING);
    $profile = filter_var($_POST['txtProfile'], FILTER_SANITIZE_STRING);
    // TODO: santize this properly
    $isActive = (int) getData('chkIsActive');
    
    
    // validate data
    if (strlen($realtorId) > 10) {
        print '<p class="mistake">Please enter a valid realtor id (10 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($firstName) > 25) {
        print '<p class="mistake">Please enter a valid first name (25 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($lastName) > 30) {
        print '<p class="mistake">Please enter a valid last name (30 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($realtorEmail) > 75) {
        print '<p class="mistake">Please enter a valid realtor email (75 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($phoneNumber) > 15) {
        print '<p class="mistake">Please enter a valid phone number (75 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($profile) > 3000) {
        print '<p class="mistake">Please enter a valid profile (3000 characters or less).</p>';
        $saveData = false;
    }
    if ($isActive != 1) {
        $isActive = 0;
    }

    if ($houseId < 0) {
        print '<p class="mistake">House ID must be greater than 0.</p>';
        $saveData = false;
    }

    if ($saveData) {
        // insert record (or update if key already exists)
        $sql = 'INSERT INTO tblRealtor SET ';
        $sql .= 'pmkNetId = ?, ';
        $sql .= 'fldFirstName = ?, ';
        $sql .= 'fldLastName = ?, ';
        $sql .= 'fldRealtorEmail = ?, ';
        $sql .= 'fldPhoneNumber = ?, ';
        $sql .= 'fldProfile = ?, ';
        $sql .= 'fldIsActive = ? ';
        $sql .= 'ON DUPLICATE KEY UPDATE ';
        $sql .= 'fldFirstName = ?, ';
        $sql .= 'fldLastName = ?, ';
        $sql .= 'fldRealtorEmail = ?, ';
        $sql .= 'fldPhoneNumber = ?, ';
        $sql .= 'fldProfile = ?, ';
        $sql .= 'fldIsActive = ?';


        $data = array();
        $data[] = $realtorId;
        $data[] = $firstName;
        $data[] = $lastName;
        $data[] = $realtorEmail;
        $data[] = $phoneNumber;
        $data[] = $profile;
        $data[] = $isActive;
        $data[] = $firstName;
        $data[] = $lastName;
        $data[] = $realtorEmail;
        $data[] = $phoneNumber;
        $data[] = $profile;
        $data[] = $isActive;
            
        # insert/update
        $realtorTableSuccess = $thisDatabaseWriter->insert($sql, $data);
        
        if ($realtorTableSuccess) {
            print '<h2 class="success-message">Realtor database successfully updated!</h2>';
        }
        else {
            print '<p class="error-message">Something went wrong, your change was not submitted properly.</p>';
        }
    }
}
?>
<main>
    <form action="<?php print PHP_SELF; ?>" id="addRealtorForm" method="post">
        <p>
            <label for="txtRealtorId">Realtor Net ID</label>
            <input type="text" value="<?php print $realtorId; ?>" name="txtRealtorId" id="txtRealtorId">
        </p>
        <p>
            <label for="txtFirstName">First Name</label>
            <input type="text" value="<?php print $firstName; ?>" name="txtFirstName" id="txtFirstName">
        </p>
        <p>
            <label for="txtLastName">Last Name</label>
            <input type="text" value="<?php print $lastName; ?>" name="txtLastName" id="txtLastName">
        </p>
        <p>
            <label for="txtRealtorEmail">Realtor Email</label>
            <input type="text" value="<?php print $realtorEmail; ?>" name="txtRealtorEmail" id="txtRealtorEmail">
        </p>
        <p>
            <label for="txtPhoneNumber">Phone Number</label>
            <input type="text" value="<?php print $phoneNumber; ?>" name="txtPhoneNumber" id="txtPhoneNumber">
        </p>
        <p class="formInput">
            <label for="txtProfile">Realtor Profile</label>
            <?php
            print '<textarea id="txtProfile" name="txtProfile" rows="6" cols="50">' . $profile . '</textarea>';
            ?>
        </p> 
        <p>
            <label for="chkIsActive">Is Employee Active?</label>
            <?php 
            if ($isActive == 1) {
                print '<input type="checkbox" name="chkIsActive" id="chkIsActive" value="' . $isActive . '" checked>';
            }
            else {
                print '<input type="checkbox" name="chkIsActive" id="chkIsActive" value ="' . $isActive . '">';
            }
            ?>
        </p>
        <fieldset>
            <p><input type="submit" value="Insert Record" tabindex="999" name="btnSubmit"></p>
        </fieldset>
    </form>
</main>

<?php
    include "footer.php";
?>