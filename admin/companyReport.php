<?php
include 'top.php';

if ($adminPermissionLevel < 3) {
    $message = "You do not have permission to this page!";
    die($message);
}

// select all houses that agency has sold
$sql = 'SELECT COUNT(*), SUM(fldPrice), AVG(DATEDIFF(fldPurchaseDate, fldDateListed)) ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE tblBuyHouse.fldPurchased = 1';

$allTimeHouses = $thisDatabaseReader->select($sql);

// now look at performance in last 365 days 
$sql = 'SELECT COUNT(*), SUM(fldPrice), AVG(DATEDIFF(fldPurchaseDate, fldDateListed)) ';
$sql .= 'FROM tblBuyHouse ';
$sql .= 'RIGHT JOIN tblHouse ON tblBuyHouse.fpkHouseId = tblHouse.pmkHouseId ';
$sql .= 'JOIN tblHouseRealtor ON tblHouse.pmkHouseID = tblHouseRealtor.fpkHouseId ';
$sql .= 'WHERE YEAR(tblBuyHouse.fldPurchaseDate) = YEAR(CURDATE()) ';
$sql .= 'AND tblBuyHouse.fldPurchased = 1';

$currentYearHouses = $thisDatabaseReader->select($sql);
?>

<main>
<?php
print '<section id="realtor-all-time">';
print '<h2>Sales Performance - Current Sales Cycle</h2>';
print '<p>Total Revenue: $' . number_format($currentYearHouses[0]['SUM(fldPrice)']) . '</p>';
print '<p>Houses Sold: ' . $currentYearHouses[0]['COUNT(*)'] . '</p>';
print '<p>Average House Turnover: ' . number_format($currentYearHouses[0]['AVG(DATEDIFF(fldPurchaseDate, fldDateListed))']) . ' days</p>';
print '</section>';

print '<section id="realtor-all-time">';
print '<h2>Sales Performance - All Time</h2>';
print '<p>Total Revenue: $' . number_format($allTimeHouses[0]['SUM(fldPrice)']) . '</p>';
print '<p>Houses Sold: ' . $allTimeHouses[0]['COUNT(*)'] . '</p>';
print '<p>Average House Turnover: ' . number_format($allTimeHouses[0]['AVG(DATEDIFF(fldPurchaseDate, fldDateListed))']) . ' days</p>';
print '</section>';
?>
</main>

<?php
    include "footer.php";
?>