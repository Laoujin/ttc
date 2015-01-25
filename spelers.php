<?php
	define("LIJST_STD", 0);
	define("LIJST_VTTL", 1);
	define("LIJST_SPORTA", 2);

	define("LIJST_VTTL_DESC", "VTTL overzicht");
	define("LIJST_SPORTA_DESC", "Sporta overzicht");

	if (!isset($_GET['display']) || !is_numeric($_GET['display']) || $_GET['display'] == 0)
	{
		$display = LIJST_STD;
		define("PAGE_TITLE", "Overzicht spelers");
		define("PAGE_DESCRIPTION", "Alle competitiespelers en recreanten ingeschreven bij TTC Erembodegem.");
	}
	else
	{
		$display = $_GET['display'];
		define("PAGE_TITLE", $display == LIJST_VTTL ? "Overzicht spelers VTTL" : "Overzicht spelers Sporta");
		define("PAGE_DESCRIPTION", "De TTC Erembodegem sterktelijst van de ".($display == LIJST_VTTL ? "VTTL" : "Sporta")." competitie.");
	}

	include_once 'include/menu_start.php';
	$params = $db->GetParams(array(PARAM_KAARTLINK_VTTL, PARAM_KAARTLINK_SPORTA));

	function PrintCompetitie(& $record, $comp, $link)
	{
		if ($record['ClubId'.$comp] == '') echo "<td>&nbsp;</td>";
		else
		{
			$klassement = $record['Klassement'.$comp];
			if (strlen($klassement) == 1) $klassement .= "&nbsp; &nbsp;";
			echo "<td align=center>".$klassement."&nbsp; <a href='".sprintf($link, $record['LinkKaart'.$comp])."' target=_blank><img src=img/linkkaart.png class=icon title='Officiële kaart' tag='Officiële kaart'></a></td>";
		}
	}
?>
<h1>Spelers</h1>

<table width='1%' class="maintable">
	<tr>
		<td<?php echo ($display == LIJST_STD ? " class=rowselected" : "")?> nowrap>
			<a href=spelers.php?display=<?php echo LIJST_STD?>>Cluboverzicht</a>
			&nbsp;
		</td>
		<td<?php echo ($display == LIJST_VTTL ? " class=rowselected" : "")?> nowrap>
			<a href=spelers.php?display=<?php echo LIJST_VTTL?>><?php echo LIJST_VTTL_DESC ?></a>
			&nbsp;
		</td>
		<td<?php echo ($display == LIJST_SPORTA ? " class=rowselected" : "")?> nowrap>
			<a href=spelers.php?display=<?php echo LIJST_SPORTA?>><?php echo LIJST_SPORTA_DESC ?></a>
			&nbsp;
		</td>
	</tr>
</table>

<br>

<?php if ($display == LIJST_VTTL) { ?>
<table width='100%' class="maintable">
	<tr>
		<th width='5%'>Volgnummer</th>
		<th width='5%'>Index</th>
		<th width='5%'>Lidnummer</th>
		<th width='20%'>Naam</th>
		<th width='10%'>VTTL</th>
		<th width='10%'>Sporta</th>
		<th width='10%'>Stijl</th>
		<th width='35%'>Beste slag(en)</th>
	</tr>
	<?php
	$result = $db->Query("SELECT ID, Naam, LinkKaartVTTL, LinkKaartSporta, KlassementSporta, KlassementVTTL, Stijl, BesteSlag, ClubIdVTTL, ClubIdSporta, VolgnummerVTTL, IndexVTTL, VolgnummerSporta, IndexSporta, ComputerNummerVTTL
						FROM speler WHERE Gestopt IS NULL ORDER BY ClubIdVTTL DESC, VolgnummerVTTL");

	while ($record = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>".$record['VolgnummerVTTL']."</td>";
		echo "<td>".$record['IndexVTTL']."</td>";
		echo "<td>".$record['ComputerNummerVTTL']."</td>";
		echo "<td><a href='speler.php?id=".$record['ID']."'>".$record['Naam'].'</a></td>';
		PrintCompetitie($record, 'VTTL', $params[PARAM_KAARTLINK_VTTL]);
		PrintCompetitie($record, 'Sporta', $params[PARAM_KAARTLINK_SPORTA]);
		echo "<td>".$record['Stijl']."</td>";
		echo "<td>".$record['BesteSlag']."</td>";
		echo "</tr>";
	}
	?>
</table>

<?php } elseif ($display == LIJST_SPORTA) { ?>
<table width='100%' class="maintable">
	<tr>
		<th width='5%'>Volgnummer</th>
		<th width='5%'>Index</th>
		<th width='5%'>Lidnummer</th>
		<th width='20%'>Naam</th>
		<th width='10%'>Sporta</th>
		<th width='10%'>Waarde</th>
		<th width='5%'>VTTL</th>
		<th width='10%'>Stijl</th>
		<th width='30%'>Beste slag(en)</th>
	</tr>
	<?php
	$result = $db->Query("SELECT s.ID, Naam, LinkKaartVTTL, k.WaardeSporta, LinkKaartSporta, KlassementSporta, KlassementVTTL, Stijl, BesteSlag, ClubIdVTTL, ClubIdSporta, VolgnummerSporta, IndexSporta, VolgnummerSporta, IndexSporta, LidNummerSporta
												FROM speler s LEFT JOIN klassement k ON s.KlassementSporta=k.Code
												WHERE Gestopt IS NULL ORDER BY ClubIdSporta DESC, VolgnummerSporta");

	while ($record = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>".$record['VolgnummerSporta']."</td>";
		echo "<td>".$record['IndexSporta']."</td>";
		echo "<td>".$record['LidNummerSporta']."</td>";
		echo "<td><a href='speler.php?id=".$record['ID']."'>".$record['Naam'].'</a></td>';
		PrintCompetitie($record, 'Sporta', $params[PARAM_KAARTLINK_SPORTA]);
		echo "<td>".$record['WaardeSporta']."</td>";
		PrintCompetitie($record, 'VTTL', $params[PARAM_KAARTLINK_VTTL]);
		echo "<td>".$record['Stijl']."</td>";
		echo "<td>".$record['BesteSlag']."</td>";
		echo "</tr>";
	}
	?>
</table>



<?php } else { ?>
<table width='100%' class="maintable">
	<tr>
		<th width='20%'>Naam</th>
		<th width='10%'>VTTL</th>
		<th width='10%'>Sporta</th>
		<th width='10%'>Stijl</th>
		<th width='50%'>Beste slag(en)</th>
	</tr>
	<?php
	$result = $db->Query("SELECT ID, Naam, LinkKaartVTTL, LinkKaartSporta, KlassementSporta, KlassementVTTL, Stijl, BesteSlag, ClubIdVTTL, ClubIdSporta
												FROM speler WHERE Gestopt IS NULL ORDER BY Naam");

	while ($record = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td><a href='speler.php?id=".$record['ID']."'>".$record['Naam'].'</a></td>';
		PrintCompetitie($record, 'VTTL', $params[PARAM_KAARTLINK_VTTL]);
		PrintCompetitie($record, 'Sporta', $params[PARAM_KAARTLINK_SPORTA]);
		echo "<td>".$record['Stijl']."</td>";
		echo "<td>".$record['BesteSlag']."</td>";
		echo "</tr>";
	}
	?>
</table>
<?php } ?>
<?php
	include_once "include/menu_end.php";
?>