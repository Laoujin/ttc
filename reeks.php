<?php
	if (!isset($_GET['id']) && !isset($_GET['competitie']) && !isset($_GET['ploeg']))
		header("Location: kalender.php");
	
	include_once 'include/menu_start_dev.php';
	$params = $db->GetParams(array(PARAM_JAAR, PARAM_STANDAARDUUR, PARAM_RESLINK_VTTL, PARAM_RANGLINK_VTTL, PARAM_RESLINK_SPORTA, PARAM_RANGLINK_SPORTA, PARAM_EMAIL));

	function PageHeader()
	{
		echo "<h1>Reeks</h1>";
		echo "<table width='100%' class='maintable'>";
	}
	
	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$result = $db->Query("SELECT Competitie, Reeks, ReeksType, ReeksCode, LinkID, Jaar FROM reeks WHERE ID=".$_GET['id']);
		if ($reeksRecord = mysql_fetch_array($result))
		{
			$comp = $reeksRecord['Competitie'];
			
			$result = $db->Query("SELECT ID, Code FROM clubploeg WHERE ReeksID=".$_GET['id']." AND ClubID=".CLUB_ID);
			$record = mysql_fetch_array($result);
			$clubploeg = $record['ID'];
			
			define("PAGE_TITLE", $record['Code']." Ploeg ".$comp);
			define("PAGE_DESCRIPTION", "Kalender en links naar de resultaten van de ".$record['Code']." ploeg $comp.");
			include_once 'include/menu_start_html.php';
			PageHeader();
			?>
			<tr>
				<th><?php echo DisplayReeks($reeksRecord)?></th>
			<tr>
			<tr>
				<td class=subheader>Erembodegem <?php echo $record['Code']?> <span class=help>(<a href=reeks.php?ploeg=<?php echo $_GET['id']?>&comp=<?php echo $reeksRecord['Competitie']?>>Spelers</a>)</span></td>
			</tr>
			<?php if ($reeksRecord['LinkID'] != "") { ?>
			<tr>
				<td><?php echo CreateIconLink(sprintf($params['linkRes'.$comp], substr($params[PARAM_JAAR], 2, 2)*1+1, $reeksRecord['LinkID']), 'Officiële site: resultaten <span class=help>(Externe link)</span>', 'statistieken.png' ,'', true)?></td>
			</tr>
			<tr>
				<td><?php echo CreateIconLink(sprintf($params['linkRang'.$comp], substr($params[PARAM_JAAR], 2, 2)*1+1, $reeksRecord['LinkID']), 'Officiële site: rangschikking <span class=help>(Externe link)</span>', 'statistieken.png' ,'', true)?></td>
			</tr>
			<?php } ?>
			<tr>
				<td class=subheader>Kalender</td>
			</tr>
			<tr>
				<td>
					<?php
					PrintKalender($db, "WHERE thuis.ID=".$clubploeg, $params[PARAM_STANDAARDUUR], false, 'reeks');
					?>
				</td>
			</tr>
			<?php
		}
	}
	elseif ((isset($_GET['competitie']) && ($_GET['competitie'] == COM_SPORTA || $_GET['competitie'] == COM_VTTL)) || is_numeric($_GET['ploeg']))
	{
		function PrintPloeg($record, $comp)
		{
			global $db;
			global $params;
			
			$jaar = isset($record['Jaar']) && $record['Jaar'] != date("Y") ? "Seizoen ".$record['Jaar']."-".($record['Jaar']*1 + 1).": " : "";
			?>
			<tr>
				<th colspan=2><?php echo $jaar."Erembodegem ".$record['Code']?></th>
			</tr>
			<tr>
				<td class=subheader width='10%'>Uitslagen:</td>
				<td width='90%'><?php echo DisplayReeks($record, '', true)?></td>
			<tr>
			<?php if ($record['LinkID'] != "") { ?>
			<tr>
				<td class=subheader>Resultaten:</td>
				<td><?php echo CreateIconLink(sprintf($params['linkRes'.$comp], substr($params[PARAM_JAAR], 2, 2)*1+1, $record['LinkID']), 'Officiële site: resultaten <span class=help>(Externe link)</span>', 'statistieken.png' ,'', true)?></td>
			</tr>
			<tr>
				<td class=subheader>Rangschikking:</td>
				<td><?php echo CreateIconLink(sprintf($params['linkRang'.$comp], substr($params[PARAM_JAAR], 2, 2)*1+1, $record['LinkID']), 'Officiële site: rangschikking <span class=help>(Externe link)</span>', 'statistieken.png' ,'', true)?></td>
			</tr>
			<?php } ?>
			<tr>
				<td class=subheader valign=top>Spelers:</td>
				<td align=center>
					<?php
					$mod = 0;
					$spelers = array();
					$spelerResult = $db->Query("SELECT SpelerID, Kapitein, Naam, Klassement$comp FROM clubploegspeler cps JOIN speler s ON cps.SpelerID=s.ID JOIN klassement k ON s.Klassement$comp=k.Code WHERE ClubPloegID=".$record['ClubPloeg']." ORDER BY Kapitein DESC, Waarde$comp DESC");
					while ($spelerRecord = mysql_fetch_array($spelerResult))
						$spelers[] = $spelerRecord;
					
					if (count($spelers) % 4 == 1) $modLimit = 3;
					else $modLimit = 4;
					
					$row1 = "";
					$row2 = "";
					
					foreach ($spelers as $key => $spelerRecord)
					{
						$mod++;
						
						$row1 .= "<td align=center>".$spelerRecord['Naam']." (".$spelerRecord['Klassement'.$comp].")</td>";
						$row2 .= "<td align=center>";
						$row2 .= "<a href=speler.php?id=".$spelerRecord['SpelerID'].">";
						$row2 .= GetImage($spelerRecord['SpelerID'], ($spelerRecord['Kapitein'] ? "Kapitein: " : "").$spelerRecord['Naam']." (".$spelerRecord['Klassement'.$comp].")");
						$row2 .= "</a>";
						$row2 .= "</td>";
						
						if ($mod % $modLimit == 0)
						{
							echo "<table width='100%' class='emptytable'><tr>".$row1."</tr><tr>".$row2."</tr></table>";
							$row1 = "";
							$row2 = "";
						}
					}
					if (strlen($row1) > 0)
						echo "<table width='100%' class=emptytable><tr>".$row1."</tr><tr>".$row2."</tr></table>";
					?>
				</td>
			</tr>
			<?php
		}
		
		if (!isset($_GET['ploeg']) || !is_numeric($_GET['ploeg']))
		{
			// Alle ploegen
			$id = $_GET['competitie'];
			$comp = $id == COM_VTTL ? "VTTL" : "Sporta";
			
			define("PAGE_TITLE", 'Ploegen '.$comp);
			define("PAGE_DESCRIPTION", "Overzicht van de spelers van alle ploegen in de $comp competitie.");
			include_once 'include/menu_start_html.php';
			PageHeader();
			
			$result = $db->Query("SELECT r.ID AS ReeksID, Competitie, Reeks, ReeksType, ReeksCode, cp.Code, cp.ID AS ClubPloeg, r.LinkID
													FROM reeks r JOIN clubploeg cp ON r.ID=cp.ReeksID AND cp.ClubID=".CLUB_ID."
													WHERE Competitie='".$comp."' AND Jaar=".$params[PARAM_JAAR]);

			while ($record = mysql_fetch_array($result)) PrintPloeg($record, $comp);
		}
		else
		{
			// 1 ploeg
			$comp = $_GET['comp'] == "VTTL" ? "VTTL" : "Sporta";
			
			$result = $db->Query("SELECT r.ID AS ReeksID, Competitie, Reeks, ReeksType, ReeksCode, cp.Code, cp.ID AS ClubPloeg, r.LinkID, Jaar
													FROM reeks r JOIN clubploeg cp ON r.ID=cp.ReeksID AND cp.ClubID=".CLUB_ID."
													WHERE r.ID=".$_GET['ploeg']);

			while ($record = mysql_fetch_array($result))
			{
				define("PAGE_TITLE", $record['Code']." Ploeg ".$comp);
				define("PAGE_DESCRIPTION", "Overzicht van de spelers van de ".$record['Code']." ploeg $comp.");
				include_once 'include/menu_start_html.php';
				PageHeader();
				
				PrintPloeg($record, $comp);
			}			
		}
	}
?>
</table>
<?php
	include_once "include/menu_end.php";
?>