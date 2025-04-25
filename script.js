function fetchReply(id) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var element = document.getElementById(id)
      if (id == 'heartbeat') {
          var timeNow = Date.now()-300000; //get unix timestamp 5 min ago
          var timestamp = this.responseText*1000; //change from seconds to milliseconds
          var timeTimestamp = new Date(timestamp);
          if (timestamp < timeNow) {
            //make red and change icon (and remove green)
            element.querySelector('a').classList.add("btn-outline-danger");
            element.querySelector('i').classList.add("fa-heart-crack");
            element.querySelector('i').classList.add("fa-shake");
            element.querySelector('a').classList.remove("btn-outline-success");
            element.querySelector('i').classList.remove("fa-heart-pulse");
          } else {
            //make green and change icon (and remove red)
            element.querySelector('a').classList.add("btn-outline-success");
            element.querySelector('i').classList.add("fa-heart-pulse");
            element.querySelector('a').classList.remove("btn-outline-danger");
            element.querySelector('i').classList.remove("fa-heart-crack");
            element.querySelector('i').classList.remove("fa-shake");
          }
          element.querySelector('span').innerHTML = timeTimestamp;
        } else if (typeof id === 'undefined' || this.responseText==="empty") {
          // add spinner if "empty" is returned
          document.getElementById('XML').innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height:100%;"><i class="fa-solid fa-spinner fa-4x fa-spin text-info-emphasis"></i></div>';
          document.getElementById('timestamp').innerHTML = 'Waiting for reply...';
          //document.getElementById('sendBtn').classList.remove("disabled");
        } else {
          element.innerHTML = this.responseText;

        }
      }
    };
    xmlhttp.open("POST", "fetchReply.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("id="+id+"&mac="+mac);
    setTimeout(Prism.highlightAll, 50);
  }

  function sendData(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('editable').innerHTML = this.responseText;
      }
    };
    xmlhttp.open("POST", "sendData.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "mode="+mode+"&mac="+mac+"&sh="+sh;
    xmlhttp.send(sendString);
    setTimeout(Prism.highlightAll, 50);
  }

  function addText(doWhat) {
    if (doWhat === "telnet") {
      var content = '<?xml version="1.0" encoding="UTF-8"?>\n<command>\n <configuration>\n  <device>\n   <telnetEnabled>true</telnetEnabled>\n  </device>\n </configuration>\n</command>';
      document.getElementById('cmd').style.display="none";
      mode = "1";
      sh = "";
    } else if (doWhat === "details") {
      var content = `<?xml version="1.0" encoding="UTF-8"?>\n<command>\n <postDeviceDetails url="https://services.openpeak.net/reply.php?mac=${mac}" />\n</command>`;
      document.getElementById('cmd').style.display="none";
      mode = "2";
      sh = "";
    } else if (doWhat === "cmd") {
      contentStart = `<?xml version="1.0" encoding="UTF-8"?>\n<command>\n <remoteExec commandId="1" timeout="5">\n  <callbackURL>https://services.openframe.net/reply.php?mac=${mac}</callbackURL>\n  <shText>`;
      contentEnd = `</shText>\n </remoteExec>\n</command>`;
      var content = contentStart + contentEnd;
      document.getElementById('cmd').style.display="block";
      document.getElementById('cmd').querySelector('textarea').focus();
      mode = "3"
    } 
    var element = document.getElementById('editable');
    element.innerHTML = `<!--${content}-->`;
    Prism.highlightElement(element);
    document.getElementById('sendBtn').classList.remove("disabled");
  }

  setInterval(fetchReply, 5000, 'heartbeat');
  setInterval(fetchReply, 5000, 'XML');
  setInterval(fetchReply, 5000, 'timestamp');
