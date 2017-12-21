<?php

require_once __DIR__ . '/setup/setup.php';

$viewawards = getPageBuilderClass('','ViewAwards');

$messages = [];

$awardsFromUser = $viewawards->getUserGeneratedAwards();

// This is when the form has been submitted.
if (request('deleteawards')) {

   $awardIDs = request('awardIDs');

   if ($viewawards->deleteUserGeneratedAwards($awardIDs)) {
      $messages = $viewawards->getSuccessMsg();
      header("refresh:2; url=viewawards.php"); 
   }
   else {
      $messages = $viewawards->getErrorMsg();
   }

}


$viewawards->renderTemplate('viewawards.html',array('messages' => $messages, 'awardsFromUser'=>$awardsFromUser));