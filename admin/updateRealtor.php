<?php
include 'top.php';
// if user is not admin stop the script
//if (!($isAdmin)) {
    //$message = "This page does not exist, please go away!" . $netId;

    //die($message);
//}

$sql = 'SELECT pmkNetId ';
$sql .= 'FROM tblRealtor ';
$sql .= 'ORDER BY pmkNetId';


$data = '';
$realtors = $thisDatabaseReader->select($sql, $data);
?>

<main>
<?php
if(is_array($realtors)){
    $rowCount = 0;
    $rowClass = "even";
    foreach($realtors as $realtor){
        if ($rowCount % 2 == 0) {
            $rowClass = "even";
        }
        else {
            $rowClass = "odd";
        }
        print '<p class=' . $rowClass . '>' . $realtor['pmkNetId'];
        print '<a class="admin-button" href = "../admin/addRealtorForm.php?hid=' . $realtor['pmkNetId'] .  '">';
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