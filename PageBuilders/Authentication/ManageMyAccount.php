<?php

require_once(PROJECT_PATH.'/PageBuilders/Authentication/AuthenticationManager.php');

class ManageMyAccount extends AuthenticationManager {

   protected $imageManager;
   protected $mimeTypes = ['gif', 'png', 'jpeg'];
   
   function __construct() {
      parent::__construct();
   }

   public function getUserName() {

$query = <<<SQL
   SELECT
      firstname,
      lastname
   FROM
      users
   WHERE
      username = ?
SQL;

      return $this->DB->execute($query, array($_SESSION['username']));

   }

   public function updateaccount($firstname, $lastname) {

$query = <<<SQL
   UPDATE
      users
   SET
      firstname = ?,
      lastname = ?
   WHERE
      username = ?
SQL;

      $this->DB->execute($query, array($firstname, $lastname, $_SESSION['username']));
      
      if ($this->DB->getLastErrno() === 0) {
         $this->addSuccessMsg('Update Successful');
         return true;
      }
      else {
         $this->addErrorMsg('Update failed. Error: '. $this->DB->getLastError());
         return false;
      }

   }

   public function uploadSignature() {
      
      
      if($currentSignature = $this->getUserSignature()) {
         unlink($currentSignature);
      }

      $imageUploadSuccess = false;

      $this->imageManager = new Bulletproof\Image($_FILES);
      if($this->imageManager['signature']){

         $this->imageManager->setName($_SESSION['username']); 

         // define min/max size limits for upload (size in bytes) 
         $this->imageManager->setSize(1, 500000); 

         // define acceptable mime types
         $this->imageManager->setMime($this->mimeTypes);  

         // set max width/height limits (in pixels)
         // $this->imageManager->setDimension($width, $height); 
         $this->imageManager->setDimension(2000, 2000); 

         // pass name (and optional chmod) to create folder for storage
         $this->imageManager->setLocation(PROJECT_PATH.'/Resources/public/images/signatures/');  
         
         if ($this->imageManager->upload()) {
            $this->successMessage[] = "Signature Uploaded";
            $imageUploadSuccess =  true;
         }
         else {
            $this->errorMessage[] = $this->imageManager['error'];

         }
      }
      
      return $imageUploadSuccess;

   }
   
   
   public function getUserSignature() {

      foreach($this->mimeTypes as $type) {
         $filename = "Resources/public/images/signatures/".$_SESSION['username'].".".$type;
         if (file_exists($filename)) {
            return $filename;
         }
      }

      return false;

   }
   
}