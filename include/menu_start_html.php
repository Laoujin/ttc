<html>
<head>
<title><?php echo (defined("PAGE_TITLE") ? PAGE_TITLE . " - " : ""); ?>TTC Erembodegem</title>
<script language="javascript" type="text/javascript" src="include/jquery.js"></script>
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
						<!--<li class="menuitem"><a href=sponsors.php>Sponsors</a>-->
						<li class="menuitem"><a href=reeks.php?competitie=<?php echo COM_VTTL?>>VTTL</a>
						<li class="menuitem"><a href=reeks.php?competitie=<?php echo COM_SPORTA?>>Sporta</a>
						<?php if (false && isset($_SESSION['user'])) { ?>
							<li class="menuitem"><a href='http://www.tafeltennisactua.be/forum/viewforum.php?f=53' target='_blank'>Forum Erembodegem</a>
						<?php } ?>
						<li class="menuitem"><a href=fotos.php>Foto's</a>
						<li class="menuitem"><a href=weetjes.php>TT Weetjes</a>
						<li class="menuitem"><a href=links.php>Links</a>
						<li class="menuitem"><a href="mailto:<?php echo $params[PARAM_EMAIL]?>">Email</a>
						<!--<li class="menuitem"><a href="admin/index.php">Admin</a>-->
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" width='99%'>