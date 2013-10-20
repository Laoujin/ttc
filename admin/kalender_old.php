<?php
	define("RELATIVE_PATH", "../");
	include_once '../include/header.php';
	
	$Page = 1;
	if (isset($_POST['Kalender1']) || isset($_POST['Kalender2']))
	{
		$fields = "datum, uur, beschrijving";
		$values = "'".ParseDate($_POST['Datum'])."', '".$_POST['Uur']."', '".ParseForInsert($_POST['Beschrijving'])."'";
		if (strlen($_POST['Week']))
		{
			$fields .= ", week";
			$values .= ", ".$_POST['Week'];
		}
		
		if (isset($_POST['Kalender1']))
		{
			if (!$_POST['PloegThuis'])
			{			
				$db->Query("INSERT INTO kalender ($fields) VALUES ($values)");
				$msg = "Toegevoegd aan de kalender!";
			}
			else
			{
				$Page = 2;
			}
		}
		else if (isset($_POST['Kalender2']))
		{
			$fields .= ", thuis, thuisclubploegid";
			$values .= ", ".($_POST['Thuis'] ? "1" : "0").", ".$_POST['PloegThuis'];
			
			// Thuis ClubID en PloegCode
			$result = $db->Query("SELECT ClubID, Code FROM clubploeg WHERE ID=".$_POST['PloegThuis']);
			$record = mysql_fetch_array($result);
			$fields .= ", ThuisClubID, ThuisPloeg";
			$values .= ", ".$record['ClubID'].", '".$record['Code']."'";
			
			// Uit
			if ($_POST['UitClubPloegID'])
			{
				$result = $db->Query("SELECT ClubID, Code FROM clubploeg WHERE ID=".$_POST['UitClubPloegID']);
				$record = mysql_fetch_array($result);
					
				$fields .= ", uitclubploegid, uitclubid, uitploeg";
				$values .= ", ".$_POST['UitClubPloegID'].", ".$record['ClubID'].", '".$record['Code']."'";
			}
			else
			{
				$fields .= ", uitclubid, uitploeg";
				$values .= ", ".$_POST['UitClubID'].", '".$_POST['UitPloeg']."'";
			}
			
			$db->Query("INSERT INTO kalender ($fields) VALUES ($values)");
			$msg = "Toegevoegd aan de kalender";
		}
	}
?>
<html>
<head>
<title>TTC Erembodegem</title>
<script language="javascript" type="text/javascript" src="../include/datetimepicker.js"></script>
</head>
<body>
<?php if ($msg) echo '<div color=red><b>'.$msg.'</b></div>'; ?>
<h1>Kalender Invoer</h1>
<form method=post>
<table width="50%" align=center border=1>
	<?php
	if ($Page == 1)
	{
		?>
		<tr>
			<td>Datum:</td>
			<td><input type=text name=Datum size="15"> <a href="javascript:NewCal('Datum','ddmmyyyy',false,24)"><img src="../img/cal.gif" width="16" height="16" border="0" alt="Kies een datum"></a> (DD/MM/JJJJ) <input type=text name=Uur value='20:00' size="5"></td>
		</tr>
		<tr>
			<td>Week:</td>
			<td><input type=text name=Week size="3"></td>
		</tr>
		<tr>
			<td>Thuis?</td>
			<td><input type=checkbox name=Thuis></td>
		</tr>
		<tr>
			<td>Ploeg Erembodegem:</td>
			<td>
				<select name=PloegThuis>
				<option value="">Geen match</option>
				<?php
				$result = $db->Query("SELECT clubploeg.ID, Competitie, Reeks, ReeksType, ReeksCode, Code
															FROM clubploeg INNER JOIN reeks ON ReeksID=reeks.ID
															WHERE ClubID=".CLUB_ID." ORDER BY Competitie DESC, Code");
				while ($record = mysql_fetch_array($result))
				{
					echo '<option value='.$record['ID'].'>'.$record['Competitie'].' '.$record['Code'].'</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Beschrijving:</td>
			<td><input type=text name=Beschrijving value="Training"> (Bij geen match)</td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type=submit name=Kalender1 value="Toevoegen"></td>
		</tr>
	<?php
	}
	else if ($Page == 2)
	{
		?>
		<input type=hidden name=Datum value="<?php echo $_POST['Datum']?>">
		<input type=hidden name=Uur value="<?php echo $_POST['Uur']?>">
		<input type=hidden name=Week value="<?php echo $_POST['Week']?>">
		<input type=hidden name=Beschrijving value="<?php echo $_POST['Beschrijving']?>">
		<input type=hidden name=PloegThuis value="<?php echo $_POST['PloegThuis']?>">
		<input type=hidden name=Thuis value="<?php echo $_POST['Thuis']?>">
		<tr>
			<td>Tegenstander Competitie:</td>
			<td>
				<select name=UitClubPloegID>
				<option value="">Selecteer bij competitie match</option>
				<?php
				$result = $db->Query("SELECT ReeksID FROM clubploeg WHERE ID=".$_POST['PloegThuis']);
				if ($record = mysql_fetch_array($result))
				{
					$result = $db->Query("SELECT clubploeg.ID, club.Naam, clubploeg.Code
																FROM club INNER JOIN clubploeg ON club.ID=ClubID
															  WHERE ReeksID=".$record['ReeksID']." AND clubploeg.ID<>".$_POST['PloegThuis']."
															  ORDER BY club.Naam, clubploeg.Code");
					while ($record = mysql_fetch_array($result))
					{
						echo '<option value='.$record['ID'].'>'.$record['Naam'].' '.$record['Code'].'</option>';
					}
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Tegenstander Ander:</td>
			<td>
				<select name=UitClubID>
				<option value="">Selecteer bij andere match</option>
				<?php
				$result = $db->Query("SELECT ID, Naam FROM club ORDER BY Naam");
				while ($record = mysql_fetch_array($result))
				{
					echo '<option value='.$record['ID'].'>'.$record['Naam'].' '.$record['Code'].'</option>';
				}
				?>
				</select> <input type=textbox size=2 name=UitPloeg>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type=submit name=Kalender2 value="Toevoegen"></td>
		</tr>
		<?php
	}
?>
</table>
</form>


<h1>Huidige Kalender</h1>
<table width=75% align=center border=1 cellpadding=2 cellspacing=2>
	<tr>
		<td width="5%">Week</td>
		<td width="10%">Dag</td>
		<td width="10%">Datum</td>
		<td width="5%">Uur</td>
		<td width="20%">Competitie</td>
		<td width="20%">Thuis</td>
		<td width="20%">Uit</td>
		<td width="10%">Uitslag</td>
	</tr>
	<?php
	$result = $db->Query(
		 "SELECT Week, TIME_FORMAT(Uur, '%k:%i') AS Uur, DATE_FORMAT(Datum, '%d/%m/%Y') AS FDatum, Beschrijving, UitPloeg, DAYOFWEEK(Datum) AS Dag
			, ThuisClubPloegID, clubthuis.Naam AS ThuisNaam, ThuisPloeg, Competitie, Reeks, ReeksType, ReeksCode, Jaar
			, UitClubPloegID, clubuit.Naam AS UitNaam, DAYOFYEAR(Datum)-DAYOFYEAR(NOW()) AS Vandaag, Thuis, WEEK(Datum) AS JaarWeek
			FROM kalender
			LEFT JOIN clubploeg thuis ON ThuisClubPloegID=thuis.ID
			LEFT JOIN reeks ON thuis.ReeksID=reeks.ID
			LEFT JOIN club clubthuis ON ThuisClubID=clubthuis.ID
			LEFT JOIN club clubuit ON UitClubID=clubuit.ID
			WHERE YEARWEEK(Datum) >= YEARWEEK(NOW())
			ORDER BY Datum");
			
	while ($record = mysql_fetch_array($result))
	{
		$class = (!$record['Vandaag']) ? "background: #F0F0F0" : "background: #FFFFFF";
		if ($record['Thuis'])
		{
			$thuis = "Thuis";
			$uit = "Uit";
		}
		else
		{
			$thuis = "Uit";
			$uit = "Thuis";
		}
		if ($JaarWeek != $record['JaarWeek'])
		{
			if ($JaarWeek) echo "<tr><td colspan=8>&nbsp;</td></tr>";
			$JaarWeek = $record['JaarWeek'];
		}
		
		?>
		<tr style="<?php echo $class?>">
			<td><?php echo nbsp($record['Week'])?></td>
			<td><?php echo DisplayDay($record['Dag'])?></td>
			<td><?php echo $record['FDatum']?></td>
			<td><?php echo ($record['Uur'] == "20:00" ? $record['Uur'] : "<b>".$record['Uur']."</b>" ) ?></td>
			<?php if ($record['ThuisClubPloegID']) { ?>
				<td><?php echo DisplayReeks($record)?></td>
				<td><?php echo DisplayPloeg($record, $thuis)?></td>
				<td><?php echo DisplayPloeg($record, $uit)?></td>
				<td>&nbsp;</td>
			<?php } else { ?>
				<td colspan=4><?php echo $record['Beschrijving']?></td>
			<?php } ?>
		</tr>
		<?php
	}
	?>
</table>
</body>
</html>
<?php
	function DisplayPloeg(& $record, $locatie)
	{
		if ($locatie == "Thuis")
			return "<a href=ploeg.php?id=".$record[$locatie.'ClubPloegID'].'>'.$record[$locatie.'Naam'].' '.$record[$locatie.'Ploeg'].'</a>';
		else
			return $record[$locatie.'Naam'].' '.$record[$locatie.'Ploeg'];
	}
	
	function nbsp($var)
	{
		return !strlen($var) ? "&nbsp;" : $var;
	}

	include_once '../include/footer.php';
?>