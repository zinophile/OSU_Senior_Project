<?php

require_once __DIR__ . '/setup/setup.php';

$editUser = getPageBuilderClass('Authentication/','ViewEditUser');

$messages = [];

// Will Need to dynamically generate these
$accesslevels = $editUser->getAccessLevels();

if (!$userID = request('userID')) {
   header('Location: viewusers.php');
}

$user = $editUser->getUserInfo($userID);
$user = $user[0];
   
$updateSuccess = false;

// This is when the form has been submitted.
if (request('updateuser')) {
   
   $firstname = request('firstname') == '' ? $user['firstname'] : request('firstname');
   $lastname = request('lastname') == '' ? $user['lastname'] : request('lastname');
   $accessID = request('accesslevel') == '' ? $user['acc_ID'] : request('accesslevel');   

   if ($editUser->editUser($userID, $firstname, $lastname, $accessID)) {
      $messages = $editUser->getSuccessMsg();
      $updateSuccess = true;
   }
   else {
      $messages = $editUser->getErrorMsg();
   }

}

if ($updateSuccess) {
   header("refresh:1; url=edituser.php?userID=$userID");
}

$editUser->renderTemplate(
   'edituser.html',
   array(
      'messages' => $messages,
      'user'=> $user,
      'accessLevels' => $accesslevels
   )
);

