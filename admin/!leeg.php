<?php
	include_once 'include.php';
	define("KALENDER", "kalender");
	include_once '../include/header.php';
	
	if (!$security->Params())
		header('Location: index.php');
	
	$params = $db->GetParams();
	
	include_once 'admin_start.php';
?>
<script type="text/javascript">
//$(document).ready(function() {
//	});
</script>

<h1></h1>
<form method=post>
<table width="100%" align=center class="maintable">
	<tr>
		<th colspan=2></th>
	</tr>
	
	<tr>
		<td colspan=2 align=center><input type=submit name=Button value="Toevoegen"></td>
	</tr>
</table>
</form>
<?php
	include_once 'admin_end.php';
?>