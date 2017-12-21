<?php

require_once __DIR__ . '/setup/setup.php';

$getreport = getPageBuilderClass('','GetReport');

$jsonTable1 = $getreport->getGraph1();
$jsonTable2 = $getreport->getGraph2();
$jsonTable3 = $getreport->getGraph3();
$jsonTable4 = $getreport->getGraph4();
//$messages = [];

	//Will dynamically generate this from DB:
//$awardsPerRegion = $getreport->getAwardsPerRegion();

$reportType = request('reporttype');
// This is when the "get report type" has been submitted
// if ($reportType == 'Awards by Region') {

// }

$getreport->renderTemplate('getreport.html',
	array('jsonTable1'=>$jsonTable1,
			'jsonTable2'=>$jsonTable2,
			'jsonTable3'=>$jsonTable3,
			'jsonTable4'=>$jsonTable4,
			'charttype'=>$reportType)
);