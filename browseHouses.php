<?php
include 'top.php';

// select houses that have not been sold already, and are assigned to realtor
$sql = 'SELECT pmkHouseId, fldNickName, fldImageUrl ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyHouse.fpkHouseId IS NULL OR tblBuyHouse.fldPurchased = 0 ';
$sql .= 'ORDER BY tblHouse.fldPrice DESC';

$data ='';
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main>

<?php
if(is_array($houses)){
    foreach($houses as $house){
        print '<a href = "displayHouse.php?hid=' . $house['pmkHouseId'] . '"><figure class="house">';
        print '<img alt="' . $house['fldNickName'] . '" src="images/' 
        . $house['fldImageUrl'] . '">';
        print '<figcaption>' . $house['fldNickName'] . '</figcaption>';
        print '</figure></a>';
    }
}
?>

</main>

<?php
include 'footer.php';
?>