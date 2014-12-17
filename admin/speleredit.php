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

		$LidNummerSporta = is_numeric($_POST['LidNummerSporta']) ? "'".$_POST['LidNummerSporta']."'" : "NULL";
		$ComputerNummerVTTL = is_numeric($_POST['ComputerNummerVTTL']) ? ".".$_POST['ComputerNummerVTTL']."'" : "NULL";

		$sql = "UPDATE `speler` SET `LinkKaartVTTL`='{$_POST['LinkKaartVTTL']}' , `Stijl`='{$_POST['Stijl']}' , `BesteSlag`='{$_POST['BesteSlag']}' , 
				`ComputerNummerVTTL`=$ComputerNummerVTTL, `Adres`='{$_POST['Adres']}' , `Gemeente`='{$_POST['Gemeente']}' , 
				`GSM`='{$_POST['GSM']}' , `Email`='{$_POST['Email']}', `LidNummerSporta`=$LidNummerSporta, 
				`LinkKaartSporta`='{$_POST['LinkKaartSporta']}' WHERE `id`='$id'"; 

		$db->Query($sql); 
		$db->SetLastUpdate(SPELERS);

		header('Location: spelers.php');
	}
	$row = mysql_fetch_array($db->Query("SELECT * FROM `speler` WHERE `id` = '$id' ")); 

	include_once 'admin_start.php';
?>

<h1><?= stripslashes($row['Naam']) ?></h1>
<form method="post">
<table width="100%" align=center class="maintable">
	<tr><th colspan='2'>Algemeen</th></tr>
	<tr><th width='25%'>Stijl</th><td width='75%'><input type='text' name='Stijl' value="<?= htmlspecialchars($row['Stijl']) ?>" /> </td></th>
	<tr><th>BesteSlag</th><td><input type='text' name='BesteSlag' value="<?= htmlspecialchars($row['BesteSlag']) ?>" /> </td></th>
	<tr><th>Adres</th><td><input type='text' name='Adres' value="<?= htmlspecialchars($row['Adres']) ?>" /> </td></th>
	<tr><th>Gemeente</th><td><input type='text' name='Gemeente' value="<?= htmlspecialchars($row['Gemeente']) ?>" /> </td></th>
	<tr><th>GSM</th><td><input type='text' name='GSM' value="<?= htmlspecialchars($row['GSM']) ?>" /> </td></th>
	<tr><th>Email</th><td><input type='text' name='Email' value="<?= htmlspecialchars($row['Email']) ?>" /> </td></th>

	<tr><th colspan='2'>VTTL</th></tr>
	<tr><th>ComputerNummerVTTL</th><td><input type='text' name='ComputerNummerVTTL' value="<?= htmlspecialchars($row['ComputerNummerVTTL']) ?>" /> </td></th>
	<tr><th>LinkKaartVTTL</th><td><input type='text' name='LinkKaartVTTL' value="<?= htmlspecialchars($row['LinkKaartVTTL']) ?>" /> </td></th>

	<tr><th colspan='2'>Sporta</th></tr>
	<tr><th>LidNummerSporta</th><td><input type='text' name='LidNummerSporta' value="<?= htmlspecialchars($row['LidNummerSporta']) ?>" /> </td></th>
	<tr><th>LinkKaartSporta</th><td><input type='text' name='LinkKaartSporta' value="<?= htmlspecialchars($row['LinkKaartSporta']) ?>" /> </td></th>
	
	<tr>
		<td colspan=2 align=center><input type='submit' value='Editeer speler' /><input type='hidden' value='1' name='submitted' /></td>
	</tr>
</table>
<a href="spelers.php">Terug naar overzicht</a>
</form>

<?php
	include_once 'admin_end.php';
?>