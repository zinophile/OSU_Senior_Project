<?php

require_once(PROJECT_PATH.'/PageBuilders/Base.php');
 
class ViewAwards extends Base {

   function __construct() {
      parent::__construct();
   }

   public function getUserGeneratedAwards($username=null) {
      
      $username= isset($username) ? $username : $_SESSION['username'];
      
      $query = <<<SQL
SELECT
   award_record_ID,
   recipient_fname,
   recipient_lname,
   award_create_date,
   recipient_email,
   city,
   region_name,
   award_class
FROM
   award_record ar
   JOIN users u ON (ar.usr_ID=u.id)
   JOIN region reg ON (reg.region_ID=ar.reg_ID)
   JOIN award awd ON (awd.award_ID = ar.awd_ID)
WHERE
   u.username = ?
   AND ar.show_award = ?
SQL;

		return $this->DB->execute($query, array($username, 1));

   }

   // For the sake of business intelligence, we don't actually delete any awards.
   // We mark the column 'show_award' as 'false' and just hide it from the user who created the award.
public function deleteUserGeneratedAwards($awardIDs) {

      foreach($awardIDs as $awardID) {
         $query = <<<SQL
UPDATE
   award_record
SET
   show_award = ?
WHERE
   award_record_ID = ?
SQL;

         $this->DB->execute($query, array(0, $awardID));

         if ($this->DB->getLastErrno()===0) {
            $this->successMessage[] = "Awards Deleted";
            return true;
         }
         else {
            $this->errorMessage[] = "Awards Could Not Be Deleted";
            return false;
         }

      }

   }   
   
}