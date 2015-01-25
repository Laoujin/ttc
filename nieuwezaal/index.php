<?php
	define("RELATIVE_PATH", "../");
	//include_once '../include/header.php';
?>
<html>
<head>
<title>TTC Erembodegem</title>
<meta name="description" content="Officiele website van tafeltennisclub Erembodegem">
<meta name="keywords" content="TTC, Erembodegem, ttc, Ttc, tafeltennis, tafeltennisclub, kvkt, vttl, ping, pong, ping-pong,  sport, gezond, blijven plakken, bier, avondje weg">
<link href="../intro.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../include/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../include/jqueryext.js"></script>
<script language="javascript" type="text/javascript" src="jquery.modal.js"></script>
<link rel="shortcut icon" href="../img/favicon.png" />
<style>
#imgTable img
{
	border: 1px solid white;
}

.popup
{
	position: absolute;
	display: none;
	width: 950px;
	height: 638px;
	left: 100px;
	top: 100px;
	color: black;
	background-color: #bfc2c9; /*#EBEBEB*/
	border: 1px solid white;
}

.popupClose
{
	display: block;
  position: absolute;
  top: 5px;
  right: 5px;
}
</style>
</head>
<body>

<div id="popup" class="popup">
		<img src='' id=popupImg width='950' height='638'>
		<div class='popupClose'><a href=#><img src='../img/close.gif' border=0 title='Foto sluiten'></a></div>
</div>

<script language="javascript" type="text/javascript">
$(document).ready(function()
{
	$("#imgTable a").click(
		function()
		{
			$("#imgTable img").css("border", "1px solid white");
			$("img", this).css("border", "1px dashed yellow");

			var image = $("img", this).attr('src');
			image = image.substr(11);
			$("#popupImg").attr('src', 'images/'+image).load(
				function()
				{
					$("#popup").centerInClient().fadeIn();
				});

			return false;
		});

	$(".popupClose,#popupImg").click(function() {$("#popup").hide();});
});
</script>


<table border="0" width="100%">
  <tr>
    <td width="100%" align="center">
			<a href='../index.php' title='Terug naar de site'><img border="0" src="../img/layout/banner.jpg" width="975" height="77"></a>
			<h1>Officiële inhuldiging nieuwe zaal</h1>
			<font size=-1>Album: 29/10/2011 (Door Marleen De Spiegeleer)</font>
		</td>
  </tr>
	<tr>
		<td>
			<TABLE cellspacing=10 cellpadding=0 border=0 align=center id=imgTable>
			<TR>
					<TD align="center">	<A href='#' onclick='return false;'><IMG src="thumbnails/DSC_0235.jpg" height="67" width="100" border=0 title=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0238.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0239.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0240.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0241.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0242.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0246.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0247.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0248.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0249.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0250.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0252.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0253.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0254.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0255.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0256.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0258.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0259.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0260.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0262.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0267.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0268.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0279.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0282.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0287.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0288.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0290.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0291.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0296.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0307.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0310.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0312.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0313.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0315.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0317.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0319.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0325.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0327.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0330.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0339.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0341.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0343.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0346.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0348.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0352.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0353.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0355.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0356.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0358.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0365.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0367.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0369.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0370.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0371.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
			</TR>
			<TR>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0372.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0373.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0374.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center">	<a href='#' onclick='return false;'><IMG src="thumbnails/DSC_0378.jpg" height="67" width="100" border=0 alt=""></A>	</TD>
					<TD align="center" valign="center">	&nbsp;</TD>
					<TD align="center" valign="center">	&nbsp;</TD>
			</TR>
			</TABLE>
		</td>
	</tr>
</table>



</body>
</html>
<?php
	//include_once "../include/footer.php";
?>