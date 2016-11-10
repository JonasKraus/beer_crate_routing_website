
    isConsentForm = false;
    isInputForm = false;
    psdnym = null;

    document.addEventListener('DOMContentLoaded', function () {
        inputChangeHandler();
        checkboxChangeHandler();
        setButtonListener();
    });

    checkReturningUser();
    checkConsentForm();
    checkInputForm();


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

function checkReturningUser () {
    var cookie = document.cookie;

    var psdnym = null;

    // Check if returning participant
    if (cookie != undefined && cookie != '') {
        psdnym = getCookie("beercrate_routing_pseudonym");
        if (psdnym != undefined && psdnym != '' && psdnym != null ) {
            window.location.href="index.html";
        }
    }
}

function checkConsentForm () {

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('#checkboxConsent').addEventListener('change', checkboxChangeHandler);
    });
}

function checkInputForm () {

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('#pseudonymForm').addEventListener('change', inputChangeHandler);
    });
}

function inputChangeHandler () {
    var input = document.getElementById("pseudonymForm");
    var buttonStart = document.getElementById("buttonStart");
    isInputForm = !(input.value === ""); // TODO Check correct form
    psdnym = input.value;
}

function checkboxChangeHandler () {
    var checkbox = document.getElementById("checkboxConsent");

    var buttonStart = document.getElementById("buttonStart");
    isConsentForm = checkbox.checked;
}

function validateForms () {
    console.info('validate');
    checkConsentForm();
    checkInputForm();

    if (isInputForm && isConsentForm) {
        setUser();
    }

    if (!isConsentForm) {
        showSnackbar("Bitte akzeptiere die Vereinbarungen");
    }
    if (!isInputForm) {
        showSnackbar("Btte geb eine gültige Probanden-ID ein")
    }
 }

function setUser () {
    var request = new XMLHttpRequest();
    var url = "scripts/setUser.php";
    var params = "pseudonym=" + psdnym;
    request.open("POST", url, true);

    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    request.addEventListener('load', function(event) {
        if (request.status >= 200 && request.status < 300) {
            console.log(request.responseText);
            checkReturningUser();
        } else {
            console.warn(request.statusText, request.responseText);
        }
    });
    request.send(params);
}

function setButtonListener () {

    var buttonStart = document.getElementById("buttonStart").onclick = validateForms;

}

function showSnackbar (message) {

    var x = document.getElementById("snackbar");
    x.innerHTML=message;

    x.className = "show";

    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}