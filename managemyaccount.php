<?php

   
require_once __DIR__ . '/setup/setup.php';


$managemyaccount = getPageBuilderClass('Authentication/','ManageMyAccount');

$messages = [];

$userName = $managemyaccount->getUserName();
$firstname = $userName[0]['firstname'];
$lastname = $userName[0]['lastname'];

$signature = $managemyaccount->getUserSignature();

$updateSuccess = false;

if (request('updateaccount')) {
   $firstname = request('firstname') == '' ? $firstname : request('firstname');
   $lastname = request('lastname') == '' ? $lastname : request('lastname');

   if ($managemyaccount->updateaccount($firstname, $lastname)) {
      $messages = $managemyaccount->getSuccessMsg();
      $updateSuccess = true;
   }
   else {
      $messages = $managemyaccount->getErrorMsg();
   }

}

if (request('uploadsignature')) {

   if ($managemyaccount->uploadSignature()) {
      $messages = $managemyaccount->getSuccessMsg();
      $updateSuccess = true;
   }
   else {
      $messages = $managemyaccount->getErrorMsg();
   }

}

if ($updateSuccess) {
   header("refresh:1; url=managemyaccount.php");
}

$managemyaccount->renderTemplate(
   'managemyaccount.html',
   array(
      'messages' => $messages,
      'firstname' =>$firstname,
      'lastname' => $lastname,
      'username' => $_SESSION['username'],
      'signature' => $signature
   )
);