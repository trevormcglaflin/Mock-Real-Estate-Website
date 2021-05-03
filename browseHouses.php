<?php
include 'top.php';

// select houses that have not been sold already, and are assigned to realtor
$sql = 'SELECT pmkHouseId, fldNickName, fldImageUrl ';
$sql .= 'FROM tblBuyerHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyerHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyerHouse.fpkHouseId IS NULL ';
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