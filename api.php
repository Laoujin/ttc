<?php
define("RELATIVE_PATH", "");
include_once 'include/header.php';

//if (!$security->GeleideTraining())
//	die('oh noes');

switch ($_POST['action'])
{
case 'gtInschrijven':
	if (is_numeric($_POST['kalenderId']) && is_numeric($_POST['uur']) && is_numeric($_POST['spelerId']))
	{
		$db->Query('INSERT INTO training (KalenderId, SpelerId, Uur) VALUES ('
			.  $_POST['kalenderId'] . ', ' . $_POST['spelerId'] . ', ' . $_POST['uur']
			. ')');
	}

	break;
}

$db->Close();
?>