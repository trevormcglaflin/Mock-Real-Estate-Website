<?php
include 'top.php';

if ($adminPermissionLevel < 3) {
    $message = "You do not have permission to this page!";
    die($message);
}

// this sql block selects all houses that have not already been sold because
// you should not be able to delete a house that has been sold
$sql = 'SELECT DISTINCT pmkHouseId, fldNickName ';
$sql .= 'FROM tblHouse ';
$sql .= 'LEFT JOIN tblBuyHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'WHERE fpkHouseId IS NULL OR fldPending = 0';

$data = '';
$houses = $thisDatabaseReader->select($sql, $data);
?>
<main class="form-page">
<?php
if(is_array($houses)){
    $rowCount = 0;
    $rowClass = "even";
    foreach($houses as $house){
        if ($rowCount % 2 == 0) {
            $rowClass = "even";
        }
        else {
            $rowClass = "odd";
        }
        print '<p class=' . $rowClass . '>' . $house['fldNickName'];
        print '<a class="admin-button" href = "../admin/assignRealtorForm.php?hid=' . $house['pmkHouseId'] .  '">';
        print 'Change Realtor Assignment';
        print '</a></p>';
        $rowCount++;
    }
}
?>
</main>

<?php
    include "footer.php";
?>