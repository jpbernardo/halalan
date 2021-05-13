
/* jQuery event handlers */

function abstainPosition(target, clicked) {
	var tr = $(target).parents('tr');

	tr.siblings().find('input:checkbox').attr('disabled', target.checked).toggle(!target.checked);
	tr.toggleClass('selected', target.checked);
	tr.siblings().has('input:checked').toggleClass('selected', !target.checked);

	var abstain = $.cookie('halalan_abstain');

	if (target.checked && clicked && abstain == null) {
		alert('By selecting abstain, you\'re not voting for any candidate in the position.  If that\'s not the case, then do not select abstain.');
		$.cookie('halalan_abstain', 1, {'path':'/'});
	}
}

function checkNumber(target) {
	if (target.disabled) {
		return;
	}

	var tr = $(target).parents('tr');
	// Get the limit for this position
	var s = tr.parents('table').siblings('h2').text().split('(');
	var limit = s[s.length - 1].replace(')', '');
	// Count the selected candidates
	var selected = tr.siblings().find('input:checked').length;

	if (selected >= limit) {
		target.checked = false;
		alert("You have already selected the maximum\nnumber of candidates for this position.");
	} else {
		tr.toggleClass('selected', target.checked);
	}
}

/**
 * This returns all TRs from other tables for the current position
 * which is determined by the 'node' parameter.
 *
 * node - any descendant of div.divider
 */
function getTableRowsForPosition(node) {
	var divider = node.parents('div.divider');
	var table = node.parents('table'); // current table

	return divider.find('table').not(table).find('tr');
}

function abstainPosition2(target, clicked) {
	var tr = $(target).parents('tr');
	var tr2 = getTableRowsForPosition(tr);

	tr.siblings().add(tr2).find('input:checkbox').attr('disabled', target.checked).toggle(!target.checked);
	tr.toggleClass('selected', target.checked);
	tr.siblings().add(tr2).has('input:checked').toggleClass('selected', !target.checked);

	if (target.checked && clicked) {
		alert('You are abstaining for this POSITION, thus any votes for this position will not be considered. If you are voting for less than the required number for this position, that is perfectly fine and you need not abstain.');
	}
}

function checkNumber2(target) {
	if (target.disabled) {
		return;
	}

	var tr = $(target).parents('tr');
	// Get the limit for this position
	var s = tr.parents('div.notes').prevAll('div.position').html().split('(');
	var limit = s[s.length - 1].replace(')', '');
	// Count the selected candidates
	var tr2 = getTableRowsForPosition(tr);
	var selected = tr.siblings().add(tr2).find('input:checked').length;

	if (selected >= limit) {
		target.checked = false;
		alert("You have already selected the maximum\nnumber of candidates for this position.");
	} else {
		tr.toggleClass('selected', target.checked);
	}
}

function modifyBallot() {
	window.location = "vote";
}

function printVotes() {
	var url = SITE_URL;
	if (url.length - 1 != url.lastIndexOf('/')) {
		url += '/';
	}
	url += 'voter/votes/print/' + $('#election_id').val();
	window.open(url);
}

function downloadVotes() {
	var url = SITE_URL;
	if (url.length - 1 != url.lastIndexOf('/')) {
		url += '/';
	}
	url += 'voter/votes/download/' + $('#election_id').val();
	window.location = url;
}

function confirmLogout() {
	var page = window.location.href.split('/').pop();
	if ('vote' == page || 'verify' == page) {
		return confirm("Your ballot has NOT been recorded yet.\nAre you sure you want to logout?");
	}
}

function triggerCheckbox(target) {
	/* Trigger for all TDs except for the one containing the toggle image */
	if (target.nodeName == 'TD' && !$(target).children('img').length) {
		var cb = $(target).siblings().find('input:checkbox');
		/*
		 * Looks hackish but this is the "only" way as of now because "clicking"
		 * a checkbox by triggering its 'click' event first executes the bound
		 * handler, i.e. checkNumber(), before toggling the 'checked' state.
		 * In the case of clicking a checkbox directly, its 'checked' state is
		 * toggled first before checkNumber() is called.
		 */
		cb.attr('checked', !cb.attr('checked'));
		cb.click();
		cb.attr('checked', !cb.attr('checked'));
	}
}

function scrollToBottom() {
	var scroll = document.body.clientHeight - window.innerHeight;
	$('html, body').animate({scrollTop: scroll}, 'normal');
	return false;
}

/* DOM is ready */
$(document).ready(function () {
	var menu_map = {};
	menu_map['verify'] = "VERIFY";
	menu_map['logout'] = "LOG OUT";
	/* always last since this can match with anything since the url has a 'vote' string */
	menu_map['vote'] = "VOTE";

	/* Bind handlers to events */
	$('table.delegateEvents').click(function(e) {
		switch (e.target.nodeName)
		{
		case 'INPUT':
			if (!$(e.target).hasClass('abstainPosition'))
				checkNumber(e.target);
			else
				abstainPosition(e.target, true);
			break;
		case 'TD':
			triggerCheckbox(e.target);
			break;
		case 'IMG':
			toggleDetails(e.target);
		}
	});
	/* Special case for one-column per party layout */
	$('table.delegateEvents2').click(function(e) {
		switch (e.target.nodeName)
		{
		case 'INPUT':
			if (!$(e.target).hasClass('abstainPosition2'))
				checkNumber2(e.target);
			else
				abstainPosition2(e.target, true);
			break;
		case 'TD':
			triggerCheckbox(e.target);
			break;
		case 'IMG':
			toggleDetails(e.target);
		}
	});
	$('a.scrollToBottom').click(scrollToBottom);
	$('input:button.modifyBallot').click(modifyBallot);
	$('input:button.printVotes').click(printVotes);
	$('input:button.downloadVotes').click(downloadVotes);
	$('a.confirmLogout').click(confirmLogout);
	/* Restore the state of abstained positions */
	$('input:checked.abstainPosition').each(function () {
		abstainPosition(this, false);
	});
	$('input:checked.abstainPosition2').each(function () {
		abstainPosition2(this, false);
	});
	$('input:checked').parents('tr').addClass('selected');
	/* Code that aren't bound to events */
	highlightMenuItem(menu_map);
	animateFlashMessage();
});
