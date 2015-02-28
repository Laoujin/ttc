<div id="verslagPopup" class="popup">
	<input type=hidden value='' id='popupIsThuis'>
	<table width='100%' class="mainTable">
		<tr>
			<th id='popupPloegThuisNaam' width='45%'>&nbsp;</th>
			<th width='10%'></th>
			<th width='45%' id='popupPloegUitNaam'>&nbsp;</th>
		</tr>
		<tr>
			<td align='center' id='popupPloegThuis'><b>&nbsp;</b></td>
			<td id='popupWedstrijdWO' align=center>&nbsp;</td>
			<td  align='center' id='popupPloegUit'><b>&nbsp;</b></td>
		</tr>
		<tr id='popupSpelers'>
			<td id='popupSpelersThuis'>
				&nbsp;
			</td>
			<td>&nbsp;</td>
			<td id='popupSpelersUit'>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan=3><b>Wedstrijdverslag</b> <span class=help>(<a href=# id=verslagWijzigen>wijzigen</a>)</span></td>
		</tr>
		</table>
		<div class='popupClose'><a href=#><img src=img/close.gif border=0></a></div>
		<div id='popupWedstrijdVerslag'></div>
</div>

<div id="verslagEditPopup" class="popup">
	<div class='popupClose'><a href=#><img src=img/close.gif border=0 id=editCloseButton></a></div>
	<input type=hidden id=editMaxPunten>
	<table width='100%' class="mainTable">
	<form id='editPopup' method="post" action="json.php?type=verslagSave">
		<input type=hidden value='' id='popupID' name='popupID'>
		<input type=hidden value='' id='popupReeks' name='popupReeks'>
		<input type=hidden value='' id='popupVerslagID' name='popupVerslagID'>
		<tr>
			<th width='45%' id='popupPloegThuisNaamEdit'>&nbsp;</th>
			<th width='10%'>-</th>
			<th width='45%' id='popupPloegUitNaamEdit'>&nbsp;</th>
		</tr>
		<tr>
			<td align='center'><input type=text size=5 id='editThuisPunten' name='editThuisPunten'></td>
			<td>&nbsp;</td>
			<td  align='center'><input type=text size=5 id='editUitPunten' name='editUitPunten'></td>
		</tr>
		<tr>
			<td id=editSpelerThuisHolder>
				<div id=editSpelerThuis>
				<?php echo $db->BuildSpelerCombo("editSpeler1", CLUB_ID, -1, true)?> &nbsp; <input type=text size=3 id='editWinst1' name='editWinst1'><br>
				<?php echo $db->BuildSpelerCombo("editSpeler2", CLUB_ID, -1, true)?> &nbsp; <input type=text size=3 id='editWinst2' name='editWinst2'><br>
				<?php echo $db->BuildSpelerCombo("editSpeler3", CLUB_ID, -1, true)?> &nbsp; <input type=text size=3 id='editWinst3' name='editWinst3'><br>
				<?php echo $db->BuildSpelerCombo("editSpeler4", CLUB_ID, -1, true)?> &nbsp; <input type=text size=3 id='editWinst4' name='editWinst4'><br>
				</div>
			</td>
			<td>&nbsp;</td>
			<td id=editSpelerUitHolder>
				<div id=editSpelerUit>
				<input type=text size=20 id=editUitSpeler1 name=editUitSpeler1> &nbsp; <?php echo $db->BuildKlassementCombo("editUitKlas1", SPORTA)?> &nbsp; <input type=text size=3 id='editUitWinst1' name='editUitWinst1'><br>
				<input type=text size=20 id=editUitSpeler2 name=editUitSpeler2> &nbsp; <select id=editUitKlas2 name=editUitKlas2></select> &nbsp; <input type=text size=3 id='editUitWinst2' name='editUitWinst2'><br>
				<input type=text size=20 id=editUitSpeler3 name=editUitSpeler3> &nbsp; <select id=editUitKlas3 name=editUitKlas3></select> &nbsp; <input type=text size=3 id='editUitWinst3' name='editUitWinst3'><br>
				<input type=text size=20 id=editUitSpeler4 name=editUitSpeler4> &nbsp; <select id=editUitKlas4 name=editUitKlas4></select> &nbsp; <input type=text size=3 id='editUitWinst4' name='editUitWinst4'><br>
				</div>
			</td>
		</tr>
		<tr id='editLoginRow'>
			<td colspan=3>
				Inloggen:
				&nbsp;
				<?php echo $db->BuildSpelerCombo("login", CLUB_ID, (isset($_COOKIE['login']) ? $_COOKIE['login'] : 0), true);?>
				&nbsp;
				<input type=password name="paswoord">
				&nbsp;
				<label class=error id=ajaxMessage></label>
			</td>
		</tr>
		<tr>
			<td colspan=3><b>Wedstrijdverslag</b></td>
		</tr>
		<tr>
			<td colspan=3>
				<input type=checkbox id=editWO name=editWO value='WO'> WO<br>
				<textarea id=editVerslag name=editVerslag rows=20 cols=80></textarea>
			</td>
		</tr>
		<tr>
			<td colspan=3 align=center><input type=submit name=buttonVerslag id=buttonVerslag value='Opslaan'>
			&nbsp;
			<input type="button" id=buttonAnnuleerVerslag value='Annuleren'></td>
		</tr>
	</form>
	</table>
</div>

<script language="javascript" type="text/javascript">
var verslagData = null;
var responseCell = null;
var hasLoggedIn = false;

function VerstuurVerslag()
{
	var options = {
        target:        '',   // target element(s) to be updated with server response
        beforeSubmit:  showRequest,  // pre-submit callback
        success:       showResponse  // post-submit callback
        //resetForm: 		 true // reset the form after successful submit

        // other available options:
        //url:       url         // override for form's 'action' attribute
        //type:      type        // 'get' or 'post', override for form's 'method' attribute
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
        //clearForm: true        // clear all form fields after successful submit

        // $.ajax options can be used here too, for example:
        //timeout:   3000
    };

  // bind form using 'ajaxForm'
  $('#editPopup').ajaxForm(options);
}

function showRequest(formData, jqForm, options)
{
	//var queryString = $.param(formData);
	//$("#editVerslag").val(queryString);
	//$("#ajaxMessage").text("Opslaan verslag...");

	// minstens de uitslag moet ingegeven worden!
	var form = jqForm[0];
	if (!form.editThuisPunten.value.length && !form.editWO.checked && !form.editVerslag.value.length)
	{
		alert("Geef minstens de uitslag van de match in!");
		return false;
	}
	if ($("#editLoginRow").is(":visible") && !form.paswoord.value.length)
	{
		alert("Inloggen is verplicht om een verslag in te geven!");
		return false;
	}

  return true;
}

function showResponse(responseText, statusText, xhr, $form)
{
	//alert(responseText);
	if (responseText.length != 0)
	{
		$("#verslagEditPopup").show();
		//alert(responseText);

		$('#ajaxMessage').hide().text(responseText).fadeIn('slow').delay(2000).fadeOut('slow');
	}
	else
	{
		hasLoggedIn = true;
		$("#editLoginRow").hide();
		$("#verslagEditPopup").hide();
		responseCell.find("label").hide().fadeIn('slow').html("<b>" + $('#editThuisPunten').val() + " - " + $('#editUitPunten').val() + "</b>");
	}
}

function ShowEditPopup(popup)
{
	var reeks = $("#popupReeks").val();
	var maxPunten = reeks == '<?php echo VTTL?>' ? <?php echo MATCHEN_VTTL?> : <?php echo MATCHEN_SPORTA?>;

	var kalenderid = $('#popupID').val();
	var verslagid = $('#popupVerslagID').val();
	$('#editPopup').resetForm();
	$('#popupID').val(kalenderid);
	$('#popupVerslagID').val(verslagid);

	$("#popupPloegThuisNaamEdit").html($("#popupPloegThuisNaam").html());
	$("#popupPloegUitNaamEdit").html($("#popupPloegUitNaam").html());

	var klassen = $("#editUitKlas1").add($("#editUitKlas2")).add($("#editUitKlas3")).add($("#editUitKlas4"));
	if (reeks == '<?php echo VTTL?>')
	{
		$("#editSpeler4").add($("#editWinst4")).add($("#editUitSpeler4")).add($("#editUitWinst4")).add($("#editUitKlas4")).show();
		klassen.find("option[value='F']").remove();
	}
	else
	{
		$("#editSpeler4").add($("#editWinst4")).add($("#editUitSpeler4")).add($("#editUitWinst4")).add($("#editUitKlas4")).hide();
		if (klassen.find("option[value='F']").size() == 0)
		{
			klassen.each(function(i, el) {
				$(el).find("option[value='NG']").after("<option value='F'>F</option>");
			});
		}
	}

	if (verslagData != null)
	{
		$('#editThuisPunten').val(verslagData.UitslagThuis);
		$('#editUitPunten').val(verslagData.UitslagUit);
		$('#editVerslag').val(verslagData.Beschrijving.replace(/\<br\>/g, "\n"));
		$("#editWO").attr('checked', verslagData.WO == 1);
		EnablePuntenIngave(verslagData.WO == 1);

		/*var thuisSpelers = $("#editSpelerThuis");
		var uitSpelers = $("#editSpelerUit");
		if ($("#popupIsThuis").val() == "1")
		{
			$("#editSpelerThuisHolder").html(thuisSpelers[0].outerHTML);
			$("#editSpelerUitHolder").html(uitSpelers[0].outerHTML);
		}
		else
		{
			$("#editSpelerThuisHolder").html(uitSpelers[0].outerHTML);
			$("#editSpelerUitHolder").html(thuisSpelers[0].outerHTML);
		}*/

		if (verslagData.ThuisObject != null)
		{
			$.each(verslagData.ThuisObject, function(i, speler) {
				$("#editWinst" + (i+1)).val(speler.Winst);
				if (speler.ID != undefined) $("#editSpeler" + (i+1)).val(speler.ID);
			});
		}

		if (verslagData.UitObject != null)
		{
			$.each(verslagData.UitObject, function(i, speler) {
				$("#editUitWinst" + (i+1)).val(speler.Winst);
				$("#editUitSpeler" + (i+1)).val(speler.Naam);
				$("#editUitKlas" + (i+1)).val(speler.Klassement);
			});
		}
	}
	else
	{
		//$('#editThuisPunten').add($('#editUitPunten')).add($('#editVerslag')).val('');
		//$("#editWO").attr('checked', false);
		EnablePuntenIngave(false);
	}

	$('#ajaxMessage').text("");
	if (!hasLoggedIn)
		$("#editLoginRow").<?php echo ($security->Verslag() ? "hide" : "show"); ?>();
	$("#editMaxPunten").val(maxPunten);
	$("#popupReeks").val(reeks);

	FadeOut($("#verslagPopup"));
	FadeIn(popup, true);
}

function FadeIn(popup, noFade)
{
	popup.width($(window).width() / 2);
 	popup.height($(window).height() / 2);
 	popup.centerInClient();
 	if (!noFade)
		popup.fadeIn('fast');
	else
		popup.show();
}

function FadeOut(popup)
{
	popup.fadeOut('fast');
}

function Toggle(popup)
{
	if (popup.is(":visible"))
		FadeOut(popup);
	else
		FadeIn(popup);
}

function ShowPopup(link, popup) {
   var kalender = link.attr('kalender');
   responseCell = link.parent();
   var hiddenField = $("#popupID");
   var currentKalender = hiddenField.val();

   hiddenField.val(kalender);
   $("#popupReeks").val(link.attr('reeks'));
   $("#popupIsThuis").val(link.attr('thuis'));

   $('#popupPloegThuisNaam').text(responseCell.prev().prev().text());
   $('#popupPloegUitNaam').text(responseCell.prev().text());

   $.getJSON("json.php", {id: kalender, type: 'verslag'}, function(data) {
		verslagData = data;
		$('#popupWedstrijdWO').html('&nbsp;');
		if (data == null || data.WO == 1)
		{
			$('#popupPloegThuis').html('&nbsp;');
				$('#popupPloegUit').html('&nbsp;');
				$('#popupSpelers').hide();

				if (data != null)
				{
					$('#popupWedstrijdWO').html('WO');
					$('#popupVerslagID').val(data.VerslagID);
					$('#popupWedstrijdVerslag').html(data.Beschrijving);
				}
				else
				{
					$('#popupVerslagID').val('');
					$('#popupWedstrijdVerslag').html('&nbsp;');
				}

				ShowEditPopup($("#verslagEditPopup"));
				return;
		}
		else
		{
			$('#popupVerslagID').val(data.VerslagID);
				$('#popupPloegThuis').html(data.UitslagThuis);
				$('#popupPloegUit').html(data.UitslagUit);
				$('#popupWedstrijdVerslag').html(data.Beschrijving + "<br><br>Verslag door <b>" + verslagData.Naam + "</b>");

				if (data.Thuis.length > 0)
				{
					$('#popupSpelers').show();
					if (link.attr('thuis') == "1")
					{
						$('#popupSpelersThuis').html(data.Thuis);
						$('#popupSpelersUit').html(data.Uit);
					}
					else
					{
						$('#popupSpelersUit').html(data.Thuis);
						$('#popupSpelersThuis').html(data.Uit);
					}
				}
				else
				{
					$('#popupSpelers').hide();
				}
		}

		popup.width($(window).width() / 2);
		popup.height($(window).height() / 2);
		popup.centerInClient();

		$("#verslagEditPopup").hide();
		if (currentKalender == kalender) Toggle(popup);
		else FadeIn(popup);
	});
}

function EnablePuntenIngave(isChecked)
{
	if (isChecked)
	{
		$("#editThuisPunten").add($("#editUitPunten")).attr('disabled', 'disabled').val("");
	}
	else
	{
		$("#editThuisPunten").add($("#editUitPunten")).removeAttr('disabled');
	}
}

$(document).ready(function() {
	$(".verslagLink").click(function(event) {
		ShowPopup($(this), $("#verslagPopup"));
		return false;
	});

	VerstuurVerslag();

	$("#buttonAnnuleerVerslag").add($('#editCloseButton')).click(function() {
		$("#verslagEditPopup").hide();
	});

	$("#editWO").click(function() {
		EnablePuntenIngave($(this).is(':checked'));
	});

	$("#editThuisPunten").add($("#editUitPunten")).change(function() {
		var maxPunten = $("#editMaxPunten").val() * 1;
		if ($(this).val() == '' || $(this).val() * 1 < 0) $(this).val("0");
		if ($(this).val() * 1 > maxPunten) $(this).val(maxPunten);

		$("#" + (this.id == 'editUitPunten' ? "editThuisPunten" : "editUitPunten")).val(maxPunten - $(this).val() * 1)[0].select();
	});

	var andereKlassementBoxes = $("#editUitKlas2").add($("#editUitKlas1")).add($("#editUitKlas3")).add($("#editUitKlas4"));
	$("#editUitKlas1 option").each(function(i, el) {
		andereKlassementBoxes.append(el);
	});
	andereKlassementBoxes.each(function(i, el) {
		$(el).find("option:first").attr("selected", "selected");
	});

	$("#verslagWijzigen").click(function() {
		ShowEditPopup($("#verslagEditPopup"));
		return false;
	});

	$("#verslagPopup").click(function() {
		$("#verslagPopup").hide();
		$("#verslagEditPopup").hide();
		return false;
	});
});
</script>