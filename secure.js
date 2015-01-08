if ($("tbl_widget_content").getElementsByTagName("tfoot")[0]) {
	$("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
}	
var currentEventEdit = -1;

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
    $("event_date").value = "";
	$("event_name").value = "";
    $("event_semester").value = "";
    $$(".event_terms").value = "";
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
        $("event_date").value = widgetEvents[currentEventEdit][1];
    }
	if (widgetEvents[currentEventEdit][2] != null) {
        $("event_name").value = widgetEvents[currentEventEdit][2];
    }
    if (widgetEvents[currentEventEdit][3] != null) {
        $("event_semester").value = widgetEvents[currentEventEdit][3];
    }
    if (widgetEvents[currentEventEdit][4] != null) {
        $$(".event_terms").value = widgetEvents[currentEventEdit][4];
    }
    $("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "";
    $("date_widget_dates").style.display = "none";
    $("widgetEventDelete").style.display = "";
}

function saveWidgetEvent ()
{
	// check all required fields have been entered
	var errors = false;
	
	if ($("event_date").value.length == 0) {
		errors = true;
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
    else if ($$(".event_terms").value.length == 0) {
		errors = true;
	}
	
	
	if (!errors) {
	    if (currentEventEdit == -1) {
	        widgetEvents.push(new Array($("event_year").value, $("event_date").value, $("event_name").value, $("event_semester").value, $$(".event_terms").value));
	        // add new row
	        addEventRow (widgetEvents.length - 1, widgetEvents[widgetEvents.length - 1]);
	    }
	    else {
            widgetEvents[currentEventEdit][0] = $("event_year").value;
	        widgetEvents[currentEventEdit][1] = $("event_date").value;
			widgetEvents[currentEventEdit][2] = $("event_name").value;
	        widgetEvents[currentEventEdit][3] = $("event_semester").value;
			widgetEvents[currentEventEdit][4] = $$(".event_terms").value;
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
    
    $(currentEventEdit).parentNode.parentNode.parentNode.removeChild($(currentEventEdit).parentNode.parentNode);
    
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
    var td = document.createElement("td");
    td.className = "label_cell";
    var aLink = document.createElement("a");
    aLink.id = "widgetEvent" + EventID;
    aLink.href = "#";
    aLink.onclick = function ()
    {
        editWidgetImage(this.id.replace(/widgetEvent/gi, ""));
        return false;
    }
    aLink.innerHTML = EventObj[2];
    aLink.title = EventObj[2];
    td.appendChild(aLink);
    tr.appendChild(td);
    td = document.createElement("td");
    td.className = "data_cell";
    var moveButton = document.createElement("a");
    moveButton.href = "#";
    moveButton.id = "widgetLinkDownText" + EventID;
    moveButton.innerHTML = "Move Down";
    moveButton.onclick = function ()
    {
        moveDown(this.id.replace(/widgetLinkDownText/gi, ""));
        return false;
    }
    td.appendChild(moveButton);
	td.appendChild(document.createTextNode("\u00a0"));
    moveButton = document.createElement("a");
    moveButton.href = "#";
    moveButton.id = "widgetLinkUpText" + EventID;
    moveButton.innerHTML = "Move Up";
    moveButton.onclick = function ()
    {
        moveUp(this.id.replace(/widgetLinkUpText/gi, ""));
        return false;
    }
    td.appendChild(moveButton);
    tr.appendChild(td);
    $("date_widget_dates").appendChild(tr);
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
    for (var wImage in widgetItems[activeWidget].settings) {
        if (wImage.indexOf("image") >= 0 && wImage.indexOf("buttonTitle") >= 0) {
            widgetEvents.push(new Array(widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "imageSrc")], widgetItems[activeWidget].settings[wImage], widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "buttonSubtitle")], widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "link")], widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "link_title")]));
        }
    }	
}

function commitWidgetEvents ()
{
    widgetItems[activeWidget].settings = new Object();

    for (var i = 0; i < widgetEvents.length; i++) {
    	if (widgetEvents[i][0] != undefined) {
	        widgetItems[activeWidget].settings["event" + i + "event_year"] = widgetEvents[i][0];
	        widgetItems[activeWidget].settings["event" + i + "event_date"] = widgetEvents[i][1];
			widgetItems[activeWidget].settings["event" + i + "event_name"] = widgetEvents[i][2];
	        widgetItems[activeWidget].settings["event" + i + "event_semester"] = widgetEvents[i][3];
	        widgetItems[activeWidget].settings["event" + i + "event_term"] = widgetEvents[i][4];
    	}
    }

    $("event_semester").parentNode.removeChild($("event_semester"));
	$$(".event_terms").parentNode.removeChild($(".event_terms"));
    $("event_date").parentNode.removeChild($("event_date"));
    $("event_name").parentNode.removeChild($("event_name"));
}