function getCookie(e){for(var n=e+"=",t=document.cookie.split(";"),o=0;o<t.length;o++){for(var r=t[o];" "==r.charAt(0);)r=r.substring(1);if(0==r.indexOf(n))return r.substring(n.length,r.length)}return""}function checkReturningUser(){var e=document.cookie,n=null;void 0!=e&&""!=e&&(n=getCookie("beercrate_routing_pseudonym"),void 0!=n&&""!=n&&null!=n&&(window.location.href="index.html"))}function checkConsentForm(){document.addEventListener("DOMContentLoaded",function(){document.querySelector("#checkboxConsent").addEventListener("change",checkboxChangeHandler)})}function checkInputForm(){document.addEventListener("DOMContentLoaded",function(){document.querySelector("#pseudonymForm").addEventListener("change",inputChangeHandler)})}function inputChangeHandler(){var e=document.getElementById("pseudonymForm");document.getElementById("buttonStart");isInputForm=!(""===e.value),psdnym=e.value.toUpperCase()}function checkboxChangeHandler(){var e=document.getElementById("checkboxConsent");document.getElementById("buttonStart");isConsentForm=e.checked}function validateForms(){checkConsentForm(),checkInputForm();var e=validatePseudonym(psdnym);isConsentForm?isInputForm?isInputForm&&isConsentForm&&e&&setUser():showSnackbar("Btte geb eine gültige Probanden-ID ein"):showSnackbar("Bitte akzeptiere die Vereinbarungen")}function setUser(){var e=new XMLHttpRequest,n="scripts/setUser.php",t="pseudonym="+psdnym;e.open("POST",n,!0),e.setRequestHeader("Content-type","application/x-www-form-urlencoded"),e.addEventListener("load",function(n){e.status>=200&&e.status<300&&checkReturningUser()}),e.send(t)}function setButtonListener(){document.getElementById("buttonStart").onclick=validateForms}function showSnackbar(e){var n=document.getElementById("snackbar");n.innerHTML=e,n.className="show",setTimeout(function(){n.className=n.className.replace("show","")},3e3)}function validatePseudonym(e){if(6!=e.length)return showSnackbar("Die Probanden-ID muss 6 Zeichen lang sein");var n=e.substring(0,4),t=/^[a-z]+$/i,o=e.substring(4,6),r=/^\d+$/;return t.test(n)?!!r.test(o)||showSnackbar("Die letzten 2 Zeichen der Probanden-ID sind Zahlen"):showSnackbar("Die ersten 4 Zeichen der Probanden-ID sind Buchstaben")}function getCookie(e){for(var n=e+"=",t=document.cookie.split(";"),o=0;o<t.length;o++){for(var r=t[o];" "==r.charAt(0);)r=r.substring(1);if(0==r.indexOf(n))return r.substring(n.length,r.length)}return""}function checkReturningUser(){var e=document.cookie,n=null;void 0!=e&&""!=e&&(n=getCookie("beercrate_routing_pseudonym"),void 0!=n&&""!=n&&null!=n&&(window.location.href="index.html"))}function checkConsentForm(){document.addEventListener("DOMContentLoaded",function(){document.querySelector("#checkboxConsent").addEventListener("change",checkboxChangeHandler)})}function checkInputForm(){document.addEventListener("DOMContentLoaded",function(){document.querySelector("#pseudonymForm").addEventListener("change",inputChangeHandler)})}function inputChangeHandler(){var e=document.getElementById("pseudonymForm");document.getElementById("buttonStart");isInputForm=!(""===e.value),psdnym=e.value.toUpperCase()}function checkboxChangeHandler(){var e=document.getElementById("checkboxConsent");document.getElementById("buttonStart");isConsentForm=e.checked}function validateForms(){checkConsentForm(),checkInputForm();var e=validatePseudonym(psdnym);isConsentForm?isInputForm?isInputForm&&isConsentForm&&e&&setUser():showSnackbar("Btte geb eine gültige Probanden-ID ein"):showSnackbar("Bitte akzeptiere die Vereinbarungen")}function setUser(){var e=new XMLHttpRequest,n="scripts/setUser.php",t="pseudonym="+psdnym;e.open("POST",n,!0),e.setRequestHeader("Content-type","application/x-www-form-urlencoded"),e.addEventListener("load",function(n){e.status>=200&&e.status<300&&checkReturningUser()}),e.send(t)}function setButtonListener(){document.getElementById("buttonStart").onclick=validateForms}function showSnackbar(e){var n=document.getElementById("snackbar");n.innerHTML=e,n.className="show",setTimeout(function(){n.className=n.className.replace("show","")},3e3)}function validatePseudonym(e){if(6!=e.length)return showSnackbar("Die Probanden-ID muss 6 Zeichen lang sein");var n=e.substring(0,4),t=/^[a-z]+$/i,o=e.substring(4,6),r=/^\d+$/;return t.test(n)?!!r.test(o)||showSnackbar("Die letzten 2 Zeichen der Probanden-ID sind Zahlen"):showSnackbar("Die ersten 4 Zeichen der Probanden-ID sind Buchstaben")}!function(){function e(){date=new Date,date.setTime(date.getTime()+6048e5),document.cookie="res="+window.innerWidth+"x"+window.innerHeight+";expires="+date.toGMTString()}function n(){if(void 0==d("beercrate_routing_pseudonym")||""==d("beercrate_routing_pseudonym")||null==d("beercrate_routing_pseudonym")){for(b=prompt("Hallo, falls du bereits eine Probanden-ID angelegt hast geb sie hier ein. Sonst klicke auf Abbrechen um eine anzulegen.");""==b;)n();null==b&&(window.location.href="../start.html"),b=b.toUpperCase()}else b=d("beercrate_routing_pseudonym").toUpperCase(),document.getElementById("loggedUser").innerHTML=b.toUpperCase()}function t(){var n=new XMLHttpRequest,t="scripts/getUser.php",r="pseudonym="+b;n.open("POST",t,!0),n.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.addEventListener("load",function(t){if(n.status>=200&&n.status<300){try{v=JSON.parse(n.responseText)}catch(e){return void(window.location.href="../start.html")}e(),document.getElementById("loggedUser").innerHTML=b.toUpperCase(),o(b.toUpperCase());for(var r=0;r<=v.progress;r++){var d=document.getElementById("breadcrump"+r).classList;d.contains("unfinished")&&(d.remove("unfinished"),d.add("active")),d.add("finished")}var a=document.getElementById("survey").classList,c=document.getElementById("paragraph_code").classList,u=document.getElementById("code").classList,m=document.getElementById("forms").classList,l=document.getElementById("form_exam").classList,h=document.getElementById("form_exercise").classList,p=document.getElementById("para_forms").classList,g=document.getElementById("button_download").classList,f=document.getElementById("para_download_explanation").classList,C=document.getElementById("button_download");switch(v.progress){case 0:s(v.pseudonym,v.progress+1,k),a.remove("hidden");break;case 1:a.add("hidden"),g.remove("hidden"),f.remove("hidden");var L="download/"+v.version+"/game.zip";C.innerHTML="<a href="+L+' class="button" id="button_download">Download Spiel 1</a>';break;case 2:a.remove("hidden"),g.add("hidden"),f.add("hidden"),s(v.pseudonym,v.progress+1,y);break;case 3:if(Date.parse(I)>Date.parse(new Date)){var B=new Date(I).getDate()+"."+new Date(I).getMonth()+"."+new Date(I).getFullYear();i("Du kannst erst ab dem "+B+" weiter machen."),a.add("hidden"),C.outerHTML="<p>Bitte warte bis zum <b>"+B+"</b> um fortzufahren.</p>"}else{a.add("hidden"),g.remove("hidden"),f.remove("hidden");var E="download/"+(v.version+1)%2+"/game.zip";C.innerHTML="<a href="+E+' class="button" id="button_download">Download Spiel 2</a>'}break;case 4:a.remove("hidden"),g.add("hidden"),f.add("hidden"),s(v.pseudonym,v.progress+1,w);break;case 5:a.add("hidden"),g.add("hidden"),c.remove("hidden"),u.remove("hidden"),m.remove("hidden"),null!=v.exam&&""!=v.exam?l.add("hidden"):l.remove("hidden"),null!=v.exercise&&""!=v.exercise?h.add("hidden"):h.remove("hidden"),null!=v.exercise&&""!=v.exam?p.add("hidden"):p.remove("hidden"),document.getElementById("code").innerHTML="<h2>"+v.code+"</h2>"}}else window.location.href="../start.html"}),n.send(r)}function o(e){Math.random()>=.5;r(e)}function r(e){var n="beercrate_routing_";date=new Date,date.setTime(date.getTime()+6048e5),document.cookie=n+"pseudonym="+e+";expires="+date.toGMTString(),document.cookie="res="+screen.width+"x"+screen.height+";expires="+date.toGMTString()}function d(e){for(var n=e+"=",t=document.cookie.split(";"),o=0;o<t.length;o++){for(var r=t[o];" "==r.charAt(0);)r=r.substring(1);if(0==r.indexOf(n))return r.substring(n.length,r.length)}return""}function s(e,n,t){var o=new XMLHttpRequest,r="scripts/generateUpdateLink.php",d=btoa(a(7)+JSON.stringify([btoa(e),btoa(n)])),s="fd="+d;o.open("POST",r,!0),o.setRequestHeader("Content-type","application/x-www-form-urlencoded"),o.onreadystatechange=function(){if(4==o.readyState&&200==o.status){var e=window.location.href.toString();e=e.replace("http://","").replace("https://","");var n=e.indexOf("/");e=e.substr(0,n),e=encodeURI(e),document.getElementById("survey").href=t+"?dn="+e.toString()+"&ul="+o.responseText}},o.send(s)}function a(e){for(var n="",t="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",o=0;o<e;o++)n+=t.charAt(Math.floor(Math.random()*t.length));return n}function i(e){var n=document.getElementById("snackbar");n.innerHTML=e,n.className="show",setTimeout(function(){n.className=n.className.replace("show","")},3e3)}function c(){document.getElementById("breadcrump0").onclick=u,document.getElementById("breadcrump1").onclick=m,document.getElementById("breadcrump2").onclick=l,document.getElementById("breadcrump3").onclick=h,document.getElementById("breadcrump4").onclick=p,document.getElementById("breadcrump5").onclick=g}function u(){0==v.progress?i("Bitte fülle den Fragebogen aus.<br>Klicke dazu auf den angezeigten Link."):f(v.progress>0)}function m(){1==v.progress?i("Bitte spiele zunächst das Spiel.<br>Klicke dazu auf den Download-Button um es herunter zuladen."):f(v.progress>1)}function l(){2==v.progress?i("Bitte fülle den Fragebogen aus.<br>Klicke dazu auf den angezeigten Link."):f(v.progress>2)}function h(){3==v.progress?i("Bitte spiele zunächst das Spiel.<br>Klicke dazu auf den Download-Button um es herunter zuladen."):f(v.progress>3)}function p(){4==v.progress?i("Bitte fülle den abschließenden Fragebogen aus.<br>Klicke dazu auf den angezeigten Link."):f(v.progress>4)}function g(){5==v.progress?i("Du hast bereits alle Aufgaben gemeistert.<br>Lade nun den angezeigten Code in deinen Moodle-Account"):f(v.progress>5)}function f(e){i(e?"Diesen Schritt hast du bereits bearbeitet.<br>Arbeite an Schritt "+(v.progress+1)+" weiter.":"Um diesen Schritt bearbeiten zu können, <br>beende zunächst Schritt "+(v.progress+1)+".")}var b=(document.cookie,null),v=null,k="https://surveys.informatik.uni-ulm.de/limesurvey/index.php/895276",y="https://surveys.informatik.uni-ulm.de/limesurvey/index.php/472153",w="https://surveys.informatik.uni-ulm.de/limesurvey/index.php/11326",I="Mon Nov 17 2016 13:35:19 GMT+0100 (Mitteleuropäische Zeit)";document.addEventListener("DOMContentLoaded",function(){n(),t(),c()})}(),isConsentForm=!1,isInputForm=!1,psdnym=null,document.addEventListener("DOMContentLoaded",function(){inputChangeHandler(),checkboxChangeHandler(),setButtonListener()}),checkReturningUser(),checkConsentForm(),checkInputForm(),isConsentForm=!1,isInputForm=!1,psdnym=null,document.addEventListener("DOMContentLoaded",function(){inputChangeHandler(),checkboxChangeHandler(),setButtonListener()}),checkReturningUser(),checkConsentForm(),checkInputForm();