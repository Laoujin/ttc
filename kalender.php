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

<h1>Kalender</h1>
<?php
	$params = $db->GetParams(array(PARAM_STANDAARDUUR, PARAM_KAL_WEEKS_OLD, PARAM_KAL_WEEKS_NEW, PARAM_FRENOY_URL_SPORTA, PARAM_FRENOY_URL_VTTL, PARAM_FRENOY_LOGIN, PARAM_FRENOY_PASSWORD, PARAM_JAAR));
	$frenoyApi = new TabTAPI($params[PARAM_FRENOY_LOGIN], $params[PARAM_FRENOY_PASSWORD], $params[PARAM_JAAR], CLUB_CODE_VTTL, CLUB_CODE_SPORTA, $params[PARAM_FRENOY_URL_VTTL], $params[PARAM_FRENOY_URL_SPORTA]);
	PrintKalender($db, $frenoyApi, "WHERE Datum BETWEEN DATE_SUB(NOW(), INTERVAL ".($params[PARAM_KAL_WEEKS_OLD]*7)." DAY) AND DATE_ADD(NOW(), INTERVAL ".($params[PARAM_KAL_WEEKS_NEW]*7)." DAY)", $params[PARAM_STANDAARDUUR], true);
	?>
<?php
	include_once "include/menu_end.php";
?>