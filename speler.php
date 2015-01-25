<?php
	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) header("Location: index.php");
	include_once 'include/menu_start_dev.php';
	$params = $db->GetParams(array(PARAM_KAARTLINK_VTTL, PARAM_KAARTLINK_SPORTA, PARAM_JAAR, PARAM_EMAIL));

	$result = $db->Query("SELECT s.ID, Naam, ClubIdVTTL, ClubIdSporta, LinkKaartVTTL, KlassementVTTL, LinkKaartSporta, KlassementSporta, Stijl, BesteSlag, ComputerNummerVTTL, LidNummerSporta, IndexVTTL, VolgnummerVTTL,
												IndexSporta, VolgnummerSporta, Adres, Gemeente, GSM, Email, k.WaardeSporta
												FROM speler s LEFT JOIN klassement k ON s.KlassementSporta=k.Code WHERE ID=".$_GET['id']);

	if ($record = mysql_fetch_array($result))
	{
		define("PAGE_TITLE", $record['Naam']);
		define("PAGE_DESCRIPTION", "De speelstijl en competitie details van ".$record['Naam'].".");
		include_once 'include/menu_start_html.php';
		?>
		<h1>Speler overzicht</h1>
		<table width='100%' class=maintable>
			<tr>
				<td width='1%' valign=top>
					<?php echo GetImage($record['ID'], $record['Naam'])?>
				</td>
				<td valign=top>
					<table width='100%' class=maintable>
						<tr>
							<th colspan=2>
								<?php echo $record['Naam']?>
							</th>
						</tr>
						<tr>
							<td class=subheader width='10%'>Stijl:</td>
							<td width='90%'><?php echo $record['Stijl']?></td>
						</tr>
						<tr>
							<td class=subheader nowrap>Beste slag(en):</td>
							<td><?php echo $record['BesteSlag']?></td>
						</tr>
						<?php
						if ($record['ClubIdVTTL'] == CLUB_ID)
						{
							?>
								<tr>
									<th colspan=2>VTTL</th>
								</tr>
								<tr>
									<td class=subheader>Klassement:</td>
									<td><?php echo $record['KlassementVTTL']." &nbsp;<a href=".sprintf($params[PARAM_KAARTLINK_VTTL], $record['LinkKaartVTTL'])." target='_Blank'><img src=img/linkkaart.png class=icon title='Officiële kaart' tag='Officiële kaart'></a>"?></td>
								<tr>
								<tr>
									<td class=subheader>Volgnummer:</td>
									<td><?php echo $record['VolgnummerVTTL']?></td>
								</tr>
								<tr>
									<td class=subheader>Index:</td>
									<td><?php echo $record['IndexVTTL']?></td>
								</tr>
								<tr>
									<td class=subheader>Lidnummer:</td>
									<td><?php echo $record['ComputerNummerVTTL']?></td>
								</tr>
								<tr>
									<td class=subheader>Ploeg:</td>
									<td>
									<?php
									$result = $db->Query("SELECT Competitie, Reeks, ReeksType, ReeksCode, r.ID AS ReeksID FROM clubploegspeler cps JOIN clubploeg cp ON cps.ClubPloegID=cp.ID JOIN reeks r ON cp.ReeksID=r.ID WHERE cps.SpelerID=".$_GET['id']." AND r.Jaar=".$params[PARAM_JAAR]." AND r.Competitie='VTTL'");
									while ($ploegRecord = mysql_fetch_array($result))
									{
										echo DisplayReeks($ploegRecord, '', true)."<br>";
									}
									?>
									</td>
								</tr>
							<?php
						}
						if ($record['ClubIdSporta'] == CLUB_ID)
						{
							?>
								<tr>
									<th colspan=2>Sporta</th>
								</tr>
								<tr>
									<td class=subheader>Klassement:</td>
									<td>
										<?php echo $record['KlassementSporta']." &nbsp;<a href=".sprintf($params[PARAM_KAARTLINK_SPORTA], $record['LinkKaartSporta'])." target='_Blank'><img src=img/linkkaart.png class=icon title='Officiële kaart' tag='Officiële kaart'></a>"?>
										&nbsp; (Waarde: <?php echo $record['WaardeSporta']?>)
									</td>
								<tr>
								<tr>
									<td class=subheader>Volgnummer:</td>
									<td><?php echo $record['VolgnummerSporta']?></td>
								</tr>
								<tr>
									<td class=subheader>Index:</td>
									<td><?php echo $record['IndexSporta']?></td>
								</tr>
								<tr>
									<td class=subheader>Lidnummer:</td>
									<td><?php echo $record['LidNummerSporta']?></td>
								</tr>
								<tr>
									<td class=subheader>Ploeg:</td>
									<td>
									<?php
									$result = $db->Query("SELECT Competitie, Reeks, ReeksType, ReeksCode, r.ID AS ReeksID FROM clubploegspeler cps JOIN clubploeg cp ON cps.ClubPloegID=cp.ID JOIN reeks r ON cp.ReeksID=r.ID WHERE cps.SpelerID=".$_GET['id']." AND r.Jaar=".$params[PARAM_JAAR]." AND r.Competitie='Sporta'");
									while ($ploegRecord = mysql_fetch_array($result))
									{
										echo DisplayReeks($ploegRecord, '', true)."<br>";
									}
									?>
									</td>
								</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
			<?php
			if (isset($_SESSION['user']))
			{
				?>
				<tr>
					<th colspan=2>Persoonlijke gegevens</th>
				</tr>
				<tr>
					<td class=subheader>Adres:</td>
					<td><?php echo $record['Adres']?><br><?php echo $record['Gemeente']?></td>
				</tr>
				<tr>
					<td class=subheader>GSM:</td>
					<td><?php echo $record['GSM']?>&nbsp;</td>
				</tr>
				<tr>
					<td class=subheader>Email:</td>
					<td><?php echo ($record['Email'] != ''? "<a href='mailto:".$record['Email']."'>".$record['Email']."</a>" : "&nbsp;")?></td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
?>
<?php
	include_once "include/menu_end.php";
?>