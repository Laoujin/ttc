<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo (defined("PAGE_TITLE") ? PAGE_TITLE . " - " : ""); ?>TTC Erembodegem</title>

<script language="javascript" type="text/javascript" src="include/jquery.js"></script>
<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript" src="include/jqueryext.js"></script>
<script language="javascript" type="text/javascript" src="include/jquery.form.js"></script>
<?php
if (defined("PAGE_DESCRIPTION")) echo "<meta name='description' content='".PAGE_DESCRIPTION."' />";
?>
<link href="layout.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="img/favicon.png" />
</head>
<body>
<?php
include_once 'include/popup.php';
?>
<table width="100%" height="100%" border=0>
	<tr height="150">
		<td colspan="2" align=center valign="top">
			<img src="img/layout/smallbanner.jpg" border="0" hight="134" width="980" />
		</td>
	</tr>
	<tr>
		<td width="1%" valign="top">
			<table width="230" class="menutable">
				<tr>
					<td class="menuheader">TTC Erembodegem</td>
				</tr>
				<tr>
					<td>
						<li class="menuitem"><a href=clubinfo.php>Clubinfo</a>
						<li class="menuitem"><a href=kalender.php>Kalender</a>
						<li class="menuitem"><a href=spelers.php>Spelers</a>
						<li class="menuitem"><a href=reeks.php?competitie=<?php echo COM_VTTL?>>VTTL</a>
						<li class="menuitem"><a href=reeks.php?competitie=<?php echo COM_SPORTA?>>Sporta</a>
						<li class="menuitem"><a href=fotos.php>Foto's</a>
						<li class="menuitem"><a href=weetjes.php>TT Weetjes</a>
						<li class="menuitem"><a href=links.php>Links</a>
						<li class="menuitem"><a href="mailto:<?php echo $params[PARAM_EMAIL]?>">Email</a>
						<li class="menuitem"><a href="admin/index.php">Ledenzone</a>
					</td>
				</tr>
			</table>

			<div style="margin-top: 7px; left: 15px; position: absolute; background-color: white; border: 1px solid black">
				<img src="img/sponsors/smaele_small.jpg" border="0" width="200" height="141" />
			</div>
		</td>
		<td valign="top" width='99%'>