window.addEventListener( "message",
  function (e) {
	
		if(e.origin !== "https://www.mysmark.com"){ return; }
		var a = e.data.split('|');
		document.getElementById('mySmarkFrame').style.height = (parseInt(a[0])+340)+"px";
		document.getElementById('mySmarkFrame').style.width = (parseInt(a[1])+20)+"px";
  },
  false);
