<?php
define("RELATIVE_PATH", "");
include_once 'include/header.php';
if (is_numeric($_POST['id']) && $security->GeleideTraining())
{
	function get_inschrijving_desc($uur, $reedsIngeschreven)
	{
		return $reedsIngeschreven ? 'Ik kan toch niet meetrainen! :(' : 'Ik doe mee om '.$uur.'u!';
	}

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
	$reedsIngeschreven = array(0, 0);
	while ($spelerRecord = mysql_fetch_array($result))
	{
		$ingeschrevenOpIndex = $spelerRecord['Uur'] == $uren[0] ? 0 : 1;
		$plaatsenVrij[$ingeschrevenOpIndex]--;
		if ($spelerRecord['SpelerId'] != $_SESSION['userid'])
		{
			$spelerNames[$ingeschrevenOpIndex] .= ', '.$spelerRecord['NaamKort'];
		}
		else
		{
			$reedsIngeschreven[$ingeschrevenOpIndex] = 1;
			$spelerNames[$ingeschrevenOpIndex] .= ', <b>'.$spelerRecord['NaamKort'].'</b>';
		}
	}
	for ($i = 0; $i < 2; $i++)
	{
		if (strlen($spelerNames[$i]) == 0) $spelerNames[$i] = "Nog geen inschrijvingen!";
		else $spelerNames[$i] = substr($spelerNames[$i], 2);
	}
?>
<div class='popupClose'><a href=#><img src=img/close.gif border=0></a></div>
<table class="maintable" width="100%">
	<?php for ($i = 0; $i < 2; $i++) { ?>
	<tr><th>Geleide Training <?=$uren[$i].'u: '.$plaatsenVrij[$i]?> plaatsen vrij</th></tr>
	<tr>
		<td><?=$spelerNames[$i]?></td>
	</tr>
	<tr>
		<td align="center">
			<input type="button"
				class="gtInschrijven <?=($reedsIngeschreven[$i] ? '' : 'gtUitschrijven') ?>"
				data-uur="<?=$uren[$i]?>"
				value="<?=get_inschrijving_desc($uren[$i], $reedsIngeschreven[$i])?>">
		</td>
	</tr>
	<?php } ?>
</table>
<script>
$(function() {
	function closePopup() {
		$("#geleidetraining").hide();
	}

	$('.gtInschrijven').click(function() {
		var $this = $(this);
		$.post('api.php', {
				action: 'gtInschrijven',
				uur: parseInt($this.attr('data-uur'), 10),
				kalenderId: <?=$_POST['id']?>,
				spelerId: <?=$_SESSION['userid']?>
			});
		closePopup();
	});
	$(".popupClose").click(closePopup);
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