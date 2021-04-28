<nav class="navbar">
    <a class="<?php
    if (PATH_PARTS['filename'] == "index") {
        print'activePage';
    }
    ?>" href="..\index.php"><-Back to Home</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "admin\add-house") {
        print'activePage';
    }
    ?>" href="addHouseForm.php">Add House</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "admin\update-house") {
        print'activePage';
    }
    ?>" href="updateHouse.php">Update House</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "admin\delete-house") {
        print'activePage';
    }
    ?>" href="deleteHouse.php">Delete House</a>

</nav>