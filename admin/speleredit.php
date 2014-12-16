<?php
	include_once 'include.php';
	define("SPELERS", "spelers");
	include_once '../include/header.php';
	
	if (!$security->Spelers() || !is_numeric($_GET['id']))
		header('Location: index.php');
	
	$id = (int) $_GET['id']; 
	if (isset($_POST['submitted'])) 
	{ 
		foreach($_POST AS $key => $value) 
		{ 
			$_POST[$key] = mysql_real_escape_string($value);
		} 
		$sql = "UPDATE `speler` SET  `LinkKaartVTTL` =  '{$_POST['LinkKaartVTTL']}' ,  `Stijl` =  '{$_POST['Stijl']}' ,  `BesteSlag` =  '{$_POST['BesteSlag']}' ,  `ComputerNummerVTTL` =  '{$_POST['ComputerNummerVTTL']}' ,  `LidNummerVTTL` =  '{$_POST['LidNummerVTTL']}' ,  `Adres` =  '{$_POST['Adres']}' ,  `Gemeente` =  '{$_POST['Gemeente']}' ,  `Tel` =  '{$_POST['Tel']}' ,  `GSM` =  '{$_POST['GSM']}' ,  `Email` =  '{$_POST['Email']}' 
				`ComputerNummerSporta` =  '{$_POST['ComputerNummerSporta']}' ,  `LidNummerSporta` =  '{$_POST['LidNummerSporta']}' `LinkKaartSporta` =  '{$_POST['LinkKaartSporta']}'   WHERE `id` = '$id' "; 
		$db->Query($sql); 
	}
	$row = mysql_fetch_array($db->Query("SELECT * FROM `speler` WHERE `id` = '$id' ")); 

	include_once 'admin_start.php';
?>
<script type="text/javascript">
//$(document).ready(function() {
//	});
</script>

<h1><?= stripslashes($row['Naam']) ?></h1>
<form method=post>
<table width="100%" align=center class="maintable">
	<tr><th colspan='2'>Algemeen</th></tr>
	<tr><th width='25%'>Stijl</th><td width='75%'><input type='text' name='Stijl' value='<?= stripslashes($row['Stijl']) ?>' /> </td></th>
	<tr><th>BesteSlag</th><td><input type='text' name='BesteSlag' value='<?= stripslashes($row['BesteSlag']) ?>' /> </td></th>
	<tr><th>Adres</th><td><input type='text' name='Adres' value='<?= stripslashes($row['Adres']) ?>' /> </td></th>
	<tr><th>Gemeente</th><td><input type='text' name='Gemeente' value='<?= stripslashes($row['Gemeente']) ?>' /> </td></th>
	<tr><th>Tel</th><td><input type='text' name='Tel' value='<?= stripslashes($row['Tel']) ?>' /> </td></th>
	<tr><th>GSM</th><td><input type='text' name='GSM' value='<?= stripslashes($row['GSM']) ?>' /> </td></th>
	<tr><th>Email</th><td><input type='text' name='Email' value='<?= stripslashes($row['Email']) ?>' /> </td></th>

	<tr><th colspan='2'>VTTL</th></tr>
	<tr><th>ComputerNummerVTTL</th><td><input type='text' name='ComputerNummerVTTL' value='<?= stripslashes($row['ComputerNummerVTTL']) ?>' /> </td></th>
	<tr><th>LidNummerVTTL</th><td><input type='text' name='LidNummerVTTL' value='<?= stripslashes($row['LidNummerVTTL']) ?>' /> </td></th>
	<tr><th>LinkKaartVTTL</th><td><input type='text' name='LinkKaartVTTL' value='<?= stripslashes($row['LinkKaartVTTL']) ?>' /> </td></th>

	<tr><th colspan='2'>Sporta</th></tr>
	<tr><th>ComputerNummerSporta</th><td><input type='text' name='ComputerNummerSporta' value='<?= stripslashes($row['ComputerNummerSporta']) ?>' /> </td></th>
	<tr><th>LidNummerSporta</th><td><input type='text' name='LidNummerSporta' value='<?= stripslashes($row['LidNummerSporta']) ?>' /> </td></th>
	<tr><th>LinkKaartSporta</th><td><input type='text' name='LinkKaartSporta' value='<?= stripslashes($row['LinkKaartSporta']) ?>' /> </td></th>
	
	<tr>
		<td colspan=2 align=center><input type='submit' value='Editeer speler' /><input type='hidden' value='1' name='submitted' /></td>
	</tr>
</table>
</form>

<?php
	include_once 'admin_end.php';
?>