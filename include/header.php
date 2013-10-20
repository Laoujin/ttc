<?php
	session_start();

	define("CLUB_ID", 1);
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
	
	class SecurityManager
	{
		var $level;
		
		function SecurityManager($accessLevel)
		{
			if (is_numeric($accessLevel))
				$this->level = $accessLevel * 1;
			else
				$this->level = 0;
		}
		
		function Kalender()
		{
		 	return !(($this->level & TOEGANG_ADMIN) == 0);
		}
		
		function Params()
		{
			return !(($this->level & TOEGANG_ADMIN) == 0);
		}
		
		function Spelers()
		{
			return !(($this->level & TOEGANG_ADMIN) == 0);
		}
		
		function Ploegen()
		{
			return !(($this->level & TOEGANG_ADMIN) == 0);
		}
		
		function Verslag($spelerId = '')
		{
			if ($spelerId == '') return !(($this->level & TOEGANG_SPELER) == 0);
			return ((($this->level & TOEGANG_KAPITEIN) != 0 && $spelerId == $_SESSION['userid']) || (($this->level & TOEGANG_ADMIN) != 0));
		}
		
		function Any()
		{
			return !(($this->level & TOEGANG_SPELER) == 0);
		}
		
		function Admin()
		{
			return !(($this->level & TOEGANG_ADMIN) == 0);
		}
	}
	
	$security = new SecurityManager(isset($_SESSION['useraccess']) ? $_SESSION['useraccess'] : 0);
	//echo "security: ".$_SESSION['useraccess'];
?>