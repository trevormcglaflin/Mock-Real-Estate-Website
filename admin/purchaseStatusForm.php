<?php
include 'top.php';

if ($adminPermissionLevel < 3) {
    $message = "You do not have permission to this page!";
    die($message);
}

$purchaseId = (isset($_GET['pid'])) ? (int) htmlspecialchars($_GET['pid']) : 0;

$sql = 'SELECT pmkPurchaseId, fpkBuyerEmail, fpkHouseId, fldNickName, fldPrice, fldDateListed, ';
$sql .= 'fldAddress, fldDistrict, fldSquareFeet, fldNickName, fldImageUrl, fldPending, fldPurchased ';
$sql .= 'FROM tblHouse ';
$sql .= 'JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'WHERE pmkPurchaseId = ?';

$data = array($purchaseId);
$purchases = $thisDatabaseReader->select($sql, $data);

if (sizeof($purchases) > 0) {
    $purchase = $purchases[0];
    $isPending = $purchase['fldPending'];
    $isPurchased = $purchase['fldPurchased'];
    // need this so drop down can be sticky
    if ($isPurchased == 1) {
        $purchaseStatus = "purchased";
    }
    else if ($isPending == 1) {
        $purchaseStatus = "pending";
    }
    else {
        $purchaseStatus = "interested";
    }
    $houseId = $purchase['fpkHouseId'];
}
else {
    $purchase = NULL;
    $purchaseId = 0;
}

// initialize save data to true
$saveData = true;

print '<main class="form-page">';

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
    print '<section class="form-message">';
    // sanitize
    $purchaseId = (int) getData('hdnPurchaseId');
    $houseId = (int) getData('hdnHouseId');
    $nickName = filter_var($_POST['hdnNickName'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['hdnBuyerEmail'], FILTER_SANITIZE_STRING);
    $price = (int) getData('hdnPrice');
    $purchaseStatus = filter_var($_POST['dwnPurchaseStatus'], FILTER_SANITIZE_STRING);

    // validate
    if ($purchaseStatus != "interested" && $purchaseStatus != "pending" && $purchaseStatus != "purchased" && $purchaseStatus != "cancelled") {
        print '<p class="mistake">Invalid purchase ID.</p>';
        $saveData = false;
    }

    // if house has already been purchased, it should not be able to be purchased or pending again
    $sql = 'SELECT pmkPurchaseId ';
    $sql .= 'FROM tblHouse ';
    $sql .= 'JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
    $sql .= 'WHERE fldPurchased = 1 AND fpkHouseId = ?';
    $data = array($houseId);
    $isAlreadyPurchased = $thisDatabaseReader->select($sql, $data);
    if (sizeof($isAlreadyPurchased) > 0 && $purchaseStatus == "purchased") {
        print '<p class="mistake">This house has already been purchased!</p>';
        $saveData = false;
    }

    if ($saveData) {
        // depending on what the value of purchaseStatus is we will do different sql statements
        if ($purchaseStatus == "cancelled") {
            $sql = 'DELETE FROM tblBuyHouse ';
            $sql .= 'WHERE pmkPurchaseId = ?';
            $data = array();
            $data[] = $purchaseId;
            $buyerHouseTableSuccess = $thisDatabaseWriter->delete($sql, $data);
        }
        else {
            // update tblBuyHouse
            $sql = 'UPDATE tblBuyHouse SET ';
            $sql .= 'fldPending = ?, ';
            $sql .= 'fldPurchased = ?, ';
            $sql .= 'fldPurchaseDate = NOW() ';
            $sql .= 'WHERE ';
            $sql .= 'pmkPurchaseId = ? ';

            $data = array();
            if ($purchaseStatus == "pending" || $purchaseStatus == "purchased") {
                $data[] = 1;
            }
            else {
                $data[] = 0;
            }
            if ($purchaseStatus == "purchased") {
                $data[] = 1;
            }
            else {
                $data[] = 0;
            }
            $data[] = $purchaseId;

            $buyerHouseTableSuccess = $thisDatabaseWriter->insert($sql, $data);
        }
    }

    if ($buyerHouseTableSuccess) {
        print '<h2 class="success-message">Purchase record has been updated!</h2>';

        // now delete all other records for that particular house since house has been sold
        if ($purchaseStatus == "purchased") {
            $sql = 'DELETE FROM tblBuyHouse ';
            $sql .= 'WHERE fldPurchased = 0 AND fpkHouseId = ?';
            $data = array();
            $data[] = $houseId;
            $deleteOthersSuccess = $thisDatabaseWriter->delete($sql, $data);

            // now let the buyer now that the purchase has been executed
            // send mail to potential buyer with form info
            $sql = 'SELECT fldPurchaseDate FROM tblBuyHouse WHERE pmkPurchaseId = ?';
            $data = array($purchaseId);
            $dateOfPurchase = $thisDatabaseWriter->select($sql, $data);
            $to = $email;
            $from = 'The McGlaflin Crib Co. Family <tmcglafl@uvm.edu>';
            $subject = 'Congrats on the buying ' . $nickName . '!';
        
            $mailMessage = '<section style="font-family: Arial;';
            $mailMessage .= 'color: grey;';
            $mailMessage .= 'background-color: ghostwhite;padding: 10px;">';
            $mailMessage .= '<h2>We have processed the transaction!</h2>';
            $mailMessage .= '<p><b>Transaction Information</b></p>';
            $mailMessage .= '<p>Price Paid: $' . number_format($price) . '</p>';
            $mailMessage .= '<p>Transaction Date:' . $dateOfPurchase[0]['fldPurchaseDate'] . '</p>';
            $mailMessage .= '<p>From all of us at McGlaflin Crib Co., we appreciate your business!</p>';
            $mailMessage .= '</section>';

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: " . $from . "\r\n";
            $mailSent = mail($to, $subject, $mailMessage, $headers);

            if ($mailSent) {
                print '<h2 class="success-message">Buyer has been informed of transaction.</h2>';
            }
        }
        
        if ($deleteOthersSuccess) {
            print '<p class="success-message">All other records associated with that house have been deleted.</p>';
        }

    }
    else {
        print '<h2 class="error-message">Something went wrong, purchase record was not updated.</h2>';
    }
    print '</section>';
}
?>
<?php
// only show form if the record exists
if($purchaseId != 0) {
    print '<h3 class="warning">NOTICE: cancelling a record will delete completely from database.</h3>';
    print '<form action="' .PHP_SELF . '" id="deletePurchaseForm" method="post">';
    print '<fieldset>';
    print '<label for="dwnPurchaseStatus">Purchase Status</label>';
    print '<select id="dwnPurchaseStatus" name="dwnPurchaseStatus">';
    print '<option value="interested"'; 
    if ($purchaseStatus == "interested") print ' selected ';
    print '>Interested</option>';
    print '<option value="pending"';   
    if ($purchaseStatus == "pending") print ' selected ';
    print '>Pending</option>';
    print '<option value="purchased"';
    if ($purchaseStatus == "purchased") print ' selected '; 
    print '>Purchased</option>';
    print '<option value="cancelled">Cancelled</option>';
    print '</select>';
    print '</fieldset>';
    print '<input type="hidden" id="hdnPurchaseId" name="hdnPurchaseId" value="' . $purchaseId . '">'; 
    print '<input type="hidden" id="hdnHouseId" name="hdnHouseId" value="' . $houseId . '">'; 
    print '<input type="hidden" id="hdnNickName" name="hdnNickName" value="' . $purchase['fldNickName'] . '">';
    print '<input type="hidden" id="hdnBuyerEmail" name="hdnBuyerEmail" value="' . $purchase['fpkBuyerEmail'] . '">';  
    print '<input type="hidden" id="hdnPrice" name="hdnPrice" value="' . $purchase['fldPrice'] . '">'; 
    print '<fieldset>';
    print '<p><input class="submit-button" type="submit" value="Update Purchase Record" tabindex="999" name="btnSubmit"></p>';
    print '</fieldset>';
    print '</form>';
    
    foreach($purchases as $purchase) {
        print '<h3><b>Purchase ID: ' . $purchase['pmkPurchaseId'] . '</b></h3>';
        print '<h3><b>Buyer Email: ' . $purchase['fpkBuyerEmail'] . '</b></h3>';
        print '<p>' . $purchase['fldNickName'] . '</p>';
        print '<figure><img src=../images/' . $purchase['fldImageUrl'] . ' alt=housePic></figure>';
        print nl2br($purchase['fldDescription']);
        print '<h3><b>Price</b></h3>';
        print number_format($purchase['fldPrice']);
        print '<h3><b>Address</b></h3>';
        print $purchase['fldAddress'];
        print '<h3><b>District</b></h3>';
        print $purchase['fldDistrict'];
        print '<h3><b>Square Feet</b></h3>';
        print $purchase['fldSquareFeet'];
        print '<h3><b>Date Listed</b></h3>';
        print $purchase['fldDateListed'];
        print '<h3><b>Image Url</b></h3>';
        print $purchase['fldImageUrl'];
    }
}
?>
</main>

<?php
    include "footer.php";
?>