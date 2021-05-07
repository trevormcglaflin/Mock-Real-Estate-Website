<?php
include 'top.php';

$sql = 'SELECT fldFirstName, fldLastName, fldRealtorEmail, fldPhoneNumber, fldProfile, fldProfilePicture ';
$sql .= 'FROM tblRealtor ';
$sql .= 'WHERE fldIsActive = 1 ';
$sql .= 'ORDER BY fldLastName';

$data ='';
$realtors = $thisDatabaseReader->select($sql, $data);
?>
<main id="contact-page">
<?php
if(is_array($realtors)){
    foreach($realtors as $realtor){
        print '<section class="realtor-info">';
        print '<p>' . $realtor['fldFirstName'] . ' ' .$realtor['fldLastName'] . '</p>';
        print '<p><b>Profile</b></p>';
        print '<p>' . $realtor['fldProfile'] . '</p>';
        print '<p><b>Contact Info</b></p>';
        print '<p>Email:<em> ' . $realtor['fldRealtorEmail'] . '</em></p>';
        print '<p>Phone Number:<em> ' . $realtor['fldPhoneNumber'] . '</em></p>';
        print '</section>';
        print '<section class="realtor-picture"><figure><img src=images/' . $realtor['fldProfilePicture'] . ' alt=realtorPic><figure></section>';
    }
}
?>
</main>
<?php
include 'footer.php';
?>