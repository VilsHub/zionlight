// JavaScript Document
var load = 0;
addEventListener("load", function(){
	beakPoints = {
		largeStart:1000,
		mediumStart: 600
	}
	sbPoint = new ScreenBreakPoint(beakPoints);

	writer = new AutoWriter();
	writer.config.callBackDelay = 1100;
	writer.config.typingSpeed = [100, 200];
	writer.config.showCursor = true;
	writer.config.cursorBlinkDelay = 350;
	writer.config.cursorStyle = {style:"solid", width:"2px", color:"purple"};
	textBoxes = $$.sa("#splash #greetingsCon #gContent #greetings p");

	texts = ["Welcome", "to my workspace"];
	writer.writeText(textBoxes, texts, vilshubGrow);

	setHidden();
	$$.scroll.lock();
},true);

function vilshubGrow(){
	vilshub = $$.ss("#splash #greetingsCon #gContent #logoCon #logo a");
	
	s = detectDeviceWidth();
	if(s == "S"){
		vilshub.style["flex-basis"] = "36%";
	}else if(s == "M"){
		vilshub.style["flex-basis"] = "40%";
	}else if(s == "L"){
		vilshub.style["flex-basis"] = "75%";
	}

	$$.delay(1000, function(){ services()});
}
function splitter(){
	splitter = $$.ss("#line");
	s = detectDeviceWidth()
	if( s == "S"){
		serviceList();
		setTimeout(function(){
			Progress();
			entry();
		}, 500);
	}else if(s == "M" || s == "L"){
		for(x=1;x<71;x++){
			splitter.style["flex-basis"] = x+"%";
		}
		setTimeout(serviceList, 1000);
		setTimeout(function(){
			Progress();
			entry();
		}, 2000);
	}
}
function services(){
	spans = $$.sa("#splash #summaryCon #titleCon h1 span");
	texts2 = ["My ","se~2000~rvices"];
	writer.config.callBackDelay = 600;
	writer.writeText(spans, texts2, splitter);	
}
function serviceList(){
	active = 0;
	lists = $$.sa("#splash #summaryCon #listCon ul li .lcon");
	s = detectDeviceWidth();
	if(s == "S"){
		widths = [65.38511834706421, 80.67053347423787, 72.60348012681408, 48.40232008454272];
		list = setInterval(function(){
				if(active == 3){
					clearInterval(list);
					lists[active].style["visibility"] = "visible";
					lists[active].style["width"] = widths[active]+"%";
				}else{
					lists[active].style["visibility"] = "visible";
					lists[active].style["width"] = widths[active]+"%";
					active++;
				}
			},300);
	}else if(s == "M" || s == "L"){
		list = setInterval(function(){
				ActiveStyle = window.getComputedStyle(lists[active], null);
				ActiveWidth = parseInt(ActiveStyle.width, "px");
				if(active == 3){
					clearInterval(list);
					cw = ActiveWidth-58;
					lists[active].style["visibility"] = "visible";
					lists[active].style["left"] = -151+"px";
				}else{
					cw = ActiveWidth-58;
					lists[active].style["visibility"] = "visible";
					lists[active].style["left"] = -151+"px";
					active++;
				}
			},500);

	}


}
function setHidden (){
	active = 0;
	lists = document.querySelectorAll("#splash #summaryCon #listCon ul li .lcon");
	s = detectDeviceWidth();
	if(s == "S"){

	}else if(s == "M" || s == "L"){
		for (x=0;x<4;x++){
			ActiveStyle = getComputedStyle(lists[x], null);
			ActiveWidth = parseInt(ActiveStyle.width, "px");
			cw = ActiveWidth+158;
			ActiveWidth += cw;
			lists[x].style["left"] = -ActiveWidth+"px";
		}
	}



}
function Progress(){
	loadContent();
}
function entry(){
	Entrancebutton = document.getElementsByTagName("button");
	Entrancebutton[0].style["display"] = "block";
}
function loadContent(){
	var screen = detectDeviceWidth();
	home = "/api/content/index/homepage";
	if(screen == "M"){
		pgURLs = [home, "/assets/imgs/slider/001_medium.jpg","/assets/imgs/slider/002_medium.jpg", "/assets/imgs/slider/003_medium.jpg", "/assets/imgs/slider/004_medium.jpg", "/assets/imgs/slider/crypto_medium.jpg"];
	}else if(screen == "S"){
		pgURLs = [home, "/assets/imgs/slider/001_small.jpg","/assets/imgs/slider/002_small.jpg", "/assets/imgs/slider/003_small.jpg", "/assets/imgs/slider/004_small.jpg", "/assets/imgs/slider/003_small.jpg", "/assets/imgs/slider/004_small.jpg", "/assets/imgs/slider/crypto_small.jpg"];
	}else if(screen == "L"){
		pgURLs = [home, "/assets/imgs/slider/001.jpg","/assets/imgs/slider/002.jpg", "/assets/imgs/slider/003.jpg", "/assets/imgs/slider/004.jpg", "/assets/imgs/slider/crypto_large.jpg"];
	}
	
	loadIndicator = document.getElementById("progressCon");
	loadIndicator.style["display"] = "flex";
	$$.IO.get(pgURLs, unlockEntry);// for caching
}
function unlockEntry(data, track){
	if(track.status){
		loadIndicator.style["display"] = "none";
		loadIndicator.style["animation-name"] = "none";
		Ebutton = $$.ss(".locked");
		Ebutton.classList.remove("locked");
		load = 1;
		Ebutton.onclick = function(){
			login();
		}
		Ebutton.ontouchstart = function(){
			login();
		}
		$$.ss("#page .pp").innerHTML = data[0];
	}
}
function lockEntry(){
	button = document.getElementById("logBt");
	indicator = document.getElementsByClassName("vProgress");
	button.classList.add("locked");
	indicator[0].style["display"] = "none";
	button.innerHTML = "Done";
}
function welcome(){
	lockEntry();
	hr = $$.ss("#topRule");
	splash = $$.ss("#bg");
	content = $$.ss(".pp");
	styleHandler = getComputedStyle(splash, null);
	height = window.innerHeight;
	splash.style["top"] = -(height*2)+"px";
	hr.parentNode.removeChild(hr);
	content.style["opacity"] = 0;
	scrollTo(0,0);
	content.style["display"] = "block";
	$$.ss("title").innerHTML = "Home | VilsHub";
	
	setTimeout(function(){
		fixScriptSrc = document.getElementById("startSrc").getAttribute("data-src");
		fixScript = $$.ce("script");
		fixScript.onload = function(){
			startPage();
		}
		fixScript.setAttribute("src", fixScriptSrc);
		document.head.appendChild(fixScript);
	},1400);
}
function login(){
	showProgress();
	Xreq = $$.ajax({method:"GET", url:"/api/welcome/register"}, "text");
	Xreq.onload = function(){
		resp = Xreq.responseText;
		if (resp == 1){
			welcome();
		}
	}
	Xreq.send();
}
function showProgress(){
	button = $$.ss("#logBt");
	button.classList.add("locked");
	button.classList.add("vProgress");
	button.innerHTML = "Opening vilsHub";

}
function hideProgress(){
	button = $$.ss("#logBt");
	//indicator = document.getElementsByClassName("vProgress");
	button.classList.remove("locked");
	button.classList.remove("vProgress");
	//indicator[0].style["display"] = "none";
	button.innerHTML = "Get into VilsHub";

}
function detectDeviceWidth(){
	width = window.innerWidth;
	if (width >= 1000){
		screen = "L";
	}else if(width >= 600 && width < 1000 ){
		screen = "M";
	}else if(width < 600){
		screen = "S";
	}
	return screen;
}