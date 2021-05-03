<?php
include 'top.php';
// if user is not admin stop the script
//if (!($isAdmin)) {
    //$message = "This page does not exist, please go away!" . $netId;

    //die($message);
//}

// this sql block selects all houses that have not already been sold because
// you should not be able to delete a house that has been sold

$sql = 'SELECT pmkPurchaseId, fldNickName ';
$sql .= 'FROM tblHouse ';
$sql .= 'JOIN tblBuyerHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'ORDER BY pmkPurchaseId';

$data = '';
$purchases = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php
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
        print '<p class=' . $rowClass . '>Purchase ID: ' . $purchase['pmkPurchaseId'] . ' ' . $purchase['fldNickName'];
        print '<a class="admin-button" href = "../admin/deletePurchaseForm.php?pid=' . $purchase['pmkPurchaseId'] .  '">';
        print 'Delete';
        print '</a></p>';
        $rowCount++;
    }
}
?>
</main>

<?php
    include "footer.php";
?>