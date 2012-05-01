window.addEventListener( "message",
  function (e) {
	
		if(e.origin !== "https://www.mysmark.com"){ return; }
		var size = e.data.split('|');
		document.getElementById('mySmarkFrame').style.height = (parseInt(size[0])+20)+"px";
		document.getElementById('mySmarkFrame').style.width = (parseInt(size[1])+20)+"px";
  },
  false);
