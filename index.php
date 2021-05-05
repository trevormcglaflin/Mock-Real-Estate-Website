<?php
include 'top.php';

// get houses that have been sold
$sql = 'SELECT pmkHouseId, fldNickName, fldSquareFeet, fldDistrict, fldImageUrl ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'JOIN tblHouse ON fpkHouseId = pmkHouseId ';
$sql .= 'WHERE fldPurchased = 1 ';
$sql .= 'ORDER BY fldPrice DESC LIMIT 5';
$data ='';
$houses = $thisDatabaseReader->select($sql, $data);
?>
<main>
<?php
print '<h2>Featured Houses From Our Portfolio</h2>';
$houseCount = 0;
if(is_array($houses)){
    foreach($houses as $house){
        print '<figure class="house">';
        print '<img alt="' . $house['fldNickName'] . '" src="images/' 
        . $house['fldImageUrl'] . '">';
        print '<figcaption>' . $house['fldNickName'] . '</figcaption>';
        print '</figure>';
    }
}

print '<p>Note: these houses are no longer on market (go to browse houses tab to view houses currently for sale)';
?>
</main>

<?php
include 'footer.php';
?>