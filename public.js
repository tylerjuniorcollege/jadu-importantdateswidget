var terms = [];
terms['term-16'] = '16-Week';
terms['term-12'] = '12-Week';
terms['term-8-1'] = '1st 8-Week';
terms['term-8-2'] = '2nd 8-Week';
terms['summer-term-1'] = 'Summer I';
terms['summer-term-2'] = 'Summer II';
terms['summer-term-mid'] = 'Mid-Summer';
terms['summer-term-long'] = 'Summer Long';

var filterTermSelects = ["filterSTerms", "filterFWTerms"];

hideEvents();
filterEvents();

$$(".filterSelect").invoke('observe', 'change', filterEvents);

function hideEvents() {
	$$(".filter_dates").each(Element.hide);
}

function resetTermValues() {
	filterTermSelects.each(function(ele) {
		ele.value = 'all';
	});
}

function resetZebra() {
	var zebra = 0;
	$$("tr.event").each(function(ele) {
		if(ele.visible()) {
			if(!ele.hasClassName('highlightRow')) {
				if((zebra % 2) == 1) { // we need to add if it DOESN'T have the zebra class.
					if(!ele.hasClassName('zebra')) {
						ele.addClassName('zebra');
					}
				} else { // we need to remove if it DOES have the zebra class
					if(ele.hasClassName('zebra')) {
						ele.removeClassName('zebra');
					}
				}
			} 	
			zebra++;
		}
	});
}

function filterEvents()
{
	hideEvents();

	var year = $("filterYear").value;
	var semester = $("filterSemester").value;
	var fw_term = $("filterFWTerms");
	var s_term = $("filterSTerms");
	var term = "all";

	var year_sem_class = "." + year + "_" + semester;

	$$(year_sem_class + ".header").each(Element.show);

	if (semester == "summer") {
		s_term.enable();
		if (s_term.visible()) {
			term = s_term.value;
		} else {
			resetTermValues();
			s_term.show();
			fw_term.hide();
		}
	} else if (semester == 'winter' || semester == 'may') {
		resetTermValues();
		s_term.disable();
		fw_term.disable();
	} else {
		fw_term.enable();
		if (fw_term.visible()) {
			term = fw_term.value;
		} else {
			resetTermValues();
			fw_term.show();
			s_term.hide();
		}
	}

	if (term == "all") {
		$$(year_sem_class + ".header span.term")[0].innerHTML = "All Terms";

		$$(year_sem_class + ".event").each(Element.show);

		$$(".available_terms").each(Element.show);
	} else {
		$$(year_sem_class + ".header span.term")[0].innerHTML = terms[term] + " Term";
		$$(year_sem_class + "." + term + ".event").each(Element.show);

		$$(".available_terms").each(Element.hide);
	}

	resetZebra();
}