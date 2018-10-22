// v2.0.6
if (!window.location.hostname.includes("localhost")) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "https://simple-banner.glitch.me/hostname", true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({ hostname: window.location.hostname }));
}
