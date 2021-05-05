<?php
include 'top.php';

if ($adminPermissionLevel < 2) {
    $message = "You do not have permission to this page!";
    die($message);
}



// select all houses that this realtor has sold
$sql = 'SELECT COUNT(*), SUM(fldPrice) ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyHouse.fldPurchased = 1 AND tblHouseRealtor.fpkNetId = ?';

$data = array($netId);
$allTimeHouses = $thisDatabaseReader->select($sql, $data);

// now look at performance in last 365 days 
$sql = 'SELECT COUNT(*), SUM(fldPrice) ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE YEAR(tblBuyHouse.fldPurchaseDate) = YEAR(CURDATE()) ';
$sql .= 'AND tblBuyHouse.fldPurchased = 1 ';
$sql .=  'AND tblHouseRealtor.fpkNetId = ?';

$currentYearHouses = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php

print '<section id="realtor-all-time">';
print '<h2>Sales Performance - Current Sales Cycle</h2>';
print '<p>Total Revenue: $' . number_format($currentYearHouses[0]['SUM(fldPrice)']) . '</p>';
print '<p>Houses Sold: ' . $currentYearHouses[0]['COUNT(*)'] . '</p>';
print '</section>';

print '<section id="realtor-all-time">';
print '<h2>Sales Performance - All Time</h2>';
print '<p>Total Revenue: $' . number_format($allTimeHouses[0]['SUM(fldPrice)']) . '</p>';
print '<p>Houses Sold: ' . $allTimeHouses[0]['COUNT(*)'] . '</p>';
print '</section>';

?>
</main>

<?php
    include "footer.php";
?>

