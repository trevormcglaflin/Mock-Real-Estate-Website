<?php
include 'top.php';

// get houses that have been sold
$sql = 'SELECT pmkHouseId, fldNickName, fldSquareFeet, fldDistrict, fldImageUrl ';
$sql .= 'FROM tblBuyerHouse ';
$sql .= 'JOIN tblHouse ON fpkHouseId = pmkHouseId ';
$sql .= 'ORDER BY fldPrice DESC';
$data ='';
$houses = $thisDatabaseReader->select($sql, $data);
?>
<main>
<?php
print '<h2>Featured Houses From Our Portfolio</h2>';
$houseCount = 0;
if(is_array($houses)){
    // only show top 5 houses
    if ($houseCount < 5) {
        foreach($houses as $house){
            print '<figure class="house">';
            print '<img alt="' . $house['fldNickName'] . '" src="images/' 
            . $house['fldImageUrl'] . '">';
            print '<figcaption>' . $house['fldNickName'] . '</figcaption>';
            print '</figure>';
            
        }
    }
    $houseCount++;
}
print '<p>Note: these houses are no longer on market (go to browse houses tab to view houses currently for sale)';
?>
</main>

<?php
include 'footer.php';
?>