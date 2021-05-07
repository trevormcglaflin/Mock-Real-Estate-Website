<?php
include 'top.php';

if ($adminPermissionLevel < 3) {
    $message = "You do not have permission to this page!";
    die($message);
}

// this sql block selects all houses that have not already been sold because
// you should not be able to delete a house that has been sold
$sql = 'SELECT pmkPurchaseId, fldNickName, fldPending, fldPurchased ';
$sql .= 'FROM tblHouse ';
$sql .= 'JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'ORDER BY pmkPurchaseId';

$data = '';
$purchases = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php
// interested section
print '<section class="purchase-section"><h2>Interested</h2>';
if(is_array($purchases)){
    $rowCount = 0;
    $rowClass = "even";
    foreach($purchases as $purchase){
        if ($rowCount % 2 == 0) {
            $rowClass = "even";
        }
        else {
            $rowClass = "odd";
        }
        if (!$purchase['fldPending'] && !$purchase['fldPurchased']) {
            print '<p class=' . $rowClass . '>Purchase ID: ' . $purchase['pmkPurchaseId'] . ' ' . $purchase['fldNickName'];
            print '<a class="admin-button" href = "../admin/purchaseStatusForm.php?pid=' . $purchase['pmkPurchaseId'] .  '">';
            print 'Change Status';
            print '</a></p>';
            $rowCount++;
        }
    }
}
print '</section>';


// pending orders
print '<section class="purchase-section"><h2>Pending Orders</h2>';
if(is_array($purchases)){
    $rowCount = 0;
    $rowClass = "even";
    foreach($purchases as $purchase){
        if ($rowCount % 2 == 0) {
            $rowClass = "even";
        }
        else {
            $rowClass = "odd";
        }
        if ($purchase['fldPending'] && !$purchase['fldPurchased']) {
            print '<p class=' . $rowClass . '>Purchase ID: ' . $purchase['pmkPurchaseId'] . ' ' . $purchase['fldNickName'];
            print '<a class="admin-button" href = "../admin/purchaseStatusForm.php?pid=' . $purchase['pmkPurchaseId'] .  '">';
            print 'Change Status';
            print '</a></p>';
            $rowCount++;
        }
    }
}
print '</section>';

// purchased houses
print '<section class="purchase-section"><h2>Purchased Houses</h2>';
if(is_array($purchases)){
    $rowCount = 0;
    $rowClass = "even";
    foreach($purchases as $purchase){
        if ($rowCount % 2 == 0) {
            $rowClass = "even";
        }
        else {
            $rowClass = "odd";
        }
        if ($purchase['fldPurchased']) {
            print '<p class=' . $rowClass . '>Purchase ID: ' . $purchase['pmkPurchaseId'] . ' ' . $purchase['fldNickName'];
            print '<a class="admin-button" href = "../admin/purchaseStatusForm.php?pid=' . $purchase['pmkPurchaseId'] .  '">';
            print 'Change Status';
            print '</a></p>';
            $rowCount++;
        }
    }
}
print '</section>';
?>
</main>

<?php
    include "footer.php";
?>