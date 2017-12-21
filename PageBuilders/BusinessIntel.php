 <?php

require_once(PROJECT_PATH.'/PageBuilders/Base.php');
 
class BusinessIntel extends Base {
	function __construct() {
		parent::__construct();
	}

//queries the DB to get report types for drop down menu in form
public function getReportType() {
	$query1 = <<<SQL
	SELECT
      *
	FROM
      reports
SQL;
	return $this->DB->execute($query1);
}

//queries the DB to get regions for drop down menu in form
public function getRegions() {
   $query = <<<SQL
   SELECT
      *
   FROM
      region
SQL;
		
		return $this->DB->execute($query);
   }
	
//queries the DB to get name of users giving awards for drop down menu in form
public function getNames() {
   $query = <<<SQL
   SELECT
		lastname, firstname
   FROM
		users
SQL;
		
		return $this->DB->execute($query);
   }
}
