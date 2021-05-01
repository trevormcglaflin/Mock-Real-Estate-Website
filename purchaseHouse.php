<?php
include 'top.php';
$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;
?>

<main>

<?php

// get house info
// this sql makes sure only lets houses that are still for sale and are assigned a realtor to be purchased
$sql = 'SELECT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldNickName, fldImageUrl ';
$sql .= 'FROM tblBuyerHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyerHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyerHouse.fpkHouseId IS NULL AND pmkHouseId = ? ';

$data = array($houseId);
$houses = $thisDatabaseReader->select($sql, $data);

if (sizeof($houses) > 0) {
    $house = $houses[0];
}
else {
    $house = NULL;
    $houseId = 0;
}

// initialize default form values
$email = "email@email.com";
$firstName = "Trevor";
$lastName = "McGlaflin";
$phoneNumber = "802-xxx-xxxx";
$message = "Cool House!";
$intentToBuy = 1;

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

function validateName($name) {
    $name = str_replace(' ', '', $name);
    return ctype_alpha($name);
}

if ($houseId != 0) {
    print '<h2>Purchase ' . $house['fldNickName'] . '</h2>';
}

if(isset($_POST['btnSubmit'])) {
    if(DEBUG) {
        print '<p>POST array:<p><pre>';
        print_r($_POST);
        print '</pre>';
    }

    //sanitize data
    $email = filter_var($_POST['txtEmail'], FILTER_SANITIZE_EMAIL);
    $firstName = filter_var($_POST['txtFirstName'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['txtLastName'], FILTER_SANITIZE_STRING);
    $phoneNumber = filter_var($_POST['txtPhoneNumber'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['txtMessage'], FILTER_SANITIZE_STRING);
    
    // TODO: sanitize this, for some reason the getData function doesn't work
    $intentToBuy = $_POST['chkIntentToBuy'];
    $houseId = (int) getData('hdnHouseId');

    //validate data
    if (!validateName($firstName)) {
        print '<p class="mistake">Please enter a valid first name.</p>';
        $saveData = false;
    }

    if (!validateName($lastName)) {
        print '<p class="mistake">Please enter a valid last name.</p>';
        $saveData = false;
    }

    if (strlen($phoneNumber) > 20) {
        print '<p class="mistake">Please enter a valid phone number (20 characters or less).</p>';
        $saveData = false;
    }

    if (strlen($message) > 3000) {
        print '<p class="mistake">Please enter a valid message (3000 characters or less).</p>';
        $saveData = false;
    }

    if ($intentToBuy != 0 && $intentToBuy != 1) {
        print '<p class="mistake">Intention to buy value is invalid.</p>';
        $saveData = false;
    }

    if ($houseId < 0) {
        print '<p class="mistake">Hidden house id value is invalid.</p>';
        $saveData = false;
    }
    
    if ($saveData) {
        // table BuyerHouse
        // only insert into buyerHouse if intentToBuy is true
        if ($intentToBuy == 1) {
            $sql = 'INSERT INTO tblBuyerHouse SET ';
            $sql .= 'fpkBuyerEmail = ?, ';
            $sql .= 'fpkHouseId = ? ';

            $data = array();
            $data[] = $email;
            $data[] = $houseId;

            $buyerHouseTableSuccess = $thisDatabaseWriter->insert($sql, $data);
        }
        else {
            $buyerHouseTableSuccess = true;
        }
        
        if (DEBUG) {
            print $thisDatabaseReader->displayQuery($sql, $data);
            print '<br>';
        }

        // table Buyer
        // insert into this table regardless of if intentToBuy is true
        $sql2 = 'INSERT INTO tblBuyer SET ';
        $sql2 .= 'pmkBuyerEmail = ?, ';
        $sql2 .= 'fldFirstName = ?, ';
        $sql2 .= 'fldLastName = ?, ';
        $sql2 .= 'fldPhoneNumber = ?, ';
        $sql2 .= 'fldMessage = ? ';
        $sql2 .= 'ON DUPLICATE KEY UPDATE ';
        $sql2 .= 'fldFirstName = ?, ';
        $sql2 .= 'fldLastName = ?, ';
        $sql2 .= 'fldPhoneNumber = ?, ';
        $sql2 .= 'fldMessage = ? ';

        $data2 = array();
        $data2[] = $email;
        $data2[] = $firstName;
        $data2[] = $lastName;
        $data2[] = $phoneNumber;
        $data2[] = $message;
        $data2[] = $firstName;
        $data2[] = $lastName;
        $data2[] = $phoneNumber;
        $data2[] = $message;
        
        $buyerTableSuccess = $thisDatabaseWriter->insert($sql2, $data2);

        
        if ($buyerTableSuccess && $buyerHouseTableSuccess) {
            // message depends on customers intent to buy
            if ($intentToBuy == 1) {
                print '<h2 class="success-message">We have been informed of your interest in buying from us!</h2>';
            }
            else {
                print '<h2 class="success-message">The agency has received your message!</h2>';
            }
        }
        else {
            print '<p class = "error-message">Something went wrong, your information has not been recorded properly.</p>';
        }
        if (DEBUG) {
            print $thisDatabaseReader->displayQuery($sql2, $data2);
        }
    }
}

// only show the form if the hid corresponds to a houseId that is on the market 
// (assigned a realtor and not purchased already)
if ($houseId != 0) {
    print '<form action="' . PHP_SELF . '" id="buyerForm" method="post">';
    print '<fieldset class="personal">';
    print '<p>';
    print '<label for="txtFirstName">First Name</label>';
    print '<input type="text" value="' . $firstName . '" name="txtFirstName" id="txtFirstName">';
    print '</p>';
    print '<p>';
    print '<label for="txtLastName">Last Name</label>';
    print '<input type="text" value="' . $lastName . '" name="txtLastName" id="txtLastName">';
    print '</p>';
    print '<p>';
    print '<label for="txtEmail">Email</label>';
    print '<input type="text" value="' . $email . '" name="txtEmail" id="txtEmail">';
    print '</p>';
    print '<p>';
    print '<label for="txtPhoneNumber">Phone Number</label>';
    print '<input type="text" value="' . $phoneNumber . '" name="txtPhoneNumber" id="txtPhoneNumber">';
    print '</p>';
    print '</fieldset>';
    print '<fieldset class = "message">';
    print '<p>';
    print '<label for="txtMessage">Message</label>';
    print '<textarea id="txtMessage" name="txtMessage" rows="6" cols="50">' . $message . '</textarea>';
    print '</p>'; 
    print '</fieldset>';
    print '<fieldset class="checkboxes">';
    print '<p>';
    print '<label for="chkIntentToBuy">Do you intend to purchase this house?</label>';
    if ($intentToBuy == 1) {
        print '<input type="checkbox" name="chkIntentToBuy" id="chkIntentToBuy" value="' . $intentToBuy . '" checked>';
    }
    else {
        print '<input type="checkbox" name="chkIntentToBuy" id="chkIntentToBuy" value ="' . $intentToBuy . '">';
    }
    print '</p>';
    print '</fieldset>';
    print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; 
    print '<fieldset>';
    print '<p><input type="submit" value="Purchase" tabindex="999" name="btnSubmit"></p>';
    print '</fieldset>';
    print '</form>';
}
print '</main>';
include 'footer.php';
?>