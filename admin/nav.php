<nav class="navbar">
    <a class="<?php
    if (PATH_PARTS['filename'] == "index") {
        print'activePage';
    }
    ?>" href="../index.php"><-Back to Home</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/add-house") {
        print'activePage';
    }
    ?>" href="addHouseForm.php">Add House</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/updateHouse") {
        print'activePage';
    }
    ?>" href="updateHouse.php">Update House</a>

    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/delete-house") {
        print'activePage';
    }
    ?>" href="deleteHouse.php">Delete House</a>
    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/add-realtor") {
        print'activePage';
    }
    ?>" href="addRealtorForm.php">Add Realtor</a>
    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/update-realtor") {
        print'activePage';
    }
    ?>" href="updateRealtor.php">Update Realtor</a>
    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/house-to-assign") {
        print'activePage';
    }
    ?>" href="houseToAssign.php">Re-Assign House</a>
    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/purchase-status") {
        print'activePage';
    }
    ?>" href="purchaseStatus.php">Purchase Statuses</a>
    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/company-report") {
        print'activePage';
    }
    ?>" href="companyReport.php">Company Report</a>
    <a class="<?php
    if (PATH_PARTS['filename'] == "admin/realtor-report") {
        print'activePage';
    }
    ?>" href="realtorReport.php">Realtor Report</a>
</nav>