<!DOCTYPE HTML>
<html lang = "en">
    <head>
        <!-- Basic site header -->
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <meta name = "author" content = "John S">
        <meta name = "description" content = "Cool friggen albums">

        <!-- site title and content -->
        <title>John's UVM CS148 Final</title><meta name = "about" content = "This is the site for John's CS148 final project, revolving around
            the buying of old classic albums">
        
        <!-- CSS links and updates - Commented out because it is not included yet -->
        <link rel = "stylesheet"
            href = "css/custom.css?version=<?php print time(); ?>"
            type = "text/css">
        <link rel = "stylesheet" media = "(max-width:800px)"
            href = "css/tablet.css?version=<?php print time(); ?>"
            type = "text/css">
        <link rel = "stylesheet" media = "(max-width: 600px)"
            href = "css/phone.css?version=<?php print time(); ?>"
            type = "text/css">
        <?php
            //Including the constants from the lib folder
            include 'lib/constants.php';

            //Connecting to database
            print '<!-- make Database connections -->';
            require_once(LIB_PATH . '/Database.php');

            //Constant database reader and writer variables
            $thisDatabaseReader = new Database('jsaillia_reader', DATABASE_NAME);
            $thisDatabaseWriter = new Database('jsaillia_writer', DATABASE_NAME);

            //netId variable from the web
            $netId = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");

            //Creating necessary sql variables for a single check
            $adminSql = 'SELECT fldNetId FROM tblAdmins WHERE fldNetId = ?';
            $data = array($netId);

            $admin = $thisDatabaseReader -> select($adminSql, $data);

            //Bool for passed
            $adminAccess = empty($admin) ? false : true;

            //If access is granted, then return the page, if not, print this message
            if(!$adminAccess){
                die('<p>Sorry, you do not have admin priveleges</p>');
            }
            
            //Closing header
            print '</head>';

            //Starting the body html - the print line still breaks the site
            print'<body id = "' . PATH_PARTS['filename'] . '">';
            print '<!-- START OF BODY -->';

            print '<body>';
            print PHP_EOL;

            //Includes the header
            include 'header.php';
            print PHP_EOL;

            //Includes the nav
            include 'nav.php';
            print PHP_EOL;
        ?>