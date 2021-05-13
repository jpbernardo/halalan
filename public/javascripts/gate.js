function hashPassword() {
	var passwd = $('#password');
	if (passwd.attr('value').length > 0) {
		passwd.attr({
			'maxlength': '40',
			'value': hex_sha1(passwd.attr('value'))
		});
	}
}

function toggleOptions() {
	if ($(this).text() == "[hide options]") {
		$(this).text("[show options]");
		$('form').fadeOut();
	} else {
		$(this).text("[hide options]");
		$('form').fadeIn();
	}
	return false;
}

function toggleAllElections() {
	var cboxes = $('form').find('input:checkbox');
	cboxes.attr('checked', ($(this).text() === "select all"));
	return false;
}

function changeBlocks() {
	var url = SITE_URL;
	if (url.length - 1 != url.lastIndexOf('/')) {
		url += '/';
	}
	url += 'gate/ballots/' + $(this).val();
	window.location.href = url;
}

/* DOM is ready */
$(document).ready(function () {
	var menu_map = {};
	menu_map['results'] = "RESULTS";
	menu_map['statistics'] = "STATISTICS";
	menu_map['ballots'] = "BALLOTS";

	/* Bind handlers to events */
	$('table.delegateEvents').click(function(e) {
		if (e.target.nodeName == 'IMG')
			toggleDetails(e.target);
	});
	/* Special case for one-column per party layout */
	$('table.delegateEvents2').click(function(e) {
		if (e.target.nodeName == 'IMG')
			toggleDetails(e.target);
	});
	$('a.toggleOptions').click(toggleOptions);
	$('a.toggleAllElections').click(toggleAllElections);
	$('form.hashPassword').submit(hashPassword);
	/* Trigger events on load */
	$('a.toggleOptions').click();
	$('select.changeBlocks').change(changeBlocks);
	/* Code that aren't bound to events */
	animateFlashMessage();
	highlightMenuItem(menu_map);
	$('div[id^="partyDialog"]').dialog({
		autoOpen: false,
		width: 500,
		modal: true,
		buttons: {
			Ok: function() {
				$(this).dialog('close');
			}
		}
	});
	$('h2.opener').click(function() {
		var s = $(this).parents('div.notes').prevAll('div.position').html();
		var selector = 'div#partyDialog-' + $(this).text() + '-' + s;
		selector = selector.split(' ').join('-').split('(').join('').split(')').join('');
		$(selector).dialog('open');
		return false;
	});
});
