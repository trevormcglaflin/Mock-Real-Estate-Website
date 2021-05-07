<?php
include 'top.php';
$houseId = (isset($_GET['hid'])) ? (int) htmlspecialchars($_GET['hid']) : 0;

// get house info
// this sql only lets houses that are still for sale and are assigned a realtor to be viewed
$sql = 'SELECT DISTINCT pmkHouseId, fldPrice, fldAddress, fldDescription, fldDistrict, ';
$sql .= 'fldSquareFeet, fldNickName, fldImageUrl ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE (tblBuyHouse.fpkHouseId IS NULL OR tblBuyHouse.fldPurchased = 0) AND pmkHouseId = ? ';

$data = array($houseId);
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main class = "house-browsing">
<?php
if(sizeof($houses) > 0) {
    foreach($houses as $house) {
        print '<section class="house-pic"><figure class="house">';
        print '<img alt="' . $house['fldNickName'] . '" src="images/' 
        . $house['fldImageUrl'] . '">';
        print '</figure></section>';
        print '<section class = "house-block">';
        print '<p><b>' . $house['fldNickName'] . '</b></p>';
        print '<p>Market Price: $' . number_format($house['fldPrice']) . '</p>';
        print '<p>Square Feet: ' . $house['fldSquareFeet'] . '</p>';
        print '<p>District: ' . $house['fldDistrict'] . '</p>';
        print '<a class="button" href="purchaseHouse.php?hid=' . $house['pmkHouseId'] . '">Message Realtor!</a><br>';
        print '</section>';
        print '<section class="house-profile">';
        print '<p><b>House Profile</b></p>';
        print '<p>' . $house['fldDescription'] . '</p>';
        print '</section>';
    }
}
?>
</main>

<?php
include 'footer.php';
?>