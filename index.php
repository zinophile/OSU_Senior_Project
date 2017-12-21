<?php

require_once __DIR__ . '/setup/setup.php';



// What will go here is the main menu template.

// NOTE:: Base will never be called directly. This is just to test.
$index = getPageBuilderClass('','Index');

// $base->randomFunction($data);
// $base->randomFunction2($data2);
// $base->randomFunction3($data3);




$index->renderTemplate('index.html');
