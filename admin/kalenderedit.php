<?php
	//Not linked anymore :)

	include_once 'include.php';
	define("KALENDER", "kalender");
	include_once '../include/header.php';
	
	$params = $db->GetParams(array(PARAM_STANDAARDUUR, PARAM_KAL_WEEKS_OLD, PARAM_KAL_WEEKS_NEW));
	
	
	
	include_once 'admin_start.php';
?>
<script type="text/javascript">

</script>
<form method=post>
<h1>Admin - kalender</h1>
<table width=75% align=center class="maintable">
	<tr>
		<th width="5%">Week</th>
		<th width="10%">Dag</th>
		<th width="10%">Datum</th>
		<th width="5%">Uur</th>
		<th width="20%">Competitie</th>
		<th width="20%">Thuis</th>
		<th width="20%">Uit</th>
		<th width="10%">Uitslag</th>
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
		if (!isset($JaarWeek) || $JaarWeek != $record['JaarWeek'])
		{
			if (isset($JaarWeek) && $JaarWeek) echo "<tr><td colspan=8>&nbsp;</td></tr>";
			$JaarWeek = $record['JaarWeek'];
		}
		
		?>
		<tr style="<?php echo $class?>">
			<td><?php echo $record['Week']?></td>
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
</form>
<?php
	include_once 'admin_end.php';
?>