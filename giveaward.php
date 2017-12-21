<?php

require_once __DIR__ . '/setup/setup.php';

$award = getPageBuilderClass('','GiveAward');

//Will need to dynamically generate this from DB:
$awardTypes = $award->getAwardType();
// $awardCities = $award->getAwardCities();
$awardRegions = $award->getAwardRegions();
$giverInfo = $award->getGiverInfo();

//These are for the user session info (aka person giving award)
//to pre-populate in form
$email = $_SESSION['username'];
$firstname = $giverInfo[0]['firstname'];
$lastname = $giverInfo[0]['lastname'];

//This is when the form has been submitted:
if(request('giveaward')) {
	$award->setGiverFName($firstname);
	$award->setGiverLName($lastname);
	$award->setGiverEmail($email);
	$award->setGiverTitle(request('title'));
	$award->setRecipientFName(request('rFName'));
	$award->setRecipientLName(request('rLName'));
	$award->setRecipientEmail(request('remail'));
	$award->setAwardTypeID(request('awardType'));
	$award->setAwardDate(request('date'));
	//$award->setAwardCity(request('awardCity'));
	$award->setAwardRegion(request('awardRegion'));

	$award->createCSV();
}

$award->renderTemplate('giveaward.html',
	array(
			'firstname'=>$firstname, 
			'lastname'=>$lastname, 
			'email'=>$email, 
			'awardTypes'=>$awardTypes,
			'awardRegions'=>$awardRegions			
	)
);
