<!DOCTYPE HTML>
<html lang = "en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Trevor McGlaflin">
        <meta name="description" content="McGlaflin Crib Co.">
        <title>McGlaflin Crib Co.</title>

        <link rel = "stylesheet"
            href = "css/custom.css?version=<?php print time(); ?>"
            type="text/css">
        <link rel = "stylesheet" media="(max-width:800px)"
            href = "css/tablet.css?version=<?php print time(); ?>"
            type="text/css">
        <link rel="stylesheet" media="(max-width: 600px)"
            href = "css/phone.css?version=<?php print time(); ?>"
            type = "text/css">
<!-- **** include libaries **** -->
<?php
include 'lib/constants.php';
include 'lib/Database.php';
print '<!-- make database connections -->';
require_once(LIB_PATH . '/Database.php');
$thisDatabaseReader = new DataBase('tmcglafl_reader', 'r', DATABASE_NAME);
$thisDatabaseWriter = new DataBase('tmcglafl_writer', 'w', DATABASE_NAME);
?>
</head>

<?php
print'<body id="' . PATH_PARTS['filename'] . '">';
print '<!--****** START OF THE BODY **** -->';

print PHP_EOL;

include 'header.php';
print PHP_EOL;

include 'nav.php';
print PHP_EOL;

?>
