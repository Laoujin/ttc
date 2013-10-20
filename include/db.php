<?php
define("DEBUG", true);
define("ANNOYING_DEBUG", false);

//http://api.frenoy.net/tabtapi-doc/index.html
Class MySqlWrapper
{
	var $Connection;
	var $Server = 'localhost';
	var $Login = 'root';
	var $Password = '';
	var $Database = 'ttc';
	
	var $Spelers = Array();
	
	function MySqlWrapper()
	{
		/*
		PhpMyAdmin:	https://dbadmin.one.com/
		Gebruikersnaam:	ttc_erembodegem
		Wachtwoord:	q2N4FQzT
		
		ftp.ttc-erembodegem.be
		Gebruikersnaam:	ttc-erembodegem.be
		Wachtwoord:	

		IMAP server:	
		imap.ttc-erembodegem.be
		
		POP3 server:	
		pop.ttc-erembodegem.be
		
		SMTP server:	
		send.one.com
		
		Webmail:	
		https://www.one.com/
		
		CO.NR:
		http://www.freedomain.co.nr/
		ttc-erembodegem : axendo7
		
		*/
		
		if (!DEBUG)
		{
			$this->Server = 'ttc-erembodegem.be.mysql';
			$this->Login = 'ttc_erembodegem';
			$this->Password = 'q2N4FQzT';
			$this->Database = 'ttc_erembodegem';
		}
		
		$this->Connection = mysql_connect($this->Server, $this->Login, $this->Password)
		or $this->Error('Failed to connect to host!');

		mysql_select_db($this->Database)
		or $this->Error('Failed to select database: '.$this->Database);
	}

	function Query($request, $update = "")
	{
		if ($update != "")
			$this->SetLastUpdate($update);
		
		if (ANNOYING_DEBUG)
		{
			echo "$request<br>";
		}
		
		if ($result = mysql_query($request)) return $result;
		if (DEBUG)
			$this->Error("Failed request:<br>$request<br><br>".mysql_error());
		else
			die("whoepsiekeesie :)");
	}
	
	function Escape($str)
	{
		return addslashes($str);
	}
	
	function Html($to_change)
	{
		if (!$to_change) return "&nbsp;";
		return stripslashes(htmlentities($to_change, ENT_QUOTES));
	}
	
	function ParseDate($date)
	{
		return substr($date, 6).substr($date, 3, 2).substr($date, 0, 2);
	}
	
	function ParseDateCombine($jaar, $maand, $dag)
	{
		return $jaar.str_pad($maand, 2, "0", STR_PAD_LEFT).str_pad($dag, 2, "0", STR_PAD_LEFT); 
	}
	
	function GetParams($keys = null)
	{
		$values = array();
		$where = "";
		if (isset($keys))
		{
			if (is_array($keys)) $keys = implode("', '", $keys);
			$where = "WHERE sleutel IN ('".$keys."')";
		}
		$result = $this->Query("SELECT sleutel, value FROM parameter $where");
		while ($record = mysql_fetch_array($result))
		{
			$values[$record['sleutel']] = $record['value'];
		}
		return $values;
	}
	
	function SetParam($key, $value)
	{
		$this->Query("UPDATE parameter SET value='".$this->Escape($value)."' WHERE sleutel='".$key."'");
	}
	
	function SetLastUpdate($value)
	{
		$value = date("d/m/Y H:i")." van ".$value;
		if (isset($_SESSION['user'])) $value .= " door ".$_SESSION['user'];
		$this->SetParam(PARAM_LASTUPDATE, $value);
	}
	
	function BuildSpelerCombo($name, $club, $default = -1, $hasEmpty = false)
	{
		// momenteel niet voorzien om de spelers van een andere club te tonen!
		$html = "<select name=$name id=$name style='width: 150px;'>";
		if ($hasEmpty) $html .= "<option value=0></option>";
		if ($club == CLUB_ID && count($this->Spelers) == 0)
		{
			$result = $this->Query("SELECT ID, NaamKort AS Naam FROM speler WHERE (ClubIdVTTL=$club OR ClubIdSporta=$club) AND Gestopt IS NULL ORDER BY Naam");
			while ($record = mysql_fetch_array($result))
				$this->Spelers[$record['ID']] = $record['Naam'];
		}
		
		foreach ($this->Spelers as $key => $value)
		{
			$html .= "<option".($key == $default ? " selected" : "")." value=".$key.">".$value."</option>";
		}
		return $html."</select>";
	}
	
	function BuildKlassementCombo($name, $comp, $default = '', $hasEmpty = true)
	{
		$html = "<select name=$name id=$name>";
		if ($hasEmpty) $html .= "<option value=0></option>";
		$result = $this->Query("SELECT Code FROM klassement WHERE Waarde$comp>0 ORDER BY Waarde$comp");
		while ($record = mysql_fetch_array($result))
		{
			$html .= "<option".($record['Code'] == $default ? " selected" : "")." value=".$record['Code'].">".$record['Code']."</option>";
		}
		return $html."</select>";
	}
	
	function GetClubLokaal($club, $alleLokalen)
	{
		$query = "SELECT Lokaal, Adres, Gemeente FROM clublokaal WHERE ClubID=$club";
		if (!$alleLokalen) $query .= " AND Hoofd=1";
		$return = array();
		
		$result = $this->Query($query);
		while ($record = mysql_fetch_array($result))
		{
			$desc = $record['Lokaal']."<br>".$record['Adres']."<br>".$record['Gemeente'];
			$return[] = $desc;
		}
		return $return;
	}

	function Close()
	{
		 mysql_close($this->Connection);
	}
	
	function ExecuteScalar($query)
	{
		$result = $this->Query($query);
		if ($record = mysql_fetch_array($result))
			return $record[0];
		else
			return "";
	}
	
	function Error($msg)
	{
		die($msg);
	}
}
?>