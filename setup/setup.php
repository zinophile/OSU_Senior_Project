<?php

session_start();

define('PROJECT_PATH', realpath(dirname(__FILE__) . '/..'));

require_once(PROJECT_PATH.'/Resources/config/config.php');
require_once(PROJECT_PATH.'/Helpers/database/mysqlconn.php');
require_once(PROJECT_PATH.'/vendor/autoload.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if Default DB Connection has been created.
// If not then create connection.
if (!(MysqlConn::getConnection('Default'))) {

   $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
       or die("Error connecting to database server");

   MysqlConn::setConnection('Default', $link);

}

// Check if page can be accessed without being logged in.
function isAccessiblePage() {

   $unauthenticatedPages = array (
      'login.php',
      'activation.php'
   );

   return checkPages($unauthenticatedPages, false);
}

// Verifies that user has logged in. If not, then it will return to the main page
// and go through log in procedure
if (!isset($_SESSION['username']) && !isAccessiblePage()) {
   header('Location: login.php');
}

// checks if page is an admin only page
function isAdminPage() {

   $adminPages = [
      'adduser.php',
      'getreport.php',
      'businessintel.php',
      'viewusers.php',
      'edituser.php'
   ];

   return checkPages($adminPages);

}

// Checks is user is an admin user
function isAdminUser() {
   return isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] == 'Admin';
}

// checks is page is a normal user only page
function isNormalPage() {

   $normalPages = [
      'giveaward.php',
      'viewawards.php'
   ];

   return checkPages($normalPages);

}

// checks if user is logged in and a normal user
function isNormalUser() {
   return isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] == 'Normal';
}

// if user is on a page that they shouldn't be with their accessLevel, then it redirects it to their home page.
if ( (isAdminUser() && !isAdminPage()) || (isNormalUser() && !isNormalPage()) ) {
   header('Location: index.php');
}

// Verifies the page that the user is on
function checkPages($pages, $checkCommonPages=true) {

   if ($checkCommonPages) {
      $pages = array_merge($pages, ['managemyaccount.php', 'logout.php', 'index.php']);
   }

   $pages[] = 'password.php';

   $currentPage = basename($_SERVER['REQUEST_URI']);

   foreach($pages as $page) {
      if (strpos($currentPage, $page) !== false) {
         return true;
      }
   }

   return false;

}

// Wrapper to grab info from form submissions
function request($requestVar) {
   $returnValue = isset($_REQUEST[$requestVar]) ? $_REQUEST[$requestVar] : null;
   return $returnValue;
}

// Grab the proper page builder classes
function getPageBuilderClass($pathInPageBuilders, $pageBuilderClass) {
   require_once(PROJECT_PATH.'/PageBuilders/'. $pathInPageBuilders . $pageBuilderClass . '.php');
   return new $pageBuilderClass();
}

// Grab the proper helper classes
function getHelperClass($pathInHelpers, $helperClass) {
   require_once(PROJECT_PATH.'/Helpers/'. $pathInHelpers . $helperClass . '.php');
   return new $helperClass();
}

