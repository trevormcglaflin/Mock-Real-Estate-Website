<?php
include 'top.php';

$sql = 'SELECT pmkHouseId, fldPrice, fldAddress, fldDistrict, fldSquareFeet, fldImageUrl ';
$sql .= 'FROM tblHouse ';
$sql .= 'ORDER BY fldPrice';

$data ='';
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main>

<?php
if(is_array($houses)){
    foreach($houses as $house){
        print '<a href = "https://tmcglafl.w3.uvm.edu/cs148/dev-lab5/displayHouse.php?hid=' . $house['pmkHouseId'] . '"><figure class="house">';
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