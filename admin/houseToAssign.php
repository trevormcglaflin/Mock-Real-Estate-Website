<?php
include 'top.php';
// if user is not admin stop the script
//if (!($isAdmin)) {
    //$message = "This page does not exist, please go away!" . $netId;

    //die($message);
//}

// this sql block selects all houses that have not already been sold because
// you should not be able to delete a house that has been sold

$sql = 'SELECT pmkHouseId, fldNickName ';
$sql .= 'FROM tblHouse ';
$sql .= 'LEFT JOIN tblBuyerHouse ON pmkHouseId = fpkHouseId ';
$sql .= 'WHERE fpkHouseId IS NULL';

$data = '';
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main>
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