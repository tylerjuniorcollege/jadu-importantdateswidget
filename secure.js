if ($("tbl_widget_content").getElementsByTagName("tfoot")[0]) {
	$("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
}	
var currentEventEdit = -1;
var terms = [];
terms['term-16'] = '16-Week';
terms['term-12'] = '12-Week';
terms['term-8-1'] = '1st 8-Week';
terms['term-8-2'] = '2nd 8-Week';
terms['summer-term-1'] = 'Summer I';
terms['summer-term-2'] = 'Summer II';
terms['summer-term-mid'] = 'Mid-Summer';
terms['summer-term-long'] = 'Summer Long';

var old_terms = [];
old_terms[0] = 'term-16';
old_terms[1] = 'term-12';
old_terms[2] = 'term-8-1';
old_terms[3] = 'term-8-2';

var months = [];
months[0] = "January";
months[1] = "Feburary";
months[2] = "March";
months[3] = "April";
months[4] = "May";
months[5] = "June";
months[6] = "July";
months[7] = "August";
months[8] = "September";
months[9] = "October";
months[10] = "November";
months[11] = "December";

var widgetEvents = new Array();
var oldsave = $("saveWidgetProperty").onclick;

if (typeof $("saveWidgetProperty").onclick != "function") {
    $("saveWidgetProperty").onclick = commitwidgetEvents;
}
else {
    $("saveWidgetProperty").onclick = function ()
    {
        commitWidgetEvents();
        oldsave();
    }
}



fetchEvents();
iterateEvents();
$('date_widget_dates').show();
filterRows(true);
switchTerms();

$("event_semester").observe('change', switchTerms);

function hideTerms() {
    $$(".term_display").each(Element.hide);
}

function switchTerms() 
{
    hideTerms();
    $("term_row").show();
    if ($("event_semester").value == "summer") {
        $$(".term_display.summer").each(Element.show);
    } else if ($("event_semester").value == "fall" || $("event_semester").value == "spring") {
        $$(".term_display.regular").each(Element.show);
    } else {
        // Hide the row.
        $("term_row").hide();
    }
}

function addWidgetEvent ()
{
    currentEventEdit = -1;
    $("event_year").value = "";
    $("event_start_date").value = "";
    $("event_end_date").value = "";
	$("event_name").value = "";
    $("event_semester").value = "";
    $$(".event_terms").each(function(ele) {
        ele.checked = false;
    });
    $("event_highlight").value = "";
    $("event_url").value = "";
    $("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "";
    $("date_widget_dates").style.display = "none";
    $("widgetEventDelete").style.display = "none";
}

function editWidgetEvent (widgetEventId)
{	
	currentEventEdit = widgetEventId;
	if (widgetEvents[currentEventEdit][0] != null) {
        $("event_year").value = widgetEvents[currentEventEdit][0];
    }
    if (widgetEvents[currentEventEdit][1] != null) {
        $("event_start_date").value = widgetEvents[currentEventEdit][1];
    }
    if (widgetEvents[currentEventEdit][2] != "0") {
        $("event_end_date").value = widgetEvents[currentEventEdit][2];
    }
	if (widgetEvents[currentEventEdit][3] != null) {
        $("event_name").value = widgetEvents[currentEventEdit][3];
    }
    if (widgetEvents[currentEventEdit][4] != null) {
        $("event_semester").value = widgetEvents[currentEventEdit][4];
    }

    // Make sure to check the boxes for the specific terms.
    widgetEvents[currentEventEdit][5].each(function(termid) {
        if (termid.length < 2 && termid.length > 0) {
            termid = old_terms[termid];
        }
        if (termid.length > 0) {
            $(termid).checked = true;
        }
    });

    switchTerms();

    if (widgetEvents[currentEventEdit][6] == true) {
        $("event_highlight").checked = true;
    }

    if (widgetEvents[currentEventEdit][7] != null) {
        $("event_url").value = widgetEvents[currentEventEdit][7];
    }

    $("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "";
    $("date_widget_dates").style.display = "none";
    $("widgetEventDelete").style.display = "";
}

function saveWidgetEvent ()
{
	// check all required fields have been entered
	var errors = false;
	
	if ($("event_year").value.length == 0) {
		errors = true;
	}
    else if ($("event_start_date").value.length == 0) {
        errors = true;
    }
    else if ($("event_end_date").value.length == 0) {
        $("event_end_date").value = 0;
    }
	else if ($("event_name").value.length == 0) {
		errors = true;
	}
    else if ($("event_semester").value.length == 0) {
		errors = true;
	}
    else if ($("event_year").value.length == 0) {
        errors = true;
    }
    
    var checked = false;
    $$(".event_terms").each(function(term) {
        if ($(term).checked) {
            checked = true;
        } else if ($("event_semester").value == 'winter' || $("event_semester").value == 'may') {
            checked = true;
        }
    });

    if(!checked) {
        errors = true;
    }
	
	if (!errors) {
        var eventTerms = [];
        $$(".event_terms").each(function(term) {
            if ($(term).checked) {
                    eventTerms.push($(term).value);
            }
        });
        var eventHighlight = false;
        if ($("event_highlight").checked) {
            eventHighlight = true;
        }

        var eventUrl = null;
        if ($("event_url").value.length > 0) {
            eventUrl = $("event_url").value;
        }

	    if (currentEventEdit == -1) {
	        widgetEvents.push(new Array($("event_year").value, 
                                        $("event_start_date").value, 
                                        $("event_end_date").value,
                                        $("event_name").value, 
                                        $("event_semester").value, 
                                        eventTerms,
                                        eventHighlight,
                                        eventUrl));
	        // add new row
	        addEventRow (widgetEvents.length - 1, widgetEvents[widgetEvents.length - 1]);
	    }
	    else {
            // editing a current row 
            widgetEvents[currentEventEdit][0] = $("event_year").value;
	        widgetEvents[currentEventEdit][1] = $("event_start_date").value;
            widgetEvents[currentEventEdit][2] = $("event_end_date").value;
			widgetEvents[currentEventEdit][3] = $("event_name").value;
	        widgetEvents[currentEventEdit][4] = $("event_semester").value;
			widgetEvents[currentEventEdit][5] = eventTerms;
            widgetEvents[currentEventEdit][6] = eventHighlight;
            widgetEvents[currentEventEdit][7] = eventUrl;

            // remove and re-add event row.
            $("widgetEvent" + currentEventEdit).remove();
            addEventRow (currentEventEdit, widgetEvents[currentEventEdit]);
	    }

	   filterRows();
	    $("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
	    $("date_widget_dates").style.display = "";
	}
	else {
		alert("Please enter all required field!");	
	}
}

function deleteWidgetEvent ()
{
    widgetEvents[currentEventEdit] = -1;
    
    $("widgetEvent" + currentEventEdit).parentNode.removeChild($("widgetEvent" + currentEventEdit));
    
    $("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
    $("date_widget_dates").style.display = ""; 
}

function closeEvent ()
{
    $("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
    $("date_widget_dates").style.display = ""; 
}

function addEventRow (EventID, EventObj)
{
    var tr = document.createElement("tr");
    tr.className = EventObj[0] + "_" + EventObj[4] + "_date filter_dates";
    tr.style.display = "none";
    tr.id = "widgetEvent" + EventID;
    var label_td = document.createElement("td");
    label_td.className = "label_cell";
    var term_label = [];
    EventObj[5].each(function(i) {
        if (i.length < 2) {
            i = old_terms[i];
        }
        term_label.push(terms[i]);
    });
    label_td.innerHTML = term_label.join(', ');
    tr.appendChild(label_td);

    var td = document.createElement("td");
    td.className = "data_cell";
    var aLink = document.createElement("a");
    aLink.href = "#";
    aLink.onclick = function ()
    {
        editWidgetEvent(this.parentNode.parentNode.id.replace(/widgetEvent/gi, ""));
        return false;
    }
    var startDate = new Date(EventObj[1]);
    var endDate = null;
    if (EventObj[2] != 0) {
        endDate = new Date(EventObj[2]);
    }

    var display = months[startDate.getMonth()] + " " + startDate.getDate() + ", " + startDate.getFullYear();
    if (endDate != null) {
        display += " - " + months[endDate.getMonth()] + " " + endDate.getDate() + ", " + endDate.getFullYear();
    }

    display += " - " + EventObj[3];

    if (EventObj[7] != null && EventObj[7].length > 1) {
        display += ' <img src="/images/newwindow.png" title="Link Added" />';
    }

    aLink.innerHTML =  display;
    aLink.title = display;
    td.appendChild(aLink);

    if (EventObj[6] == true) {
        var strong_highlight = document.createElement("strong");
        strong_highlight.innerHTML = " Highlighted";
        td.appendChild(strong_highlight);
        td.style.background = "#f0de84";
    }

    tr.appendChild(td);
    var id = $(EventObj[0] + "_" + EventObj[4]);
    id.insert({'after': tr});
}

function iterateEvents ()
{
    for (var i = 0; i < widgetEvents.length; i++) {
        addEventRow(i, widgetEvents[i]);
    }    
}

function fetchEvents ()
{
    widgetEvents.clear();
    for (var wEvent in widgetItems[activeWidget].settings) {
        if (wEvent.indexOf("event_year-") >= 0 && wEvent.length > 10) {
            var term_arr = widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_terms")].split(',');
            var event_url = null;
            if(widgetItems[activeWidget].settings.hasOwnProperty(wEvent.replace(/event_year/gi, "event_url"))) {
                event_url = widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_url")];
            }

            widgetEvents.push(new Array(widgetItems[activeWidget].settings[wEvent], 
                                        widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_start_date")], 
                                        widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_end_date")],
                                        widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_name")], 
                                        widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_semester")],
                                        term_arr,
                                        widgetItems[activeWidget].settings[wEvent.replace(/event_year/gi, "event_highlight")],
                                        event_url));
        }
    }	
}

function commitWidgetEvents ()
{
    widgetItems[activeWidget].settings = new Object();

    for (var i = 0; i < widgetEvents.length; i++) {
    	if (widgetEvents[i][0] != undefined) {
	        widgetItems[activeWidget].settings["event_year-" + i] = widgetEvents[i][0];
	        widgetItems[activeWidget].settings["event_start_date-" + i] = widgetEvents[i][1];
            widgetItems[activeWidget].settings["event_end_date-" + i] = widgetEvents[i][2];
			widgetItems[activeWidget].settings["event_name-" + i] = widgetEvents[i][3];
	        widgetItems[activeWidget].settings["event_semester-" + i] = widgetEvents[i][4];
	        widgetItems[activeWidget].settings["event_terms-" + i] = widgetEvents[i][5].join(',');
            widgetItems[activeWidget].settings["event_highlight-" + i] = widgetEvents[i][6];
            widgetItems[activeWidget].settings["event_url-" + i] = widgetEvents[i][7];
    	}
    }

    $("event_semester").parentNode.removeChild($("event_semester"));
	$$(".event_terms").each(function(ele) {
        var ele_id = ele.value;
        ele.parentNode.removeChild($(ele_id));
    });
    $("event_start_date").parentNode.removeChild($("event_start_date"));
    $("event_end_date").parentNode.removeChild($("event_end_date"));
    $("event_name").parentNode.removeChild($("event_name"));
    $("event_highlight").parentNode.removeChild($("event_highlight"));
    $("event_url").parentNode.removeChild($("event_url"));
    $("event_year").parentNode.removeChild($("event_year"));
}

function cleanWidgetItemsArray() {
    for (var wEvent in widgetItems[activeWidget].settings) {
        if (wEvent.indexOf('event_') > 0) {
            console.log(wEvent);
        }
    }
}

function filterRows(filterSem) {
    var year = $("picker_year").value;
    var semester;
    if(filterSem) {
        semester = $("filterSemester").value;
        $("picker_semester").value = semester;
    } else {
        semester = $("picker_semester").value;
    }

    // Hide all of the dates.
    $$(".filter_dates").each(Element.hide);

    // Show all dates and the header.
    $(year + "_" + semester).show();
    var dates = $$("tr." + year + "_" + semester + "_date");
    dates.each(Element.show);
}