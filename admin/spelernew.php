<?php
	include_once 'include.php';
	define("SPELERS", "spelers");
	include_once '../include/header.php';

	if (!$security->Spelers())
		header('Location: index.php');

	if (isset($_POST['submitted'])) 
	{
		foreach($_POST AS $key => $value)
		{ 
			$_POST[$key] = mysql_real_escape_string($value);
		}

		$LidNummerSporta = is_numeric($_POST['LidNummerSporta']) ? "'".$_POST['LidNummerSporta']."'" : "NULL";
		$ComputerNummerVTTL = is_numeric($_POST['ComputerNummerVTTL']) ? "'".$_POST['ComputerNummerVTTL']."'" : "NULL";

		$rndPwd = createRandomPassword();
		$sql = "INSERT INTO speler (Naam, NaamKort, LinkKaartVTTL, Stijl, BesteSlag, ComputerNummerVTTL, Adres, Gemeente, GSM, Email, LidNummerSporta, LinkKaartSporta, Toegang, Paswoord) 
				VALUES ('{$_POST['Naam']}', '{$_POST['NaamKort']}', '{$_POST['LinkKaartVTTL']}', '{$_POST['Stijl']}', '{$_POST['BesteSlag']}',
				$ComputerNummerVTTL, '{$_POST['Adres']}', '{$_POST['Gemeente']}',  
				'{$_POST['GSM']}', '{$_POST['Email']}', $LidNummerSporta, '{$_POST['LinkKaartSporta']}', ".TOEGANG_SPELER.", MD5('".$rndPwd."'))"; 
		$db->Query($sql); 
		$db->SetLastUpdate(SPELERS);

		$mail = "Welkom bij TTC Erembodegem\n\n";
		$mail .= "Je account om wedstrijdverslagen in te geven op http://ttc-erembodegem.be is aangemaakt \n\n";
		$mail .= "Je login: ".$_POST['NaamKort']."\n";
		$mail .= "Je paswoord: $rndPwd \n(Je kan dit in de 'Ledenzone' op de site opnieuw aanpassen.)";

		$params = $db->GetParams(array(PARAM_EMAIL));
		mail($_POST['Email'], "Welkom bij TTC Erembodegem", $mail, "From:".$params[PARAM_EMAIL]);

		header('Location: spelers.php');
	}

	include_once 'admin_start.php';
?>

<h1>Nieuwe speler toevoegen</h1>
<form method="post">
<table width="100%" align=center class="maintable">
	<tr><th colspan='2'>Algemeen</th></tr>
	<tr><td colspan="2"><font size='-2'>Gebruik de spelerslijst om in te vullen of het nieuwe lid VTTL / Sporta zal spelen (zodat de indexen/volgnummers ook goed gezet worden voor de andere leden)</font></td></tr>
	<tr><th width='25%'>Achternaam Voornaam</th><td width='75%'><input type='text' name='Naam' /> </td></th>
	<tr><th>Naam (kort)</th><td width='75%'><input type='text' name='NaamKort' /><br>Bestaande namen: <?=$db->BuildSpelerCombo("emailLogin", CLUB_ID) ?></td></th>
	<tr><th>Stijl</th><td width='75%'><input type='text' name='Stijl' /> (Aanvaller, Verdediger of All-rounder) </td></th>
	<tr><th>BesteSlag</th><td><input type='text' name='BesteSlag' /> </td></th>
	<tr><th>Adres</th><td><input type='text' name='Adres' /> </td></th>
	<tr><th>Gemeente</th><td><input type='text' name='Gemeente' /> </td></th>
	<tr><th>GSM</th><td><input type='text' name='GSM' /> </td></th>
	<tr><th>Email</th><td><input type='text' name='Email' /> </td></th>

	<tr><th colspan='2'>VTTL</th></tr>
	<tr><th>ComputerNummerVTTL</th><td><input type='text' name='ComputerNummerVTTL' /> </td></th>
	<tr><th>LinkKaartVTTL</th><td><input type='text' name='LinkKaartVTTL' /> </td></th>

	<tr><th colspan='2'>Sporta</th></tr>
	<tr><th>LidNummerSporta</th><td><input type='text' name='LidNummerSporta' /> </td></th>
	<tr><th>LinkKaartSporta</th><td><input type='text' name='LinkKaartSporta' /> </td></th>
	
	<tr>
		<td colspan=2 align=center><input type='submit' value='Speler toevoegen' /><input type='hidden' value='1' name='submitted' /></td>
	</tr>
</table>
<a href="spelers.php">Terug naar overzicht</a>
</form>

<?php
	include_once 'admin_end.php';
?>