<?php
include 'top.php';
$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;
print '<p>House Id = ' . $houseId;

// get house info
// this sql only lets houses that are still for sale and are assigned a realtor to be viewed
$sql = 'SELECT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldNickName, fldImageUrl ';
$sql .= 'FROM tblBuyerHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyerHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyerHouse.fpkHouseId IS NULL AND pmkHouseId = ? ';

$data = array($houseId);
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php
if(sizeof($houses) > 0) {
    foreach($houses as $house) {
        print '<a class="button" href="purchaseHouse.php?hid=' . $house['pmkHouseId'] . '">Buy ' . $house['fldNickName'] . '!</a><br>';
        print '<p>Adress: ' . $house['fldAddress'] . '</p>';
        print '<p>Price: ' . $house['fldPrice'] . '</p>';
        print '<p>District: ' . $house['fldDistrict'] . '</p>';
        print '<p>Square Feet: ' . $house['fldSquareFeet'] . '</p>';
        print '<figure><img src=images/' . $house['fldImageUrl'] . ' alt=housePic></figure>';
        print nl2br($house['fldDescription']);
    }
}
?>
</main>

<?php
include 'footer.php';
?>