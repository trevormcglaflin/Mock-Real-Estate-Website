<?php
include 'top.php';

if ($adminPermissionLevel < 1) {
    $message = "You do not have permission to this page!";
    die($message);
}

// houses on market
$sql = 'SELECT pmkHouseId, fldNickName, fldPrice ';
$sql .= 'FROM tblHouse ';
$sql .= 'LEFT JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= '(WHERE fpkHouseId IS NULL OR fldPurchased = 0) AND ';

$data = '';
$housesOnMarket = $thisDatabaseReader->select($sql, $data);

// houses pending
$sql = 'SELECT pmkHouseId, fldNickName, fldPrice ';
$sql .= 'FROM tblHouse ';
$sql .= 'LEFT JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'fldPurchased = 0 AND fldPending = 1';

$data = '';
$housesPending = $thisDatabaseReader->select($sql, $data);

// houses sold
$sql = 'SELECT pmkHouseId, fldNickName, fldPrice ';
$sql .= 'FROM tblHouse ';
$sql .= 'LEFT JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'fldPurchased = 1';

$data = '';
$housesSold = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php
print '<h2>Houses On Market</h2>';
if(is_array($housesOnMarket)){
    $rowCount = 0;
    $rowClass = "even";
    foreach($houses as $house){
        if ($rowCount % 2 == 0) {
            $rowClass = "even";
        }
        else {
            $rowClass = "odd";
        }
        print '<p class=' . $rowClass . '>' . $house['fldNickName'];
        print '<a class="admin-button" href = "../admin/deleteHouseForm.php?hid=' . $house['pmkHouseId'] .  '">';
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