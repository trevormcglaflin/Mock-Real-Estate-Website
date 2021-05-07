<?php
include 'top.php';
$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;
?>

<main class="form-page">

<?php
// get house info
// this sql makes sure only lets houses that are still for sale and are assigned a realtor to be purchased
$sql = 'SELECT DISTINCT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldNickName, fldImageUrl ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE (tblBuyHouse.fpkHouseId IS NULL OR tblBuyHouse.fldPurchased = 0) AND pmkHouseId = ? ';

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
$email = "";
$firstName = "";
$lastName = "";
$phoneNumber = "";
$message = "";

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
    print '<p id="message-realtor-header">Message Realtor about ' . $house['fldNickName'] . '</p>';
}

if(isset($_POST['btnSubmit'])) {
    if(DEBUG) {
        print '<p>POST array:<p><pre>';
        print_r($_POST);
        print '</pre>';
    }

    // sanitize data
    $email = filter_var($_POST['txtEmail'], FILTER_SANITIZE_EMAIL);
    $firstName = filter_var($_POST['txtFirstName'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['txtLastName'], FILTER_SANITIZE_STRING);
    $phoneNumber = filter_var($_POST['txtPhoneNumber'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['txtMessage'], FILTER_SANITIZE_STRING);
    $houseId = (int) getData('hdnHouseId');

    // validate data
    if (!validateName($firstName) || strlen($firstName) > 25) {
        print '<p class="mistake">Please enter a valid first name.</p>';
        $saveData = false;
    }

    if (!validateName($lastName) || strlen($lastName) > 35) {
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

    if ($houseId < 0) {
        print '<p class="mistake">Hidden house id value is invalid.</p>';
        $saveData = false;
    }
    
    if ($saveData) {
        // table BuyerHouse
        $sql = 'INSERT INTO tblBuyHouse SET ';
        $sql .= 'fpkBuyerEmail = ?, ';
        $sql .= 'fpkHouseId = ?, ';
        $sql .= 'fldMessage = ? ';

        $data = array();
        $data[] = $email;
        $data[] = $houseId;
        $data[] = $message;

        $buyerHouseTableSuccess = $thisDatabaseWriter->insert($sql, $data);
        
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
        $sql2 .= 'fldPhoneNumber = ? ';
        $sql2 .= 'ON DUPLICATE KEY UPDATE ';
        $sql2 .= 'fldFirstName = ?, ';
        $sql2 .= 'fldLastName = ?, ';
        $sql2 .= 'fldPhoneNumber = ? ';
        
        $data2 = array();
        $data2[] = $email;
        $data2[] = $firstName;
        $data2[] = $lastName;
        $data2[] = $phoneNumber;
        $data2[] = $firstName;
        $data2[] = $lastName;
        $data2[] = $phoneNumber;
        
        $buyerTableSuccess = $thisDatabaseWriter->insert($sql2, $data2);

        // display form message
        print '<section class="form-message">';
        if ($buyerTableSuccess && $buyerHouseTableSuccess) {
            print '<h2 class="success-message">The agency has received your message, and will be in touch shortly!</h2>';
            
            // send mail to potential buyer with form info
            $to = $email;
            $from = 'The McGlaflin Crib Co. Family <tmcglafl@uvm.edu>';
            $subject = 'We have received your message!';
            
            $mailMessage = '<section style="font-family: Arial;';
            $mailMessage .= 'color: grey;';
            $mailMessage .= 'background-color: ghostwhite;padding: 10px;">';
            $mailMessage .= '<h2>We appreciate your interest, welcome to the McGlaflin crib family!</h2>';
            $mailMessage .= '<p>Here is your info that we recieved: </p>';
            $mailMessage .= '<p>First Name: ' . $firstName . '</p>';
            $mailMessage .= '<p>Last Name: ' . $lastName . '</p>';
            $mailMessage .= '<p>Email: ' . $email . '</p>';
            $mailMessage .= '<p>Phone Number: ' . $phoneNumber . '</p>';
            $mailMessage .= '<p>Message</p>';
            $mailMessage .= '<p>' . $message . '</p>';
            $mailMessage .= '</section>';

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: " . $from . "\r\n";
            $mailSent = mail($to, $subject, $mailMessage, $headers);

            if ($mailSent) {
                print '<h2 class="success-message">A copy has been emailed to you.</h2>';
            }
        }
        else {
            print '<p class = "error-message">Something went wrong, your information has not been recorded properly.</p>';
        }
        print '</section>';
        
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
    print '<label class="text-area-label" for="txtMessage">Message</label>';
    print '<textarea class="text-area-input" id="txtMessage" name="txtMessage" rows="6" cols="50">' . $message . '</textarea>';
    print '</p>'; 
    print '</fieldset>';
    print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; 
    print '<fieldset>';
    print '<p><input class="submit-button" type="submit" value="Message Realtor" tabindex="999" name="btnSubmit"></p>';
    print '</fieldset>';
    print '</form>';
}
print '</main>';
include 'footer.php';
?>