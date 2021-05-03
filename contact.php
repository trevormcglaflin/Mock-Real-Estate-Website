<?php
include 'top.php';

$sql = 'SELECT fldFirstName, fldLastName, fldRealtorEmail, fldPhoneNumber, fldProfile ';
$sql .= 'FROM tblRealtor ';
$sql .= 'WHERE fldIsActive = 1 ';
$sql .= 'ORDER BY fldLastName';

$data ='';
$realtors = $thisDatabaseReader->select($sql, $data);
?>

<main>
<h2>Our Realtor Team</h2>
<?php
if(is_array($realtors)){
    foreach($realtors as $realtor){
        print '<section class="realtor-block">';
        print '<p>' . $realtor['fldFirstName'] . ' ' .$realtor['fldLastName'] . '</p>';
        print '<p><b>Profile</b></p>';
        print '<p>' . $realtor['fldProfile'] . '</p>';
        print '<p><b>Contact Info</b></p>';
        print '<p>Email:<em> ' . $realtor['fldRealtorEmail'] . '</em></p>';
        print '<p>Phone Number:<em> ' . $realtor['fldPhoneNumber'] . '</em></p>';
        print '</section>';
        
    }
}
?>
</main>
<?php
include 'footer.php';
?>