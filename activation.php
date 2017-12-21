<?php

require_once __DIR__ . '/setup/setup.php';


$activation = getPageBuilderClass('Authentication/','Activation');

$messages = [];

$key = request('key');



if (request('useractivation')) {
   $email = request('email');
   // $email = urlencode('saie@test.com');
   
   $password = request('password');
   // $password = sdfwenof4930x;

   if ($activation->activate($email, $password, request('useractkey'))) {
      // $messages = $activation->getSuccessMsg();
      $email = urlencode($email);
      header( "Location: password.php?fromactivation=true&email=$email&password=$password" );
   }
   else {
      $messages = $activation->getErrorMsg();
   }
}



 
$activation->renderTemplate('activation.html',array('messages' => $messages, 'key' => $key));