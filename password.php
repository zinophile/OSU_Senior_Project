<?php

require_once __DIR__ . '/setup/setup.php';


$passwordAuth = getPageBuilderClass('Authentication/','Password');


$messages = [];

$email = request('email');
$password = request('password');
$fromactivation = request('fromactivation');
$fromforgot = request('fromforgot');
$resetkey = request('resetkey');

$hideEmailPassword = $fromactivation || $fromforgot;

// echo "Hello!";

// NEED TO ADD 'FORGOT PASSWORD' Functionality


if (request('resetpassword')) {

   $newpassword = request('newpassword');
   $verifynewpassword = request('verifynewpassword');

   if ($fromforgot) {

      if (
         $passwordAuth->resetForgottenPassword(
            $email,
            $resetkey,
            $newpassword,
            $verifynewpassword      
         )
      ) {
         $messages = $passwordAuth->getSuccessMsg();
         header( "refresh:2; url=index.php"); 
      }
      else {
         $messages = $passwordAuth->getErrorMsg();
      }
   }
   else {
      if ($passwordAuth->changePassword(
         $email,
         $password,
         $newpassword,
         $verifynewpassword
      )) {
         $messages = $passwordAuth->getSuccessMsg();
         header( "refresh:2; url=index.php"); 
      }
      else {
         $messages = $passwordAuth->getErrorMsg();
      }
      
   }
   
   
}




$passwordAuth->renderTemplate(
   'password.html',
   array(
      'messages' => $messages,
      'email' => $email,
      'password' => $password,
      'hideemailpassword'=> $hideEmailPassword,
      'fromactivation' => $fromactivation,
      'fromforgot'=> $fromforgot,
      'resetkey' => $resetkey
   )
);

//http://web.engr.oregonstate.edu/~kurakuls/seniorproject/password.php?fromforgot=true&email=kurakuls+activation@oregonstate.edu&password=3KiibpCzk7&resetkey=HHH34iSGbXg8DDW