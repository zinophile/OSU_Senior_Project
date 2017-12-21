<?php

require_once __DIR__ . '/setup/setup.php';

// $login = getPageBuilderClass('Authentication/','Login');



$logout = getPageBuilderClass('Authentication/','Logout');

$logout->logout();

header('Location: login.php');