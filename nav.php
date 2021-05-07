<nav class="navbar">
    <a class="<?php
    if (PATH_PARTS['filename'] == "index") {
        print'activePage';
    }
    ?>" href="index.php">Home</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "about") {
        print'activePage';
    }
    ?>" href="about.php">About</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "browseHouses") {
        print'activePage';
    }
    ?>" href="browseHouses.php">Browse Houses</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "contact") {
        print'activePage';
    }
    ?>" href="contact.php">Contact</a>


<?php
print '<div class = "dropdown">';
print '<button data-href="admin.php" class = "dropbtn">Admin';
print '<i class="fa fa-caret-down"></i>';
print '</button>';
print '<div class = "dropdown-content">';
print '<a href="admin/addHouseForm.php">Add House</a>';
print '<a href="admin/updateHouse.php">Update House</a>';
print '<a href="admin/deleteHouse.php">Delete House</a>';
print '<a href="admin/addRealtorForm.php">Add Realtor</a>';
print '<a href="admin/updateRealtor.php">Update Realtor</a>';
print '<a href="admin/houseToAssign.php">Re-Assign House</a>';
print '<a href="admin/purchaseStatus.php">Purchase Statuses</a>';
print '<a href="admin/companyReport.php">Company Report</a>';
print '<a href="admin/realtorReport.php">Realtor Report</a>';
print '</div>';
print '</div>';
?>

</nav>