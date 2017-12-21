<?php

require_once __DIR__ . '/setup/setup.php';

$viewEditUser = getPageBuilderClass('Authentication/','ViewEditUser');
$messages = [];

$users = $viewEditUser->getAllUsers();

// This is when the form has been submitted.
if (request('deleteusers')) {

   $userIDs = request('userids');

   if ($viewEditUser->deleteUsers($userIDs)) {
      $messages = $viewEditUser->getSuccessMsg();
      header("refresh:2; url=viewusers.php"); 
   }
   else {
      $messages = $viewEditUser->getErrorMsg();
   }

}

$viewEditUser->renderTemplate('viewusers.html',array('messages' => $messages, 'users'=>$users));
