<?php
include 'top.php';

// if user is not the admin, prevent access
//if (!($isAdmin)) {
    //$message = "This page does not exist, please go away!";
    //die($message);
//}

$purchaseId = (isset($_GET['pid'])) ? (int) htmlspecialchars($_GET['pid']) : 0;

$sql = 'SELECT pmkPurchaseId, fpkBuyerEmail, fldNickName,  fldPrice, fldAddress, fldDistrict, fldSquareFeet, ';
$sql .= 'fldNickName, fldImageUrl ';
$sql .= 'FROM tblHouse ';
$sql .= 'JOIN tblBuyerHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'WHERE pmkPurchaseId = ?';

$data = array($purchaseId);
$purchases = $thisDatabaseReader->select($sql, $data);

if (sizeof($purchases) > 0) {
    $purchase = $purchases[0];
}
else {
    $purchase = NULL;
    $purchaseId = 0;
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
    $purchaseId = (int) getData('hdnPurchaseId');

    // delete record if field is valid
    if ($saveData) {
        // now, delete record from BuyerHouse
        $sql = 'DELETE FROM tblBuyerHouse ';
        $sql .= 'WHERE pmkPurchaseId = ?';
        $data = array();
        $data[] = $purchaseId;
        $buyerHouseTableSuccess = $thisDatabaseWriter->delete($sql, $data);
    }
    // display message
    if ($buyerHouseTableSuccess) {
        print '<h2 class="success-message">Purchase record has been deleted!</h2>';
        print '<p class="success-message">House is back on the market.</p>';
    }
    else {
        print '<h2 class="error-message">Something went wrong, purchase record was not deleted from database.</h2>';
    }
}
?>
<main>
<?php
// only show delete button if the record exists
if($purchaseId != 0) {
    print '<h3 class="warning">NOTICE: deleting this record will put the house back on the market.';
    print '<form action="' .PHP_SELF . '" id="deletePurchaseForm" method="post">';
    print '<input type="hidden" id="hdnPurchaseId" name="hdnPurchaseId" value="' . $purchaseId . '">'; 
    print '<fieldset>';
    print '<p><input type="submit" value="Delete Purchase Record" tabindex="999" name="btnSubmit"></p>';
    print '</fieldset>';
    print '</form>';
    
    foreach($purchases as $purchase) {
        print '<h3><b>Purchase ID: ' . $purchase['pmkPurchaseId'] . '</b></h3>';
        print '<h3><b>Buyer Email: ' . $purchase['fpkBuyerEmail'] . '</b></h3>';
        print '<p>' . $purchase['fldNickName'] . '</p>';
        print '<figure><img src=../images/' . $purchase['fldImageUrl'] . ' alt=housePic></figure>';
        print nl2br($purchase['fldDescription']);
        print '<h3><b>Price</b></h3>';
        print $purchase['fldPrice'];
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