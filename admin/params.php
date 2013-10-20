<?php
	include_once 'include.php';
	define("KALENDER", "kalender");
	include_once '../include/header.php';
	
	if (!$security->Params())
		header('Location: index.php');
		
		if (isset($_POST['paramButton']))
		{
			for ($i = 1; $i <= $_POST['aantalParams']; $i++)
			{
				$db->SetParam($_POST['paramSleutel'.$i], $_POST['paramValue'.$i]);
			}
			$db->SetLastUpdate("site");
		}
	
	include_once 'admin_start.php';
?>
<script type="text/javascript">
//$(document).ready(function() {
//	});
</script>

<h1>Admin - parameters</h1>
<form method=post>
<table width="100%" align=center class="maintable">
	<tr>
		<th width='10%'>Sleutel</th>
		<th width='90%'>Waarde</th>
	</tr>
	<?php
	$cnt = 0;
	$result = $db->Query("SELECT sleutel, value, omschrijving FROM parameter WHERE sleutel<>'".PARAM_LASTUPDATE."' ORDER BY sleutel");
	while ($record = mysql_fetch_array($result))
	{
		$cnt++;
		?>
		<tr>
			<td class='subheader' rowspan=2>
				<?php echo ucfirst($record['sleutel'])?>:
			</td>
			<td class=help>
				<input type=hidden name="paramSleutel<?php echo $cnt?>" value="<?php echo $record['sleutel']?>">
				<?php echo $record['omschrijving']?>
			</td>
		</tr>
		<tr>
			<td class=help>
				<input type=text name="paramValue<?php echo $cnt?>" size="<?php echo max(strlen($record['value']) + 5, 15)?>" value="<?php echo $record['value']?>">
			</td>
		</tr>
		<?php
	}
	?>
	<input type=hidden name=aantalParams value=<?php echo $cnt?>>
	<tr>
		<td colspan=2 align=center><input type=submit name=paramButton value="Updaten"></td>
	</tr>
</table>
</form>
<?php
	include_once 'admin_end.php';
?>