<?php

require_once(PROJECT_PATH.'/PageBuilders/Authentication/AuthenticationManager.php');

class Password extends AuthenticationManager {

   function __construct() {
      parent::__construct();
   }

   public function changePassword($email, $password, $newpassword, $verifynewpassword) {
      return $this->authenticator->changepass($email, $password, $newpassword, $verifynewpassword);
   }
   
   public function resetForgottenPassword($email, $resetkey, $newpass, $verifynewpass) {
      return $this->authenticator->resetpass($email, $resetkey, $newpass, $verifynewpass);
   }
}