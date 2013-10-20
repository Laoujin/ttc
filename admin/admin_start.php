<html>
<head>
<title>TTC Erembodegem</title>
<script language="javascript" type="text/javascript" src="../include/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../include/datetimepicker.js"></script>
<link href="layout.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%">
	<?php if (isset($msg)) { ?>
	<tr>
		<td class="error" colspan=2>
			<?php echo $msg; ?>
			<br>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td width="150" valign="top">
			<table width="100%" class="menutable">
				<tr>
					<td class="menuheader">Admin</td>
				</tr>
				<tr>
					<td nowrap>
						<?php
						echo "<a href=../kalender.php>Terug</a><br>";
						if ($security->Kalender()) echo "<a href=kalender.php>Kalender nieuw</a><br>";
						if (false && $security->Kalender()) echo "<a href=kalenderedit.php>Kalender edit</a><br>";
						if ($security->Spelers()) echo "<a href=spelers.php>Spelers</a><br>";
						if ($security->Params()) echo "<a href=params.php>Parameters</a><br>";
						if (false && $security->Ploegen()) echo "<a href=ploegen.php>Ploegen</a><br>";
						if ($security->Any()) echo "<a href=paswoord.php>Paswoord</a><br>";
						if ($security->Admin()) echo "<a href=../json.php?type=admin>JSon</a><br>";
						if ($security->Admin()) echo "<a href='sitemapgenerator.php'>Sitemap</a><br>";
						echo "<br>";
						if ($_SESSION['user'] != "") echo "<a href=index.php?uitloggen=true>Uitloggen</a><br>";
						?>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">