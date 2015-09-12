<?php
	define("PAGE_TITLE", "Kalender");
	define("PAGE_DESCRIPTION", "Kalender met de nog te spelen Sporta en VTTL competitie matchen van alle ploegen.");
	include_once 'include/menu_start.php';
	include_once 'TabTAPI/TabTAPI.php';
?>
<script>
$(function() {
	$(".geleidetraining").click(function() {
		var $this = $(this);
		var id = $this.attr("data-training-id");

		$('#geleidetraining').load('trainingpopup.php', {id: id}, function() {
			var popup = $('#geleidetraining');
			popup.width($(window).width() / 2);
 			popup.height($(window).height() / 2);
 			popup.centerInClient().show();
		});
		return false;
	});
});
</script>
<div id="geleidetraining"></div>

<table width="100%" cellpadding=0 cellspacing=0>
	<tr>
		<td width="500px">
			<div class="eetfestijntabel">
				<b>Zaterdag 17 oktober 2015<br>
				Eetfestijn TTC Erembodegem</b>
				
				<br><br>
				
				Van 18u00 tot 22u00 in zaal <a class="eetfestijn" href="https://maps.google.com/maps?q=Botermelkstraat+63,+9300+Aalst&hl=en&ll=50.953115,4.061058&spn=0.009449,0.023475&sll=50.952442,4.062345&sspn=0.001188,0.002934&t=m&hnear=Botermelkstraat+63,+Aalst+9300+Aalst,+Oost-Vlaanderen,+Vlaams+Gewest,+Belgium&z=16" target=_blank>Sint-Paulus</a><br>
				Botermelkstraat 63, 9300 Aalst
				
				<br><br>
				
				<table width=100% border=0 align=center>
				<tr><th colspan=2><font size=+1>Menu</font></th></tr>
				<tr>
					<td width="99%"><b>Varkenshaasje</b> met sla, tomaten<br> en saus naar keuze</td><td width="1%">&euro;15</td>
				</tr>
				<tr>
					<td><b>Tongrolletjes</b> in mosterdsaus</td><td>&euro;15</td>
				</tr>
				<tr>
					<td><b>Kindermenu</b>: kip met appelmoes</td><td><font size=-1>&euro;7,5</font></td>
				</tr>
				</table>
				
				<br>
				Steunkaarten ook beschikbaar voor &euro;3
			</div>
		</td>
	</tr>
</table>

<h1>Kalender</h1>
<?php
	$params = $db->GetParams(array(PARAM_STANDAARDUUR, PARAM_KAL_WEEKS_OLD, PARAM_KAL_WEEKS_NEW, PARAM_FRENOY_URL_SPORTA, PARAM_FRENOY_URL_VTTL, PARAM_FRENOY_LOGIN, PARAM_FRENOY_PASSWORD, PARAM_JAAR));
	$frenoyApi = new TabTAPI($params[PARAM_FRENOY_LOGIN], $params[PARAM_FRENOY_PASSWORD], $params[PARAM_JAAR], CLUB_CODE_VTTL, CLUB_CODE_SPORTA, $params[PARAM_FRENOY_URL_VTTL], $params[PARAM_FRENOY_URL_SPORTA]);
	PrintKalender($db, $frenoyApi, "WHERE Datum BETWEEN DATE_SUB(NOW(), INTERVAL ".($params[PARAM_KAL_WEEKS_OLD]*7)." DAY) AND DATE_ADD(NOW(), INTERVAL ".($params[PARAM_KAL_WEEKS_NEW]*7)." DAY)", $params[PARAM_STANDAARDUUR], true);
	?>
<?php
	include_once "include/menu_end.php";
?>