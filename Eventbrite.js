/*
* Author: Yan Ma
* Date: Sep. 29. 2015
*
*/

function listOrganizers() {
	var xmlhttp;
	var organizersObj;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","https://www.eventbriteapi.com/v3/users/UserID/organizers/?format=json",true);
	xmlhttp.setRequestHeader("Authorization","Bearer TOKEN");
	xmlhttp.setRequestHeader("Content-Type","application/json");
    	xmlhttp.setRequestHeader("Accept","application/json");
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			organizersObj = JSON.parse(xmlhttp.responseText)["organizers"];
			for (var i = 0; i < organizersObj.length; i++) {
				var org = organizersObj[i];
				var name = org["name"];
				var id = org["id"];
				var option = document.createElement("option");
				option.text = name;
				option.value = id;
				var select = document.getElementById("org");
				select.appendChild(option);
			}
	    }
	} 
}

var pages = 1;
var page = 1;

function listAllEvents() {
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "https://www.eventbriteapi.com/v3/users/UserID/owned_events/?format=json&status=live&order_by=start_asc&expand=organizer";

	listEventsWithPageCount(xmlhttp, url, page);

}

function listEventsWithPageCount(xmlhttp, url, page) {
	var eventlist;
	var newurl = url;
	if (page > 1) {
		newurl = url + "&page=" + page;
	}
	xmlhttp.open("GET",newurl,true);	
	xmlhttp.setRequestHeader("Authorization","Bearer TOKEN");
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			if (page == 1) {
				document.getElementById("preload").innerHTML = "";
				pages = JSON.parse(xmlhttp.responseText)["pagination"]["page_count"];
			}
			eventlist = JSON.parse(xmlhttp.responseText)["events"];
			showEventList(eventlist);
			if (page < pages) {
				listEventsWithPageCount(xmlhttp, url, page + 1);
			}
	    }
	}
}

function showEventList(eventlist) {
	var tbody = document.getElementById("target");
	if (eventlist.length == 0) {
		var row = document.createElement("tr");
		row.innerHTML = "<td colspan='5'>No workshop available.</td>";
		tbody.appendChild(row);
	}
	for (var i = 0; i < eventlist.length; i++) {
		var eventItem = eventlist[i];
		var date = parseDate(eventItem["start"]["utc"]);
		var title = eventItem["name"]["text"];
		var starttime = parseTime(eventItem["start"]["utc"]);
		var endtime = parseTime(eventItem["end"]["utc"]);
		var org = eventItem["organizer"]["name"];
		var url = eventItem["url"];
		var row = document.createElement("tr");
		var datecol = document.createElement("td");
		var titlecol = document.createElement("td");
		var timeDurationcol = document.createElement("td");
		var orgcol = document.createElement("td");
		var urlcol = document.createElement("td");
		datecol.innerHTML = date;
		titlecol.innerHTML = title;
		timeDurationcol.innerHTML = starttime + " to " + endtime;
		orgcol.innerHTML = org;
		urlcol.innerHTML = "<a href='" + url + "' target='_blank'> <img src='https://www.eventbrite.com/custombutton?eid=10869283319' alt='" + title + "' /></a>";
		row.appendChild(datecol);
		row.appendChild(titlecol);
		row.appendChild(timeDurationcol);
		row.appendChild(orgcol);
		row.appendChild(urlcol);
		tbody.appendChild(row);
	}
}

function parseDate(datestring) {
	var string = datestring;
	var milliseconds = Date.parse(string);
	var date = new Date(milliseconds);
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	if (month < 10) {
		month = "0" + month;
	}
	var day = date.getDate();
	if (day < 10) {
		day = "0" + day;
	}
	return month + "/" + day + "/" + year;
}

function parseTime(datestring) {
	var string = datestring;
	var milliseconds = Date.parse(string);
	var date = new Date(milliseconds);
	var hour = date.getHours();
	var apm = "AM";
	if (hour >= 12) {
		apm = "PM";
	}
	if (hour > 12) {
		hour -= 12;
	}
	var minute = date.getMinutes();
	if (minute < 10) {
		minute = "0" + minute;
	}
	return hour + ":" + minute + " " + apm;
}

var fpages = 1;
var fpage = 1;

function filterByOrg() {
	var xmlhttp;
	var organizersObj;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var index = document.getElementById("org").selectedIndex;
	var url = "https://www.eventbriteapi.com/v3/events/search/?format=json&sort_by=date&user.id=UserID&expand=organizer";
	if (index > 0) {
		var option = document.getElementById("org").options[index].value; //org id
		url += "&organizer.id=" + option;
	}

	var search = document.getElementById("searchEvents").value;
	if (search.length > 0) {
		url += "&q=" + search;
	}
	listFilteredEventsWithPageCount(xmlhttp, url, fpage);
}

function listFilteredEventsWithPageCount(xmlhttp, url, fpage) {
	var eventlist;
	var newurl = url;
	if (fpage > 1) {
		newurl = url + "&page=" + fpage;
	}
	xmlhttp.open("GET",newurl,true);	
	xmlhttp.setRequestHeader("Authorization","Bearer TOKEN");
	xmlhttp.send();
	var tbody = document.getElementById("target");
	
	if (fpage == 1) {
		while (tbody.hasChildNodes()) {
			tbody.removeChild(tbody.firstChild);
		}
		var domain = document.location.host;
		document.getElementById("preload").innerHTML = "<img src='http://" + domain + "/loading.gif'>";
		document.getElementById("preload").innerHTML += "<label>Searching workshops......</label>";
	}
	
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			if (fpage == 1) {
				document.getElementById("preload").innerHTML = "";
				fpages = JSON.parse(xmlhttp.responseText)["pagination"]["page_count"];
			}
			eventlist = JSON.parse(xmlhttp.responseText)["events"];
			showEventList(eventlist);
			if (fpage < fpages) {
				listFilteredEventsWithPageCount(xmlhttp, url, fpage + 1);
			}
	    }
	}
}





