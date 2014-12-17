function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();

function sendemail() {
	var msg = document.contactform.msg.value;
	var name = document.contactform.name.value;
	var email = document.contactform.email.value;
	document.contactform.send.disabled=true; 
	document.contactform.send.value='Sending....';

    http.open('get', 'sendmail.php?msg='+msg+'&name='+name+'&email='+email+'&action=send');
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
    if(http.readyState == 4){
        var response = http.responseText;
        var update = new Array();

        if(response.indexOf('|' != -1)) {
            update = response.split('|');
            document.getElementById(update[0]).innerHTML = update[1];
         
        }
    }
}