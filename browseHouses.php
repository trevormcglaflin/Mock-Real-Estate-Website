<?php
include 'top.php';

// select houses that have not been sold already, and are assigned to realtor
$sql = 'SELECT DISTINCT pmkHouseId, fldNickName, fldImageUrl, fldPrice, fldSquareFeet, fldDistrict ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyHouse.fpkHouseId IS NULL OR tblBuyHouse.fldPurchased = 0 ';
$sql .= 'ORDER BY tblHouse.fldPrice DESC';

$data ='';
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main class = "house-browsing">

<?php
if(is_array($houses)){
    foreach($houses as $house){
        print '<section class="house-pic"><a href = "displayHouse.php?hid=' . $house['pmkHouseId'] . '"><figure class="house">';
        print '<img alt="' . $house['fldNickName'] . '" src="images/' 
        . $house['fldImageUrl'] . '">';
        print '</figure></a></section>';
        print '<section class = "house-block">';
        print '<p><b>' . $house['fldNickName'] . '</b></p>';
        print '<p>Market Price: $' . number_format($house['fldPrice']) . '</p>';
        print '<p>Square Feet: ' . $house['fldSquareFeet'] . '</p>';
        print '<p>District: ' . $house['fldDistrict'] . '</p>';
        print '</section>';
    }
}
?>

</main>

<?php
include 'footer.php';
?>