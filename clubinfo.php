<?php
	define("PAGE_TITLE", "Clubinfo");
	define("PAGE_DESCRIPTION", "Ligging van de club en contacteren van het clubbestuur.");
	include_once 'include/menu_start.php';
?>
<h1>Clubinfo</h1>

<div align=center><img src='img/club/groepsfoto.jpg' border=1></div>
<br>

<table width='100%' class='maintable'>
	<tr>
		<th width="25%">Lokaal</th>
		<th width="25%">Training</th>
		<th width="25%">Competitie</th>
		<th width="25%">Inschrijvingsgeld</th>
	</tr>
	<tr>
		<td valign=top align=center>
			<?php
			$first = true;
			$lokaal = $db->GetClubLokaal(CLUB_ID, false);
			foreach ($lokaal as $key => $value)
			{
				if (!$first) echo "<br><br>";
				echo $value;
				$first = false;
			}
			?>
		</td>
		<td valign=top align=center>
			Dinsdag en donderdag vanaf 20u
		</td>
		<td valign=top align=center>
			Maandag, woensdag en vrijdag om 20u
		</td>
		<td align=center>
			&euro;90 voor volwassenen<br>
			&euro;50 voor -18 jarigen
		</td>
	</tr>
</table>
<table width='100%' class='maintable'>
	<tr>
		<th colspan=2>Bestuur</th>
	</tr>
	<?php
	$bestuursledenShown = false;
	$result = $db->Query("SELECT SpelerID, Omschrijving, s.Naam, Adres, Gemeente, Tel, GSM, Email FROM clubcontact cc JOIN speler s ON cc.SpelerID=s.ID ORDER BY Sortering");
	while ($record = mysql_fetch_array($result))
	{
		if ($record['Omschrijving'] != "") $desc = ucfirst($record['Omschrijving']);
		else if (!$bestuursledenShown) $desc = "Bestuursleden";
		else $desc = "";
		if ($desc != "")
		{
			?>
			<tr>
				<th colspan=2><?php echo $desc?></th>
			</tr>
			<?php
		}
			?>
			<tr>
				<td width='1%'>
					<?php echo GetImage($record['SpelerID'], $record['Naam'])?>
				</td>
				<td width='99%' valign=top>
					<b><a href=speler.php?id=<?php echo $record['SpelerID']?>><?php echo $record['Naam']?></a></b><br>
					<?php
						//echo $record['Adres']."<br>".$record['Gemeente']."<br>";
						if ($record['Tel'] != "") echo "Tel.: ".$record['Tel']."<br>";
						echo "GSM: ".$record['GSM']."<br>";
						echo "Email: <a href=mailto:".$record['Email'].">".$record['Email']."</a>";
					?>
				</td>
			</tr>
		<?php
		
		if ($record['Omschrijving'] == "") $bestuursledenShown = true;
	}
	?>
</table>
<?php
	include_once "include/menu_end.php";
?>