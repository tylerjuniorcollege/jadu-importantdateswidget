var terms = [
    '16 Week',
    '12 Week',
    '1st 8 Week',
    '2nd 8 Week'
]

hideEvents();
filterEvents();

$$(".filterSelect").invoke('observe', 'change', filterEvents);

function hideEvents() {
	$$(".filter_dates").each(Element.hide);
}

function filterEvents()
{
	hideEvents();

	var year = $("filterYear").value;
	var semester = $("filterSemester").value;
	var term = $("filterTerms").value;

	var year_sem_class = "." + year + "_" + semester;

	$$(year_sem_class + ".header").each(Element.show);

	if (term == "all") {
		$$(year_sem_class + ".header span.term")[0].innerHTML = "All Terms";

		$$(year_sem_class + ".event").each(Element.show);
	} else {
		var term_class = ".term-" + term;

		$$(year_sem_class + ".header span.term")[0].innerHTML = terms[term] + " Term";
		$$(year_sem_class + term_class + ".event").each(Element.show);
	}
}