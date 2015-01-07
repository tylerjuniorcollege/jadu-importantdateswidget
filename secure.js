if ($("tbl_widget_content").getElementsByTagName("tfoot")[0]) {
	$("tbl_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
}	
var currentImageEdit = -1;

var widgetImages = new Array();
var oldsave = $("saveWidgetProperty").onclick;

if (typeof $("saveWidgetProperty").onclick != "function") {
    $("saveWidgetProperty").onclick = commitwidgetImages;
}
else {
    $("saveWidgetProperty").onclick = function ()
    {
        commitwidgetImages();
        oldsave();
    }
}

// Load image carousel widget images into sub table

fetchImages();
iterateImages();
$('image_carousel_widget_images').show();


function addWidgetImage ()
{
    currentImageEdit = -1;
    $("image_carousel_image").value = "";
	$("image_carousel_imagei").src= "../images/no_image.gif";
    $("image_carousel_button_title").value = "";
	$("image_carousel_button_subtitle").value = "";
    $("image_carousel_link").value = "";
    $("image_carousel_link_title").value = "";
    $("lb_widget_content").getElementsByTagName("tfoot")[0].style.display = "";
    $("image_carousel_widget_images").style.display = "none";
    $("widgetImageDelete").style.display = "none";
}

function editWidgetImage (widgetImageID)
{	
	currentImageEdit = widgetImageID;
	
    if (widgetImages[currentImageEdit][0] != null) {
        $("image_carousel_image").value = widgetImages[currentImageEdit][0];
    }
    if (widgetImages[currentImageEdit][0] != null) {
        $("image_carousel_imagei").src = 'http://'+$('DOMAIN').value+'/images/'+widgetImages[currentImageEdit][0];
    }
    if (widgetImages[currentImageEdit][1] != null) {
        $("image_carousel_button_title").value = widgetImages[currentImageEdit][1];
    }
	 if (widgetImages[currentImageEdit][2] != null) {
        $("image_carousel_button_subtitle").value = widgetImages[currentImageEdit][2];
    }
    if (widgetImages[currentImageEdit][3] != null) {
        $("image_carousel_link").value = widgetImages[currentImageEdit][3];
    }
    if (widgetImages[currentImageEdit][4] != null) {
        $("image_carousel_link_title").value = widgetImages[currentImageEdit][4];
    }
    $("lb_widget_content").getElementsByTagName("tfoot")[0].style.display = "";
    $("image_carousel_widget_images").style.display = "none";
    $("widgetImageDelete").style.display = "";
}

function saveWidgetImage ()
{
	// check all required fields have been entered
	var errors = false;
	
	if ($("image_carousel_button_title").value.length == 0) {
		errors = true;
	}
	else if ($("image_carousel_button_subtitle").value.length == 0) {
		errors = true;
	}
        else if ($("image_carousel_link").value.length == 0) {
		errors = true;
	}
        else if ($("image_carousel_link_title").value.length == 0) {
		errors = true;
	}
	
	
	if (!errors) {
	    if (currentImageEdit == -1) {
	        widgetImages.push(new Array($("image_carousel_image").value, $("image_carousel_button_title").value, $("image_carousel_button_subtitle").value, $("image_carousel_link").value, $("image_carousel_link_title").value));
	        // add new row
	        addImageRow (widgetImages.length - 1, widgetImages[widgetImages.length - 1]);
	    }
	    else {
	        widgetImages[currentImageEdit][0] = $("image_carousel_image").value;
	        widgetImages[currentImageEdit][1] = $("image_carousel_button_title").value;
			widgetImages[currentImageEdit][2] = $("image_carousel_button_subtitle").value;
	        widgetImages[currentImageEdit][3] = $("image_carousel_link").value;
			widgetImages[currentImageEdit][4] = $("image_carousel_link_title").value;
	        $("widgetImageText" + currentImageEdit).title = $("image_carousel_button_title").value;
	        $("widgetImageText" + currentImageEdit).innerHTML = $("image_carousel_button_title").value;
	    }
	
	    $("lb_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
	    $("image_carousel_widget_images").style.display = "";
	}
	else {
		alert("Please enter all required field!");	
	}
}



function deleteWidgetImage ()
{
    widgetImages[currentImageEdit] = -1;
    
    $("widgetImageText" + currentImageEdit).parentNode.parentNode.parentNode.removeChild($("widgetImageText" + currentImageEdit).parentNode.parentNode);
    
    $("lb_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
    $("image_carousel_widget_images").style.display = ""; 
}

function closeSlide ()
{
    $("lb_widget_content").getElementsByTagName("tfoot")[0].style.display = "none";
    $("image_carousel_widget_images").style.display = ""; 
}

function addImageRow (ImageID, ImageObj)
{
    var tr = document.createElement("tr");
    var td = document.createElement("td");
    td.className = "label_cell";
    var aLink = document.createElement("a");
    aLink.id = "widgetImageText" + ImageID;
    aLink.href = "#";
    aLink.onclick = function ()
    {
        editWidgetImage(this.id.replace(/widgetImageText/gi, ""));
        return false;
    }
    aLink.innerHTML = ImageObj[1];
    aLink.title = ImageObj[1];
    td.appendChild(aLink);
    tr.appendChild(td);
    td = document.createElement("td");
    td.className = "data_cell";
    var moveButton = document.createElement("a");
    moveButton.href = "#";
    moveButton.id = "widgetLinkDownText" + ImageID;
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
    moveButton.id = "widgetLinkUpText" + ImageID;
    moveButton.innerHTML = "Move Up";
    moveButton.onclick = function ()
    {
        moveUp(this.id.replace(/widgetLinkUpText/gi, ""));
        return false;
    }
    td.appendChild(moveButton);
    tr.appendChild(td);
    $("image_carousel_widget_images").appendChild(tr);
}

function iterateImages ()
{
    for (var i = 0; i < widgetImages.length; i++) {
        addImageRow(i, widgetImages[i]);
    }    
}

function fetchImages ()
{
    widgetImages.clear();
    for (var wImage in widgetItems[activeWidget].settings) {
        if (wImage.indexOf("image") >= 0 && wImage.indexOf("buttonTitle") >= 0) {
            widgetImages.push(new Array(widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "imageSrc")], widgetItems[activeWidget].settings[wImage], widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "buttonSubtitle")], widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "link")], widgetItems[activeWidget].settings[wImage.replace(/buttonTitle/gi, "link_title")]));
        }
    }	
}

function commitwidgetImages ()
{
    widgetItems[activeWidget].settings = new Object();

    for (var i = 0; i < widgetImages.length; i++) {
    	if (widgetImages[i][0] != undefined) {
	        widgetItems[activeWidget].settings["image" + i + "imageSrc"] = widgetImages[i][0];
	        widgetItems[activeWidget].settings["image" + i + "buttonTitle"] = widgetImages[i][1];
			widgetItems[activeWidget].settings["image" + i + "buttonSubtitle"] = widgetImages[i][2];
	        widgetItems[activeWidget].settings["image" + i + "link"] = widgetImages[i][3];
	        widgetItems[activeWidget].settings["image" + i + "link_title"] = widgetImages[i][4];
    	}
    }

    $("image_carousel_image").parentNode.removeChild($("image_carousel_image"));
    $("image_carousel_link").parentNode.removeChild($("image_carousel_link"));
	$("image_carousel_link_title").parentNode.removeChild($("image_carousel_link_title"));
    $("image_carousel_button_title").parentNode.removeChild($("image_carousel_button_title"));
    $("image_carousel_button_subtitle").parentNode.removeChild($("image_carousel_button_subtitle"));
}

function moveUp (widgetImageID)
{
    var tempLink = null;
    if (widgetImageID > 0) {
        tempLink = widgetImages[widgetImageID - 1];
        widgetImages[widgetImageID - 1] = widgetImages[widgetImageID];
        widgetImages[widgetImageID] = tempLink;
        if ( $("image_carousel_widget_images").hasChildNodes() ) {
            while ( $("image_carousel_widget_images").childNodes.length >= 1 ) {
                $("image_carousel_widget_images").removeChild( $("image_carousel_widget_images").firstChild );       
            } 
        }
        iterateImages();
    }
}

function moveDown (widgetImageID)
{
    var tempLink = null;
    widgetImageID = parseInt(widgetImageID);
    if (widgetImageID < widgetImages.length - 1) {
        tempLink = widgetImages[widgetImageID + 1];
        widgetImages[widgetImageID + 1] = widgetImages[widgetImageID];
        widgetImages[widgetImageID] = tempLink;
        if ($("image_carousel_widget_images").hasChildNodes()) {
	        while ($("image_carousel_widget_images").childNodes.length >= 1) {
                $("image_carousel_widget_images").removeChild($("image_carousel_widget_images").firstChild);       
            } 
        }
        iterateImages();
    }
}