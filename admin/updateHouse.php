<?php
include 'top.php';
// if user is not admin stop the script
//if (!($isAdmin)) {
    //$message = "This page does not exist, please go away!" . $netId;

    //die($message);
//}

$sql = 'SELECT pmkHouseId, fldNickName ';
$sql .= 'FROM tblHouse ';
$sql .= 'ORDER BY pmkHouseId';

$data = '';
$houses = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php
print $netId;
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
        print '<a class="admin-button" href = "../admin/addHouseForm.php?hid=' . $house['pmkHouseId'] .  '">';
        print 'Update';
        print '</a></p>';
        $rowCount++;
    }
}
?>
</main>

<?php
    include "footer.php";
?>