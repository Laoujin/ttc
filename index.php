<?php
	define("RELATIVE_PATH", "");
	include_once 'include/header.php';
	$params = $db->GetParams(array(PARAM_LASTUPDATE, PARAM_JAAR));
	
	$lokaal = $db->GetClubLokaal(CLUB_ID, false);
	$leden = $db->ExecuteScalar("SELECT COUNT(0) FROM speler WHERE (ClubIdVTTL=".CLUB_ID." OR ClubIdSporta=".CLUB_ID.") AND Gestopt IS NULL");
	$ploegen = $db->ExecuteScalar("SELECT COUNT(0) FROM reeks WHERE jaar=".$params[PARAM_JAAR]);
	$ploegenVTTL = $db->ExecuteScalar("SELECT COUNT(0) FROM reeks WHERE jaar=".$params[PARAM_JAAR]." AND Competitie='VTTL'");
	$ploegenSporta = $db->ExecuteScalar("SELECT COUNT(0) FROM reeks WHERE jaar=".$params[PARAM_JAAR]." AND Competitie='Sporta'");
?>
<html>
<head>
<title>TTC Erembodegem</title>
<meta name="description" content="Officiele website van tafeltennisclub Erembodegem">
<meta name="keywords" content="TTC, Erembodegem, ttc, Ttc, tafeltennis, tafeltennisclub, kvkt, vttl, ping, pong, ping-pong,  sport, gezond, blijven plakken, bier, avondje weg">
<link href="intro.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="img/favicon.png" />
<style type="text/css">
#eetfestijn {
	width: 100%; 
	background: #5282a6; 
	color: #0D0140;
	border: solid 3px white; 
	text-align: center;
	padding: 5px;
}
a.eetfestijn {
	text-decoration: underline;
	color: #0D0140;
}

.sponsor {
	width: 100%; 
	background: #5282a6;
	color: black;
	border: solid 3px white;
	text-align: center;
	margin-top: 10px;
	padding: 5px;
}

.tkleinoffer {
	background-image: url("img/sponsors/tkleinoffer.png");
	background-repeat: no-repeat;
    background-position: center; 
	height: 255px;
}

.tkleinoffer div {
	color: #8C6C51;
	padding-top: 130px;
	padding-left: 25px;
	text-align: left;
	font-weight: bold;
	font-size: 13px;
}

.tkleinoffer a {
	color: #8C6C51;
}

.sponsor img {
	border: solid 1px black;
}

#maintable {
	border: solid 0px white;
	padding-left: 25px;
}

#wrappertable {
	width: 975px;
	border: solid 0px white;
	align: center;
}
</style>
</head>
<body>

<table cellpadding=4 cellspacing=0 id=wrappertable border=0 align=center>
	<tr height=85>
		<td colspan=2 valign=top align=center><img border="0" src="img/layout/banner.jpg" width="975" height="77"></td>
	</tr>
	<tr>
		<td width="340" valign=top>
			<table width="100%" cellpadding=0 cellspacing=0>
				<!--
				<tr>
					<td>
						<div id="eetfestijn">
							<b>Zaterdag 21 september 2013<br>
							Eetfestijn TTC Erembodegem</b>
							
							<br><br>
							
							Van 18u00 tot 22u00 in zaal <a class="eetfestijn" href="https://maps.google.com/maps?q=Botermelkstraat+63,+9300+Aalst&hl=en&ll=50.953115,4.061058&spn=0.009449,0.023475&sll=50.952442,4.062345&sspn=0.001188,0.002934&t=m&hnear=Botermelkstraat+63,+Aalst+9300+Aalst,+Oost-Vlaanderen,+Vlaams+Gewest,+Belgium&z=16" target=_blank>Sint-Paulus</a><br>
							Botermelkstraat 63, 9300 Aalst
							
							<br><br>
							
							<table width=100% border=0 align=center>
							<tr><th colspan=2><font size=+1>Menu</font></th></tr>
							<tr>
								<td width="99%"><b>Varkenshaasje</b> met sla, tomaten<br> en saus naar keuze</td><td width="1%">€15</td>
							</tr>
							<tr>
								<td><b>Tongrolletjes</b> in mosterdsaus</td><td>€15</td>
							</tr>
							<tr>
								<td><b>Kindermenu</b>: kip met appelmoes</td><td><font size=-1>€7,5</font></td>
							</tr>
							</table>
							
							<br>
							Steunkaarten zijn ook beschikbaar voor €3
						</div>
					</td>
				</tr>
				-->
				<tr>
					<td>
						<div class=sponsor>
							<a href="http://www.desmaele.be/" target="_blank">
								<img src="img/sponsors/smaele.jpg" title='Klik om de site van onze sponsor "Glashandel De Smaele" te bezoeken!' width="350" height="246" />
							</a>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class=sponsor>
							<b>Slagerij Guy</b>
							<br>Erembodegem Dorp 72
							<br>9320 Erembodegem
							<br>Tel: 053 / 21 13 59
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class=sponsor>
							<a href="http://www.doopsuikersymphony.be/" target="_blank">
								<img src="img/sponsors/symphony.jpg" title='Klik om de site van onze sponsor "Doopsuiker Symphony" te bezoeken!' width="148" height="75" />
							</a>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class='sponsor tkleinoffer'>
							<div>
							Peter en Lauren Neckebroeck
							<br>Erembodegem-Dorp, 47
							<br>9320 Erembodegem
							<br>053/82 86 41
							<br><a href="http://www.tkleinoffer.be/">www.tkleinoffer.be</a>
							<br><a href="mailto:info@tkleinoffer.com">info@tkleinoffer.com</a>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class=sponsor>
							Bakkerij <a href="http://www.bakkerijvanlierde.be/">Karel Van Lierde</a>
							<br>Tel: 053 / 21 27 20
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td valign=top width="625">
			<table cellpadding=0 cellspacing=0 id=maintable>
				<tr>
					<td colspan=3>
						Welkom bij <a href="kalender.php"><b>TTC Erembodegem</b></a>
											
						<br><br>

						Wij zijn een kleine, toffe tafeltennisclub met <?php echo $leden?> leden. 
						Ondanks onze beperkte kern, slagen we er toch in om met <?php echo $ploegen?> ploegen in competitie te treden, nl. <?php echo $ploegenVTTL?> ploegen in VTTL en <?php echo $ploegenSporta?> in Sporta.
						Fairplay en gezelligheid staan centraal bij al onze tafeltennis-activiteiten!
					</td>
				</tr>
				<tr>
					<td width="40%" align=center>
						<a href="kalender.php" class="entera"><img src="img/schlager.gif" width="263" height="229" title="Welkom bij TTC Erembodegem" border="0"></a>
						<br>
						<a href="kalender.php" class="entera">Klik om de site te betreden</a>
					</td>
					<td>
						&nbsp;
					</td>
					<td>
						
						<b>Het bestuur:</b> 
						<br>
						<?php
						$mod = 0;
						$bestuursleden = "";
						$result = $db->Query("SELECT SpelerID, Omschrijving, s.Naam FROM clubcontact cc JOIN speler s ON cc.SpelerID=s.ID ORDER BY Sortering");
						while ($record = mysql_fetch_array($result))
						{
							$link = "<a href=speler.php?id=".$record['SpelerID'].">".$record['Naam']."</a>";
							if ($record['Omschrijving'] != "")
								echo $link.": ".$record['Omschrijving']."<br>";
							else
							{
								$mod++;
								$bestuursleden .= ", ";
								if ($mod > 1 && $mod % 2 == 1) $bestuursleden .= "<br>";
								$bestuursleden .= $link;
							}
						}
						if (strlen($bestuursleden) > 1)
							echo substr($bestuursleden, 2);
						?>
						
						<br><br>
						
						<b>Ons lokaal:</b>
						<br>
						<?php echo $lokaal[0] ?>
					</td>
				</tr>
				<tr>
					<td colspan=3 align=center>
						<br><br><br><br><br><br><br><br>
						Laatste update: 
						<br>
						<?php echo $params[PARAM_LASTUPDATE]?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</body>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30075487-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</html>
<?php
	include_once "include/footer.php";
?>