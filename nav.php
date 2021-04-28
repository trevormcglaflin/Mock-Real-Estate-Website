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
// only show this dropdown if the user is an admin
if ($isAdmin) {
    print '<div class = "dropdown">';
    print '<button class = "dropbtn"  href="admin.php">Admin';
    print '<i class="fa fa-caret-down"></i>';
    print '</button>';
    print '<div class = "dropdown-content">';
    print '<a href="admin\insertRecordForm.php">Add Wildlife Record</a>';
    print '<a href="admin\updateRecord.php">Update Wildlife Records</a>';
    print '<a href="admin\deleteRecord.php">Delete Wildlife Records</a>';
    print '</div>';
    print '</div>';
}
?>

</nav>