if ($("tbl_widget_content").getElementsByTagName("tfoot")[0]) {
	$("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
}	
var currentEventEdit = -1;
var terms = [
    '16 Week',
    '12 Week',
    '8 Week'
]

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


function addWidgetEvent ()
{
    currentEventEdit = -1;
    $("event_year").value = "";
    $("event_start_month").value = "";
    $("event_start_day").value = "";
    $("event_end_month").value = "";
    $("event_end_day").value = "";
	$("event_name").value = "";
    $("event_semester").value = "";
    $$(".event_terms").each(function(ele) {
        ele.checked = 0;
    });
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
        $("event_start_month").value = widgetEvents[currentEventEdit][1];
    }
    if (widgetEvents[currentEventEdit][2] != null) {
        $("event_start_day").value = widgetEvents[currentEventEdit][2];
    }
    if (widgetEvents[currentEventEdit][3] != null) {
        $("event_end_month").value = widgetEvents[currentEventEdit][4];
    }
    if (widgetEvents[currentEventEdit][4] != null) {
        $("event_end_day").value = widgetEvents[currentEventEdit][4];
    }
	if (widgetEvents[currentEventEdit][5] != null) {
        $("event_name").value = widgetEvents[currentEventEdit][5];
    }
    if (widgetEvents[currentEventEdit][6] != null) {
        $("event_semester").value = widgetEvents[currentEventEdit][6];
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
    else if ($("event_start_month").value.length == 0) {
        errors = true;
    }
    else if ($("event_start_day").value.length == 0) {
        errors = true;
    }
    else if ($("event_end_month").value.length == 0) {
        $("event_end_month").value = 0;
    }
    else if ($("event_end_day").value.length == 0) {
        $("event_end_day").value = 0;
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
	    if (currentEventEdit == -1) {
	        widgetEvents.push(new Array($("event_year").value, 
                                        $("event_start_month").value, 
                                        $("event_start_day").value,
                                        $("event_end_month").value,
                                        $("event_end_day").value, 
                                        $("event_name").value, 
                                        $("event_semester").value, 
                                        eventTerms));
	        // add new row
	        addEventRow (widgetEvents.length - 1, widgetEvents[widgetEvents.length - 1]);
	    }
	    else {
            widgetEvents[currentEventEdit][0] = $("event_year").value;
	        widgetEvents[currentEventEdit][1] = $("event_start_month").value;
            widgetEvents[currentEventEdit][2] = $("event_start_day").value;
            widgetEvents[currentEventEdit][3] = $("event_end_month").value;
            widgetEvents[currentEventEdit][4] = $("event_end_day").value;
			widgetEvents[currentEventEdit][5] = $("event_name").value;
	        widgetEvents[currentEventEdit][6] = $("event_semester").value;
			widgetEvents[currentEventEdit][7] = eventTerms;
	    }
	
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
    tr.className = EventObj[0] + "_" + EventObj[6] + "_date filter_dates";
    tr.style.display = "none";
    tr.id = "widgetEvent" + EventID;
    var label_td = document.createElement("td");
    label_td.className = "label_cell";
    var term_label = [];
    EventObj[7].each(function(i) {
        console.log(terms[i]);
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
    var display = EventObj[1] + " " + EventObj[2];
    if (EventObj[1] != EventObj[3] && EventObj[3] != 0) {
        display += " - " + EventObj[3];
    } else {
        display += " - ";
    }
    if (EventObj[4] != 0) { 
        display += " " + EventObj[4] + " - ";
    }
    aLink.innerHTML =  display + EventObj[5];
    aLink.title = display + EventObj[5];
    td.appendChild(aLink);
    tr.appendChild(td);
    var id = $(EventObj[0] + "_" + EventObj[6]);
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
        if (wEvent.indexOf("event_year") >= 0) {
            var arr_id = wEvent.substring(0, wEvent.lastIndexOf("-"));
            var term_arr = JSON.parse(widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_terms")]);
            console.log(term_arr);
            widgetEvents.push(new Array(widgetItems[activeWidget].settings[arr_id], 
                                        widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_start_month")], 
                                        widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_start_day")],
                                        widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_end_month")],
                                        widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_end_day")], 
                                        widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_name")], 
                                        widgetItems[activeWidget].settings[arr_id.replace(/event_year/gi, "event_semester")], 
                                        term_arr));
        }
    }	
}

function commitWidgetEvents ()
{
    widgetItems[activeWidget].settings = new Object();

    for (var i = 0; i < widgetEvents.length; i++) {
    	if (widgetEvents[i][0] != undefined) {
	        widgetItems[activeWidget].settings["event_year-" + i] = widgetEvents[i][0];
	        widgetItems[activeWidget].settings["event_start_month-" + i] = widgetEvents[i][1];
            widgetItems[activeWidget].settings["event_start_day-" + i] = widgetEvents[i][2];
            widgetItems[activeWidget].settings["event_end_month-" + i] = widgetEvents[i][3];
            widgetItems[activeWidget].settings["event_end_day-" + i] = widgetEvents[i][4];
			widgetItems[activeWidget].settings["event_name-" + i] = widgetEvents[i][5];
	        widgetItems[activeWidget].settings["event_semester-" + i] = widgetEvents[i][6];
	        widgetItems[activeWidget].settings["event_terms-" + i] = JSON.stringify(widgetEvents[i][7]);
    	}
    }

    $("event_semester").parentNode.removeChild($("event_semester"));
	$$(".event_terms").each(function(ele) {
        var ele_id = "term-" + ele.value;
        ele.parentNode.removeChild($(ele_id));
    });
    $("event_start_day").parentNode.removeChild($("event_start_day"));
    $("event_start_month").parentNode.removeChild($("event_start_month"));
    $("event_end_day").parentNode.removeChild($("event_end_day"));
    $("event_end_month").parentNode.removeChild($("event_end_month"));
    $("event_name").parentNode.removeChild($("event_name"));
}

function filterRows() {
    var year = $("picker_year").value;
    var semester = $("picker_semester").value;

    // Hide all of the dates.
    $$(".filter_dates").each(Element.hide);

    // Show all dates and the header.
    $(year + "_" + semester).show();
    var dates = $$("tr." + year + "_" + semester + "_date");
    dates.each(Element.show);
}