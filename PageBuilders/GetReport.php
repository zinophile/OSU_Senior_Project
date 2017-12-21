<?php
require_once(PROJECT_PATH.'/PageBuilders/Base.php');
 
class GetReport extends Base {
	function __construct() {
		parent::__construct();
	}

public function getGraph1() {
   $query = <<<SQL
	SELECT
		region.region_name AS region,
		COUNT(award_record.award_record_ID) AS award_count
	FROM
		region
		INNER JOIN award_record ON region.region_ID=award_record.reg_ID
	GROUP BY
		region
SQL;

   $result = $this->DB->execute($query);
     
   $rows = array();
   $table = array();
   $table['cols'] = array(
      array('label' => 'Region', 'type' => 'string'),
      array('label' => "Number-of-Awards", 'type' => 'number')
   );

   foreach($result as $row) {
      $temp = array();
         // The following line will be used to slice the chart
      $temp[] = array('v' => (string) $row['region']); 
         // Values of the each slice
      $temp[] = array('v' => (int) $row['award_count']); 
      $rows[] = array('c' => $temp);
   }

   $table['rows'] = $rows;

      // convert data into JSON format
   $jsonTable = json_encode($table,true);
      
   return $jsonTable;
}


public function getGraph2() {
	$query = <<<SQL
	SELECT
		CONCAT(users.lastname, ",", users.firstname) AS user,
		COUNT(award_record.award_record_ID) AS award_count
	FROM users
		INNER JOIN award_record ON users.id=award_record.usr_ID
	GROUP BY
		user
SQL;

	$result = $this->DB->execute($query);

	$rows = array();
	$table = array();
	$table['cols'] = array(
		array('label' => 'User-Name', 'type' => 'string'),
		array('label' => 'Number-of-Awards-Given', 'type' => 'number')
	);
	
	foreach($result as $row) {
		$temp = array();
			// The following line will be used to slice the chart
		$temp[] = array('v' => (string) $row['user']); 
			// Values of the each slice
		$temp[] = array('v' => (int) $row['award_count']); 
      $rows[] = array('c' => $temp);
    }

	#$result->free();
	$table['rows'] = $rows;

		// convert data into JSON format
	$jsonTable = json_encode($table, true);
	
	return $jsonTable;
}

public function getGraph3() {
	$query = <<<SQL
	SELECT
		YEAR(award_record.award_create_date) AS award_year,
		COUNT(award_record.award_record_ID ) AS award_count
	FROM
		award_record
	GROUP BY
		award_year
SQL;

	$result = $this->DB->execute($query);
	
	$rows = array();
	$table = array();
	$table['cols'] = array(
		array('label' => 'Year', 'type' => 'string'),
		array('label' => 'Number-of-Awards-Given', 'type' => 'number')
	);
	
	foreach($result as $row) {
		$temp = array();
			// The following line will be used to slice the chart
		$temp[] = array('v' => (string) $row['award_year']); 
			// Values of the each slice
		$temp[] = array('v' => (int) $row['award_count']); 
      $rows[] = array('c' => $temp);
    }

	#$result->free();
	$table['rows'] = $rows;

		// convert data into JSON format
	$jsonTable = json_encode($table, true);
	
	return $jsonTable;
}

public function getGraph4() {
	$query = <<<SQL
	SELECT
		MONTHNAME(award_record.award_create_date) AS award_month, COUNT(award_record.award_record_ID ) AS award_count
	FROM
		award_record
	WHERE
		award_record.award_create_date BETWEEN '2016-01-01 01:00:00' AND '2016-12-31 01:00:00'	
	GROUP BY
		award_month
SQL;

	$result = $this->DB->execute($query);
	
	$rows = array();
	$table = array();
	$table['cols'] = array(
		array('label' => 'Month', 'type' => 'string'),
		array('label' => 'Number-of-Awards-Given', 'type' => 'number')
	);
	
	foreach($result as $row) {
		$temp = array();
			// The following line will be used to slice the Pie chart
		$temp[] = array('v' => (string) $row['award_month']); 
			// Values of the each slice
		$temp[] = array('v' => (int) $row['award_count']); 
      $rows[] = array('c' => $temp);
    }

	//$result->free();
	$table['rows'] = $rows;

		// convert data into JSON format
	$jsonTable = json_encode($table, true);
	
	return $jsonTable;
}
}