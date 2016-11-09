
// at first check the cookie
var cookie = document.cookie;

var psdnym = '';
var user = null;
var firstSurveyURL = "https://surveys.informatik.uni-ulm.de/limesurvey/index.php/617829";

checkCookie();
setScreenResCookie();
setBreadcrumps();


function setScreenResCookie () {
    date = new Date();
    date.setTime(date.getTime()+(7*24*60*60*1000));
    document.cookie = "res=" + window.innerWidth + "x" + window.innerHeight + ";expires=" + date.toGMTString();
}

function checkCookie () {

    if (cookie == undefined || cookie == '') {

        psdnym = prompt('Bitte geb dein Kürzel ein', 'Erster Buchstabe Name zweiter Nachname und Geburtsjahr');
        var requestSet = new XMLHttpRequest();
        requestSet.open("POST","scripts/setUser.php");
        //requestSet.setRequestHeader("pseudonym",psdnym);
        //TODO check wenn abbrechen

        requestSet.addEventListener('load', function(event) {
            if (requestSet.status >= 200 && requestSet.status < 300) {
                console.log(requestSet.responseText);
                setFirstCookie(psdnym);
            } else {
                console.warn(requestSet.statusText, requestSet.responseText);
            }
        });
        requestSet.send(psdnym);

        // Set cookie

    } else {
        psdnym = getCookie("beercrate_routing_pseudonym");
    }
}

function setBreadcrumps () {
    // Ajax call to server to collect user data
    var request = new XMLHttpRequest();
    request.open("POST","scripts/getUser.php");
    request.setRequestHeader("pseudonym",psdnym);

    request.addEventListener('load', function(event) {
        if (request.status >= 200 && request.status < 300) {
            try {
                user = JSON.parse(request.responseText);
            } catch (errr) {
                console.warn("get User:" + request.responseText );
                return;
            }

            //deleteCookies();
            //setCookie(user.pseudonym, user.progress, user.version);

            console.info(user);

            for (var i = 0; i <= user.progress; i++) {

                var classes = document.getElementById("breadcrump" + i).classList;

                if (classes.contains("unfinished")) {

                    classes.remove("unfinished");
                    classes.add("active");

                }

                classes.add("finished");

            }


            var classesSurvey = document.getElementById("survey").classList;
            var classesPara = document.getElementById("paragraph_code").classList;
            var classesCode = document.getElementById("code").classList;
            var classesForms = document.getElementById("forms").classList;
            var classesExam = document.getElementById("form_exam").classList;
            var classesExercise = document.getElementById("form_exercise").classList;
            var classesParaForms = document.getElementById("para_forms").classList;

            var classesDownload = document.getElementById("button_download").classList;
            var buttonDownload = document.getElementById("button_download");

            switch (user.progress) {
                case 0:
                    //first survey
                    setSurveyLink(user.pseudonym, (user.progress + 1), firstSurveyURL);
                    classesSurvey.remove("hidden");

                    break;
                case 1:
                    classesSurvey.add("hidden");
                    classesDownload.remove("hidden"); //TODO link tauschen und version checken
                    buttonDownload.innerHTML = '<a href="download/readme.txt" class="button" id="button_download">Download Spiel 1</a>';
                    break;
                case 2:
                    classesSurvey.remove("hidden");
                    classesDownload.add("hidden");
                    setSurveyLink(user.pseudonym, (user.progress + 1), firstSurveyURL);
                    break;
                case 3:
                    classesSurvey.add("hidden");
                    classesDownload.remove("hidden");//TODO link tauschen und version checken
                    buttonDownload.innerHTML = '<a href="download/readme.txt" class="button" id="button_download">Download Spiel 2</a>';
                    break;
                case 4:
                    classesSurvey.remove("hidden");
                    classesDownload.add("hidden");
                    setSurveyLink(user.pseudonym, (user.progress + 1), firstSurveyURL);
                    break;
                case 5:
                    classesSurvey.add("hidden");
                    classesDownload.add("hidden");
                    classesPara.remove("hidden");
                    classesCode.remove("hidden");
                    classesForms.remove("hidden");
                    if (user.exam != null && user.exam != '') {
                        classesExam.add("hidden");
                    } else {
                        classesExam.remove("hidden");
                    }
                    if (user.exercise != null && user.exercise != '') {
                        classesExercise.add("hidden");
                    } else {
                        classesExercise.remove("hidden");
                    }
                    if (user.exercise != null && user.exam != '') {
                        classesParaForms.add("hidden");
                    } else {
                        classesParaForms.remove("hidden");
                    }
                    document.getElementById("code").innerHTML = "<h2>" + user.code + "</h2>"; // TODO richtiger code
                    break;

            }
        } else {
            console.warn(request.statusText, request.responseText);
        }
    });
    request.send(psdnym);
}

function setFirstCookie (psdnym) {
    var rand = Math.random() >= 0.5;
    setCookie(psdnym);
}

function setCookie (psdnym) {

    var name = "beercrate_routing_";

    date = new Date();
    date.setTime(date.getTime()+(7*24*60*60*1000));

    document.cookie = name + "pseudonym=" + psdnym + ";expires=" + date.toGMTString();
    document.cookie = "res=" + screen.width + "x" + screen.height + ";expires=" + date.toGMTString();
}

function getCookie (cname) {

    var name = cname + "=";
    var ca = document.cookie.split(';');

    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deleteCookie (name) {
    document.cookie = name; // TODO Datum
}

function deleteCookies () {
    deleteCookie("beercrate_routing_pseudonym");
}

function setSurveyLink (pseudonym, progress, surveyURL) {
    var http = new XMLHttpRequest();
    var url = "/scripts/generateUpdateLink.php";
    var params =
        "ps=" + pseudonym +
        "&pr=" + progress;
    http.open("POST", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function() {

        if(http.readyState == 4 && http.status == 200) {

            var domainName = window.location.href.toString();
            domainName = domainName.replace('http://', '').replace('https://', '');
            var slashPos = domainName.indexOf('/');
            domainName = domainName.substr(0,slashPos);

            document.getElementById("survey").href =
                surveyURL
                + "?dn=" + domainName +
                + "&ul=" + http.responseText;
        }
    };
    http.send(params);
}




