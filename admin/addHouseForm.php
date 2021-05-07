<?php
include 'top.php';

if ($adminPermissionLevel < 2) {
    $message = "You do not have permission to this page!";
    die($message);
}

$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;

$sql = 'SELECT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldNickName, fldImageUrl ';
$sql .= 'FROM tblHouse ';
$sql .= 'WHERE pmkHouseId = ? ';
$sql .= 'ORDER BY fldNickName';

$data = array($houseId);
$houses = $thisDatabaseReader->select($sql, $data);

if (is_array($houses) && $houseId != 0) {
    $house = $houses[0];
}
else {
    $house = NULL;
}

// intitialize default form values (set to existing values if updating)
if (!is_null($house)) {
    $price = $house['fldPrice'];
    $address = $house['fldAddress'];
    $description = $house['fldDescription'];
    $district = $house['fldDistrict'];
    $squareFeet = $house['fldSquareFeet'];
    $nickName = $house['fldNickName'];
    $existingImageUrl = $house['fldImageUrl'];
}
else {
    $price = "";
    $address = "";
    $description = "";
    $district = "";
    $squareFeet = "";
    $nickName = "";
    $existingImageUrl = "";

    // TODO: find a better way to do this
    // set house id by finding the last record's id and adding one
    $sql = 'SELECT pmkHouseId FROM tblHouse';
    $houses = $thisDatabaseReader->select($sql);
    $houseId = end($houses)['pmkHouseId'] + 1;
}

// set save data to true
$saveData = true;
// helps make image sticky
$formProcessed = false;

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
    // if an existing house image doesn't exist then make them upload one
    // if it does exist then uploading a picture should be optional
    $existingPicture = filter_var($_POST['hdnImageUrl'], FILTER_SANITIZE_STRING);
    if (strlen($existingPicture) > 50) {
        print '<p class="mistake">Hidden image url field invalid (must be 50 characters or less).</p>';
        $saveData = false;
    }
    
    // for image submission
    include 'upload.php';
    
    if(DEBUG) {
        print '<p>POST array:<p><pre>';
        print_r($_POST);
        print '</pre>';
    }

    // sanitize data
    $price = (int) getData('txtPrice');
    $address = filter_var($_POST['txtAddress'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['txtDescription'], FILTER_SANITIZE_STRING);
    $district = filter_var($_POST['txtDistrict'], FILTER_SANITIZE_STRING);
    $squareFeet = (int) getData('txtSquareFeet');
    $nickName = filter_var($_POST['txtNickName'], FILTER_SANITIZE_STRING);
    $houseId = (int) getData('hdnHouseId');
    $realtorId = filter_var($_POST['hdnRealtorId'], FILTER_SANITIZE_STRING);
    // if a new file was not submitted and an old one exists then the old one is the "new" one
    if ($fileName == "" && $existingPicture != "") {
        $newImageUrl = filter_var($existingPicture, FILTER_SANITIZE_STRING);
    }
    // otherwise, the one submitted is the new one
    else {
        $newImageUrl = filter_var($fileName, FILTER_SANITIZE_STRING);
    }
    
    // validate data
    // this is based on the file upload
    if ($uploadOk == 0) {
        $saveData = false;
    }
    if ($price < 0 || $price > 250000000) {
        print '<p class="mistake">Price must be between 0 and 250000000.</p>';
        $saveData = false;
    }
    if (strlen($address) > 70) {
        print '<p class="mistake">Please enter a valid address (70 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($description) > 3000) {
        print '<p class="mistake">Please enter a valid description (3000 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($district) > 30) {
        print '<p class="mistake">Please enter a valid district (30 characters or less).</p>';
        $saveData = false;
    }
    if ($squareFeet < 0 || $squareFeet > 100000) {
        print '<p class="mistake">Square feet must be between 0 and 100000.</p>';
        $saveData = false;
    }
    if (strlen($nickName) > 75) {
        print '<p class="mistake">Please enter a valid nick name (75 characters or less).</p>';
        $saveData = false;
    }
    if (strlen($newImageUrl) > 50) {
        print '<p class="mistake">Please enter a valid image url (50 characters or less).</p>';
        $saveData = false;
    }

    if ($houseId < 0) {
        print '<p class="mistake">Invalid house id (must be a positive integer).</p>';
        $saveData = false;
    }

    if (strlen($realtorId) > 10) {
        print '<p class="mistake">Please enter a valid realtor id (10 characters or less).</p>';
        $saveData = false;
    }

    if ($saveData) {
        $newRecord = true;
        
        // try to insert the house
        $sql = 'INSERT INTO tblHouse SET ';
        $sql .= 'pmkHouseId = ?, ';
        $sql .= 'fldPrice = ?, ';
        $sql .= 'fldAddress = ?, ';
        $sql .= 'fldDescription = ?, ';
        $sql .= 'fldDistrict = ?, ';
        $sql .= 'fldSquareFeet = ?, ';
        $sql .= 'fldNickName = ?, ';
        $sql .= 'fldImageUrl = ?, ';
        $sql .= 'fldDateListed = NOW()';

        $data = array();
        $data[] = $houseId;
        $data[] = $price;
        $data[] = $address;
        $data[] = $description;
        $data[] = $district;
        $data[] = $squareFeet;
        $data[] = $nickName;
        $data[] = $newImageUrl;
            
        # insert
        $houseTableSuccess = $thisDatabaseWriter->insert($sql, $data);
        
        // if the insert didn't work, that means the house pk already exists so update instead
        if (!($houseTableSuccess)) {
            $newRecord = false;
            $sql = "UPDATE tblHouse SET ";
            $sql .= 'fldPrice = ?, ';
            $sql .= 'fldAddress = ?, ';
            $sql .= 'fldDescription = ?, ';
            $sql .= 'fldDistrict = ?, ';
            $sql .= 'fldSquareFeet = ?, ';
            $sql .= 'fldNickName = ?, ';
            $sql .= 'fldImageUrl = ? ';
            $sql .= "WHERE ";
            $sql .= "pmkHouseId = ?";

            $data = array();
            $data[] = $price;
            $data[] = $address;
            $data[] = $description;
            $data[] = $district;
            $data[] = $squareFeet;
            $data[] = $nickName;
            $data[] = $newImageUrl;
            $data[] = $houseId;

            # update
            $houseTableSuccess = $thisDatabaseWriter->update($sql, $data);
        }
       
        // if it is a new realtor, assign the house to the realtor that added it
        // NOTE: if the house is being updated, the realtor assigned to it will not change (unless you go to assign realtor page)
        if ($newRecord) {
            $sql = 'INSERT INTO tblHouseRealtor SET ';
            $sql .= 'fpkNetId = ?, ';
            $sql .= 'fpkHouseId = ? ';
            $data = array();
            $data[] = $realtorId;
            $data[] = $houseId;
            $houseRealtorTableSuccess = $thisDatabaseWriter->insert($sql, $data);
        }

        // delete old image if image was changed
        if ($newImageUrl != $existingPicture) {
            $deleteImage = shell_exec('rm ../images/' . $existingPicture);
        }
        
        // display messages accordingly
        print '<section class="form-message">';
        if ($houseTableSuccess && $newRecord) {
            print '<h2 class="success-message">' . $nickName .  ' successfully inserted into database!</h2>';
            $formProcessed = true;
        }
        else if ($houseTableSuccess && !($newRecord)) {
            print '<h2 class="success-message">' . $nickName . ' record has been updated in database!</h2>';
            $formProcessed = true;
        }

        else {
            print '<p class="error-message">Something went wrong, your house table change was not submitted properly.</p>';
            $deleteImage = shell_exec('rm ../images/' . $fileName);
            print '<p class="error-message">Image has been removed from directory.</p>';
        }
        if ($houseRealtorTableSuccess) {
            print '<p class="success-message">' . $realtorId . ' has been assigned to ' . $nickName . '!</p>';
            print '<p>NOTE: to change the realtor assigned to this house go to house assignment tab above</p>';
        }
        print '</section>';
    }
    // if form validation failed remove the image from directory
    else {
        $deleteImage = shell_exec('rm ../images/' . $fileName);
    }
}
?>

<main class="form-page">
    <form action="<?php print PHP_SELF; ?>" id="addHouseForm" method="post" enctype="multipart/form-data">
        <fieldset>
        <p>
            <label for="txtPrice">Price $</label>
            <input type="text" value="<?php print $price; ?>" name="txtPrice" id="txtPrice">
        </p>
        <p>
            <label for="txtAddress">Address</label>
            <input type="text" value="<?php print $address; ?>" name="txtAddress" id="txtAddress">
        </p> 
        <p>
            <label for="txtDistrict">District</label>
            <input type="text" value="<?php print $district; ?>" name="txtDistrict" id="txtDistrict">
        </p>
        <p>
            <label for="txtSquareFeet">Square Feet</label>
            <input type="text" value="<?php print $squareFeet; ?>" name="txtSquareFeet" id="txtSquareFeet">
        </p>  
        <p>
            <label for="txtNickName">Nick Name</label>
            <input type="text" value="<?php print $nickName; ?>" name="txtNickName" id="txtNickName">
        </p>
        </fieldset>
        <fieldset>
        <p class="formInput">
            <label class="text-area-label" for="txtDescription">Description</label>
            <?php
            print '<textarea class="text-area-input" id="txtDescription" name="txtDescription" rows="6" cols="50">' . $description . '</textarea>';
            ?>
        </p>
        </fieldset>
        <fieldset>
        <p>
        <?php 
        if (!$formProcessed) {
            if ($existingImageUrl != "") { 
                print 'NOTE: house already has a picture, so only upload if you want a new one';
                print '<figure><img src=../images/' . $existingImageUrl . ' alt=realtorPic><figure>';
            }
        }
        else {
            print '<figure><img src=../images/' . $newImageUrl . ' alt=realtorPic><figure>';
        }
        ?> 
            Upload Main House Image:
            <input type="file" name="fileToUpload" id="fileToUpload">
        </p> 
        </fieldset>
        <p>
            <?php print '<input type="hidden" id="hdnImageUrl" name="hdnImageUrl" value="' . $existingImageUrl . '">'; ?>
        </p> 
        <?php print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; ?>
        <?php print '<input type="hidden" id="hdnRealtorId" name="hdnRealtorId" value="' . $netId . '">'; ?>
        <fieldset>
            <p><input class="submit-button" type="submit" value="Insert Record" tabindex="999" name="btnSubmit"></p>
        </fieldset>
    </form>
</main>

<?php
    include "footer.php";
?>