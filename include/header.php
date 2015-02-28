<?php
	session_start();

	define("CLUB_ID", 1);
	define("CLUB_CODE_VTTL", "OVL134");
	define("CLUB_CODE_SPORTA", "4055");

	define("PARAM_LASTUPDATE", "updated");
	define("PARAM_STANDAARDUUR", "stduur");
	define("PARAM_KAL_WEEKS_OLD", "kalold");
	define("PARAM_KAL_WEEKS_NEW", "kalnew");
	define("PARAM_EMAIL", "email");
	define("PARAM_JAAR", "jaar");
	define("PARAM_KAARTLINK_VTTL", "linkKaartVTTL");
	define("PARAM_KAARTLINK_SPORTA", "linkKaartSporta");
	define("PARAM_RESLINK_VTTL", "linkResVTTL");
	define("PARAM_RANGLINK_VTTL", "linkRangVTTL");
	define("PARAM_RESLINK_SPORTA", "linkResSporta");
	define("PARAM_RANGLINK_SPORTA", "linkRangSporta");
	define("PARAM_FRENOY_URL_SPORTA", "frenoy_wsdlUrlSporta");
	define("PARAM_FRENOY_URL_VTTL", "frenoy_wsdlUrlVTTL");
	define("PARAM_FRENOY_LOGIN", "frenoy_login");
	define("PARAM_FRENOY_PASSWORD", "frenoy_password");

	define("TOEGANG_NONE", 0);
	define("TOEGANG_SPELER", 1);
	define("TOEGANG_KAPITEIN", 2);
	define("TOEGANG_ADMIN", 4);

	define ("VTTL", "VTTL");
	define ("SPORTA", "SPORTA");

	define("COM_VTTL", 1);
	define("COM_SPORTA", 2);

	define("MATCHEN_VTTL", 16);
	define("MATCHEN_SPORTA", 10);

	include_once RELATIVE_PATH.'include/db.php';
	include_once RELATIVE_PATH.'include/function.php';

	$db = new MySqlWrapper();

	function Login($db, $login, $md5)
	{
		$result = $db->Query("SELECT ID, NaamKort AS Naam, Toegang, Paswoord FROM speler WHERE ID=".$login." AND Paswoord=$md5");
		if ($record = mysql_fetch_array($result))
		{
			$_SESSION['user'] = $record['Naam'];
			$_SESSION['useraccess'] = $record['Toegang'];
			$_SESSION['userid'] = $record['ID'];

			$expire = time() + 60*60*24*30;
			setcookie("Paswoord", $record['Paswoord'], $expire, '/');
			setcookie("login", $record['ID'], $expire, '/');
			return "";
		}
		else
		{
			return "Foutief paswoord!";
		}
	}

	if (isset($_GET['uitloggen']))
	{
		$security = new SecurityManager(0);
		unset($_SESSION['user']);
		unset($_SESSION['useraccess']);
		unset($_SESSION['userid']);
		setcookie("Paswoord", "", 0, "/");
	}
	elseif (isset($_POST['paswoord']) && strlen($_POST['paswoord']) > 0 ||
			(!isset($_SESSION['userid']) && isset($_COOKIE['Paswoord']) && isset($_COOKIE['login']) && $_COOKIE['Paswoord'] != "" && $_COOKIE['login'] != ""))
	{
		if (isset($_POST['login'])) $login = $_POST['login'];
		else $login = $_COOKIE['login'];
		if (isset($_POST['paswoord'])) $check = "MD5('".$db->Escape($_POST['paswoord'])."')";
		else $check = "'".$db->Escape($_COOKIE['Paswoord'])."'";

		$msg = Login($db, $login, $check);
	}

	include_once RELATIVE_PATH.'include/security.php';

	$security = new SecurityManager(isset($_SESSION['useraccess']) ? $_SESSION['useraccess'] : 0);
?>