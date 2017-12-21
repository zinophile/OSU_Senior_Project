<?php

class Base {

   protected $DB;

   protected $Twig;
   
   protected $accessLevel;

   protected $baseTemplateArguments = [];
   
   protected $successMessage = [];
   
   protected $errorMessage = [];

   function __construct() {

      $this->buildDB();
      $this->buildTwig();

      $this->getAccessLevel();
   }

   protected function buildDB() {
      $this->DB = getHelperClass('database/','DB');
   }

   protected function buildTwig() {
      $loader = new Twig_Loader_Filesystem(PROJECT_PATH.'/Resources/templates/');
      $this->Twig = new Twig_Environment($loader);
   }
   
   protected function getAccessLevel() {

      $this->accessLevel = isset($_SESSION['accessLevel']) ? $_SESSION['accessLevel'] : 'NotSet';      
      $this->baseTemplateArguments['accessLevel'] = $this->accessLevel;

   }

   public function renderTemplate($template, $templateArguments = null) {

      if ($templateArguments) {
         $this->baseTemplateArguments = array_merge($this->baseTemplateArguments, $templateArguments);
      }

      if (!empty($this->baseTemplateArguments)) {
         echo $this->Twig->render($template, $this->baseTemplateArguments);
      }
      // else {
         // echo $this->Twig->render($template);
      // }

   }
   
   public function getDB() {
      return $this->DB;
   }

   public function addErrorMsg($message) {
      $this->errorMessage[] = $message;
   }

   public function addSuccessMsg($message) {
      $this->successMessage[] = $message;
   }
   
   public function getErrorMsg() {
      return $this->errorMessage;
   }

   public function getSuccessMsg() {
      return $this->successMessage;
   }
   
}
