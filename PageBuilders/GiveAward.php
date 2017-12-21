 
 <?php

require_once(PROJECT_PATH.'/PageBuilders/Base.php');

class GiveAward extends Base {

	protected $giverFName;
	protected $giverLName;
	protected $giverEmail;
	protected $giverTitle;
	protected $recipientFName;
	protected $recipientLName;
	protected $recipientEmail;
	protected $awardType;
	protected $awardTYpeID;
	protected $awardDate;
	//protected $awardCity;
	protected $awardRegion;

	function __construct() {
		parent::__construct();
	}
	
	public function setGiverFName($giverFName) {
		$this->giverFName = $giverFName;
	}
	
	public function setGiverLName($giverLName) {
		$this->giverLName = $giverLName;
	}
	
	public function setGiverEmail($giverEmail) {
		$this->giverEmail = $giverEmail;
	}
	
	public function setGiverTitle($giverTitle) {
		$this->giverTitle = $giverTitle;
	}
	
	public function setRecipientFName($recipientFName) {
		$this->recipientFName = $recipientFName;
	}
	
	public function setRecipientLName($recipientLName) {
		$this->recipientLName = $recipientLName;
	}
	
	public function setRecipientEmail($recipientEmail) {
		$this->recipientEmail = $recipientEmail;
	}
	
	public function setAwardType($awardType) {
		$this->awardType = $awardType;
	}
	
	public function setAwardTypeID($awardTypeID) {
		$this->awardTypeID = (int)$awardTypeID;
	}

	public function setAwardDate($awardDate) {
		$this->awardDate = $awardDate;
	}
	
	// public function setAwardCity($awardCity) {
	// 	$this->awardCity = $awardCity;
	// }

		public function setAwardRegion($awardRegion) {
		$this->awardRegion = (int)$awardRegion;
	}

	//queries the DB to get possible award types for drop down menu in form
	public function getAwardType() {
		$query = <<<SQL
   		SELECT
      	award_ID, award_class
   		FROM
      	award
SQL;
		return $this->DB->execute($query);
	}

// 	//queries the DB to get possible award cities for drop down menu in form
// 	public function getAwardCities() {
// 		$query = <<<SQL
//    		SELECT
//       	city
//    		FROM
//       	region
// SQL;
// 		return $this->DB->execute($query);
// 	}

		//queries the DB to get possible award regions for drop down menu in form
	public function getAwardRegions() {
		$query = <<<SQL
   		SELECT
      	region_ID, region_name
   		FROM
      	region
SQL;
		return $this->DB->execute($query);
	}

	//queries the DB to get user (aka the giver) info
	public function getGiverInfo() {
		$query = <<<SQL
		SELECT
		firstname, lastname
		FROM
		users
		WHERE
		username = ?
SQL;
		return $this->DB->execute($query, array($_SESSION['username']));
	}

	//queries the DB to get user (aka the giver) info
	public function getAwardName() {
		$query = <<<SQL
		SELECT
		award_class
		FROM
		award
		WHERE
		award_ID = ?
SQL;
		return $this->DB->execute($query, array($this->awardTypeID));
	}
	//function to create the data CSV file
	//calls tempnam_sfx to create a .csv file
	//calls createAward which in turn calls emailAward
	//also calls saveAwardInfo to save the info in the database
	public function createCSV() {
		//Because form returns awardTypeID, need to get name for certificate
		$array = $this->getAwardName();
 		$this->setAwardType($array[0]['award_class']);

		//create column headers for .csv
		$columns = array('GiverFName', 'GiverLName', 'Title', 'Date', 'Type', 'RecFName', 'RecLName');      

		//create data array for .csv
		$awardValues = array($this->giverFName, $this->giverLName, $this->giverTitle, $this->awardDate, $this->awardType, $this->recipientFName, $this->recipientLName);
		
		//an array of arrays for the for each function to fputcsv the data into the file
		$csvData = array($columns, $awardValues);
		
		//naming the temp file with a .csv extension
		//$tempfname = $this->tempnam_sfx(sys_get_temp_dir(), ".csv");
		
		$file = PROJECT_PATH.'/Helpers/award/data.csv';
		//http://wordpress.stackexchange.com/questions/179791/how-to-create-a-csv-on-the-fly-and-send-as-an-attachment-using-wp-mail
		//opening .csv file
		$fd = fopen($file, 'w');
		if($fd === FALSE) {
			die('Failed to open file');
		}

		//putting column names and award values into the .csv
    	foreach($csvData as $row) {
        	fputcsv($fd, $row);
    	}

    	//rewind so the file reads from beginning when calling pdf latex, then close
    	rewind($fd);
    	fclose($fd);
		
		//createAward calls the emailAward function
		$this->createAward();
	
		$this->saveAwardInfo();
	}
	
	//to create a temp .csv filename
	// public function tempnam_sfx($path, $suffix) {
	// 	do {
	// 		$file = $path."/".mt_rand().$suffix;
	// 		$fp = @fopen($file, 'x');
	// 	} while (!$fp);

	// 	fclose($fp);
	// 	return $file;
	// }

	//function to actually create the pdf award from the latex script
	public function createAward(){
		
		$awardDir = PROJECT_PATH.'/Helpers/award';
		$awardHandle = PROJECT_PATH.'/Helpers/award/certificate';
		
		chdir($awardDir);
		$command = shell_exec("/usr/bin/pdflatex certificate.tex");
		
		$pdf = PROJECT_PATH.'/Helpers/award/certificate.pdf';
		//$this->showPDF($pdf);
		$this->emailAward($pdf);
	}
	
	// //experiment function to show the PDF
	// //can't work until .tex compiles & PDF is created
	//  public function showPDF($pdf){
	// 	header('Content-type: application/pdf');
	// 	header('Content-Disposition: inline; filename="certificate.pdf"');
	// 	header('Content-Transfer-Encoding:binary');
	// 	header('Accept-Ranges: bytes');
	// 	@readfile($pdf);
	// }

	//function to construct email with PDF of award attached
	public function emailAward($award) {
		//from http://stackoverflow.com/questions/10606558/how-to-attach-pdf-to-email-using-php-mail-function
		$mail = new PHPMailer();
		
		$body = "Congratulations $this->recipientFName $this->recipientLName!!\n
				$this->giverFName $this->giverLName has presented you with an award for \n
				$this->awardType . See the attached document to view it.\n
				Thank you for your continued hard work!";
		
		$mail->AddReplyTo("$this->giverEmail", "$this->giverFName $this->giverLName");
		$mail->SetFrom("$this->giverEmail", "$this->giverFName $this->giverLName");		
		$mail->AddAddress("$this->recipientEmail", "$this->recipientFName $this->recipientLName");
		
		$mail->Subject = "$this->giverFName $this->giverLName has given you an award!";
		$mail->MsgHTML($body);
		
		$mail->AddAttachment($award);
		
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent.";
		}
	}

	//function to save the award info to call it later when managing awards	
	public function saveAwardInfo(){
		//query DB for user ID which is foreign key in award_record table
		$userIDquery = <<<SQL
		SELECT
		id
		FROM
		users
		WHERE
		username = ?
SQL;
		
		$userID = $this->DB->execute($userIDquery, array($_SESSION['username']))[0]['id'];

		$Insertquery = <<<SQL
		INSERT INTO 
		award_record (recipient_lname, recipient_fname, usr_ID, awd_ID, reg_ID, recipient_email)
		VALUES
		(?, ?, ?, ?, ?, ?)
SQL;

		return $this->DB->execute(
         $Insertquery,
         array(
            $this->recipientLName,
            $this->recipientFName,
            $userID,
            $this->awardTypeID,
            $this->awardRegion,
            $this->recipientEmail
         )
      );
	}
}

