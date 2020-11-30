// JavaScript Document
window.addEventListener("load",function (){
  colors=["#E2F556", "#B8F556", "#7BF556", "#56F563", "#56F5A5", "#56F5E0", "#56EDF5", "#56C8F5" ,"#56A5F5", "#5683F5", "#5856F5", "#8D56F5", "#B256F5", "#F256F5", "#F556CD"];
  activeColor = 0, end=false;
  maintenanceCountDown();
  progressIndicator();
  centralize();
},false);
function colorCycler(){
  if(activeColor >= 0){
    if(end == false){
      activeColor++;
    }else if(end == true){
      activeColor--;
    }
    if(activeColor == 0){
      end = false;
    }else if(activeColor > colors.length-1){
      activeColor = colors.length-2;
      end =true;
    }
  }
}
function maintenanceCountDown(){
	var DateLine = document.getElementById("countDown").innerHTML;
	var apiUrl = "http://api.vilshub.com/countdown";
	var DateLineWrap = new FormData();
	DateLineWrap.append("dateline", DateLine);

	//Count down
	var XHR = Ajax.create();
	XHR.open("POST", apiUrl, true);
	XHR.addEventListener("load", function(){
		var decoded = JSON.parse(this.responseText);
		var compBlock = document.getElementById("vCountDown");
		var CountDownRunner = new countDown(decoded, compBlock);
		CountDownRunner.initialize();
		CountDownRunner.start();
	}, false);
	XHR.send(DateLineWrap);
}
function progressIndicator(){
//////// grid border rectangle  ///////////
  //gets canvas element
	var TargetCanvas = document.getElementById("canvas");
	var GridBorderRectangleObj = new GridBorderRectangle();
  var gbr = GridBorderRectangleObj.animatedRectangle();
	gbr.config.easing = "linear";
	gbr.config.duration = 1000;
	gbr.config.segment = [12, 5];
	gbr.config.lineColor = colors[activeColor];
  gbr.config.lineWidth = 5;
  gbr.config.fn = function(){
    colorCycler();
    gbr.config.lineColor = colors[activeColor];
  };
  gbr.config.iterationCount = "infinite";
	gbr.draw(TargetCanvas);
}
function centralize (){
  m = document.querySelector(".maintenanceInfo");
  DOMelement.center(m);
}
