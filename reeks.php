<?php
	if (!isset($_GET['id']) && !isset($_GET['competitie']) && !isset($_GET['ploeg']))
		header("Location: kalender.php");

	include_once 'include/menu_start_dev.php';
	include_once 'TabTAPI/TabTAPI.php';

	$params = $db->GetParams(array(PARAM_JAAR, PARAM_STANDAARDUUR, PARAM_RESLINK_VTTL, PARAM_RANGLINK_VTTL, PARAM_RESLINK_SPORTA, PARAM_RANGLINK_SPORTA, PARAM_EMAIL, PARAM_FRENOY_URL_SPORTA, PARAM_FRENOY_URL_VTTL, PARAM_FRENOY_LOGIN, PARAM_FRENOY_PASSWORD));
	$frenoyApi = new TabTAPI($params[PARAM_FRENOY_LOGIN], $params[PARAM_FRENOY_PASSWORD], $params[PARAM_JAAR], CLUB_CODE_VTTL, CLUB_CODE_SPORTA, $params[PARAM_FRENOY_URL_VTTL], $params[PARAM_FRENOY_URL_SPORTA]);

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

			$result = $db->Query("SELECT ID, Code FROM clubploeg WHERE ReeksID=".$_GET['id']." AND ClubID=".CLUB_ID." AND Code='".$_GET['ploeg']."'"); // sql injection for the win
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
			<?php
				$frenoyApi->SetCompetition($comp);
				$teamRanking = $frenoyApi->GetDivisionRanking($reeksRecord['LinkID']);
				if ($frenoyApi->IsSuccess()) {
				?>
				<tr>
					<td class='subheader'>Rangschikking</td>
				</tr>
				<tr><td>
					<table width="100%" class="maintable">
					<tr>
						<th>Positie</th>
						<th>Club</th>
						<th>Gespeeld</th>
						<th>Gewonnen</th>
						<th>Verloren</th>
						<th>Gelijk</th>
						<th>Punten</th>
					</tr>
						<?php
							foreach ($teamRanking as $key => $ranking) {
								if ($ranking->TeamClub == $frenoyApi->GetCurrentClub()) {
									echo "<tr class='rowselected'>";
								} else {
									echo "<tr>";
								}

								echo "<td>" . $ranking->Position . "</td>";
								echo "<td>" . $ranking->Team . "</td>";
								echo "<td align='center'>" . $ranking->GamesPlayed . "</td>";
								echo "<td align='center'>" . $ranking->GamesWon . "</td>";
								echo "<td align='center'>" . $ranking->GamesLost . "</td>";
								echo "<td align='center'>" . $ranking->GamesDraw . "</td>";
								echo "<td align='center'>" . $ranking->Points . "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</td></tr>
				<?php } ?>
			<tr>
				<td class=subheader>Kalender</td>
			</tr>
			<tr>
				<td>
					<?php
					PrintKalender($db, $frenoyApi, "WHERE thuis.ID=".$clubploeg, $params[PARAM_STANDAARDUUR], false, 'reeks');
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

						$row1 .= "<td align=center>".$db->Html($spelerRecord['Naam'])." (".$spelerRecord['Klassement'.$comp].")</td>";
						$row2 .= "<td align=center>";
						$row2 .= "<a href=speler.php?id=".$spelerRecord['SpelerID'].">";
						$row2 .= GetImage($spelerRecord['SpelerID'], ($spelerRecord['Kapitein'] ? "Kapitein: " : "").$db->Html($spelerRecord['Naam'])." (".$spelerRecord['Klassement'.$comp].")");
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

			$result = $db->Query("SELECT r.ID AS ReeksID, Competitie, Reeks, ReeksType, ReeksCode, cp.Code, cp.Code AS ThuisPloeg, cp.ID AS ClubPloeg, r.LinkID
													FROM reeks r JOIN clubploeg cp ON r.ID=cp.ReeksID AND cp.ClubID=".CLUB_ID."
													WHERE Competitie='".$comp."' AND Jaar=".$params[PARAM_JAAR]." ORDER BY cp.Code");

			while ($record = mysql_fetch_array($result)) PrintPloeg($record, $comp);
		}
		else
		{
			// 1 ploeg
			$comp = $_GET['comp'] == "VTTL" ? "VTTL" : "Sporta";

			$result = $db->Query("SELECT r.ID AS ReeksID, Competitie, Reeks, ReeksType, ReeksCode, cp.Code, cp.Code AS ThuisPloeg, cp.ID AS ClubPloeg, r.LinkID, Jaar
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