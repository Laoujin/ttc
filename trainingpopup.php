<?php
define("RELATIVE_PATH", "");
include_once 'include/header.php';
if (is_numeric($_POST['id']) && $security->GeleideTraining())
{
	define('TRAINING_PERSONEN', 'training_personen');
	$params = $db->GetParams(TRAINING_PERSONEN);
	$result = $db->Query(
		 "SELECT NaamKort, SpelerId, Uur
			FROM training t
			JOIN speler s ON s.ID=t.SpelerId
			WHERE KalenderId=" . $_POST['id']);

	$uren = $_POST['uren'];
	assert(is_array($uren));

	$plaatsenVrij = array($params[TRAINING_PERSONEN], $params[TRAINING_PERSONEN]);
	$spelerNames = array('', '');
	while ($spelerRecord = mysql_fetch_array($result))
	{
		$ingeschrevenOpIndex = $spelerRecord['Uur'] == $uren[0] ? 0 : 1;
		$spelerNames[$ingeschrevenOpIndex] .= ', '.$spelerRecord['NaamKort'];
		$plaatsenVrij[$ingeschrevenOpIndex]--;
	}
	for ($i = 0; $i < 2; $i++)
	{
		if (strlen($spelerNames[$i]) == 0) $spelerNames[$i] = "Nog geen inschrijvingen!";
		else $spelerNames[$i] = substr($spelerNames[$i], 2);
	}
?>
<table class="maintable" width="100%">
	<tr><th>Geleide Training <?=$uren[0].'u:'.$plaatsenVrij[0]?> plaatsen vrij</th></tr>
	<tr>
		<td>
		<?=$spelerNames[0]?>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="button" class="gtInschrijven" data-uur="<?=$uren[0]?>" value="Ik doe mee om <?=$uren[0]?>u!">
			<input type="button" class="gtInschrijven" data-uur="<?=$uren[1]?>" value="Ik doe mee om <?=$uren[1]?>u!">
		</td>
	</tr>
</table>
<script>
$(function() {
	$('.gtInschrijven').click(function() {
		var $this = $(this);
		$.post('api.php', {
				action: 'gtInschrijven',
				uur: parseInt($this.attr('data-uur'), 10),
				kalenderId: <?=$_POST['id']?>,
				spelerId: <?=$_SESSION['userid']?>
			});
		$('#geleidetraining').hide();
	});
});
</script>
<?php
	$db->Close();
}
else
{
	echo "oepsie!";
}
?>