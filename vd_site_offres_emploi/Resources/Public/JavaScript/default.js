function makeHttpObject() {
  try {
    return new XMLHttpRequest();
  } catch(error) {}
  try {
    return new ActiveXObject();
  } catch(error) {}
  throw new Error("Could not create HTTP request object.");
}

function readCookie(name) {
  let nameEQ = name + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while(c.charAt(0) == ' ')
      c = c.substring(1, c.length);
    if(c.indexOf(nameEQ) == 0)
      return c.substring(nameEQ.length, c.length);
  }
  return '';
}

function replacecontent() {
  // required for honoring site preferences from provided cookie consent
  //   let langCookieVal = readCookie('siteLang');
  let langCookieVal = "fr";
  let anchortext = (document.URL.split('#').length > 1) ? document.URL.split('#')[1] : langCookieVal;

  //Replace the FUSION_CE_HOST_ADDRESS with Fusion CE Host details
  //Example:
  //host = 'https://fuscdrmsmc82-fa-ext.us.oracle.com';
  const host = document.querySelector('body').dataset.host;
  const ceBaseURL = host + '/hcmUI/CandidateExperience/';

  var replaced = null;
  var xhr = makeHttpObject();
  xhr.open('GET', ceBaseURL + anchortext, true);
  xhr.setRequestHeader('ora-irc-vanity-domain', 'Y');
  xhr.send();
  xhr.onreadystatechange = function() {
    if(xhr.readyState !== 4) return;
    if(xhr.status >= 200 && xhr.status < 300) {
      replaced = xhr.responseText;
      //REQUIRED: makes the short links work by replacing
      window.location.href = '#' + xhr.responseURL.replace(ceBaseURL, "");
      document.open("text/html", "replace");
      document.write(replaced);
      document.close();
    }
  };
}
