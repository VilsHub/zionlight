addEventListener("load", function(){
	colors =["#CDEF00","#EB2A6E","#8EEF2B", "#B826C7"];
	draw();
	sliderShow();
	whyUsControl();
	vNavController();
	//typo();
}, false);
function assignHandlers(){
	cons = document.querySelectorAll("#body .content");
	canEle = document.getElementsByClassName("gc");
	for(x=0;x<cons.length;x++){
		signHandler(x);
	}
};
function signHandler(x){
	animatedRectangle = GridBorderRectangleObj.animatedRectangle();
	function ins(a){
		animatedRectangle.config.easing = "swingEaseIn";
		animatedRectangle.config.duration = 800;
		animatedRectangle.config.segment = [5, 4];
		animatedRectangle.config.lineColor = colors[a];
		animatedRectangle.draw(canEle[a]);
	}
	cons[x].onmouseenter = function(){
		ins(x);
	}
	cons[x].ontouchstart = function(){
		ins(x);
	}
	cons[x].onmouseleave = function(){
		animatedRectangle.stop();
	}
	cons[x].ontouchend = function(x){
		animatedRectangle.stop();
	}

}
function draw(){
	iteration=0;
	GridBorderRectangleObj = new CShapes();
	fixedRectangle = GridBorderRectangleObj.fixedRectangle();
	canEle = document.querySelectorAll(".gc");
	for(x=0; x<canEle.length; x++){
		fixedRectangle.config.lineColor = colors[x];
		fixedRectangle.config.segment = [5, 4];
		fixedRectangle.draw(canEle[x]);
		iteration++;
		if(iteration == canEle.length){
			assignHandlers();
		};
	}
};
function sliderShow(){
	container = document.getElementById("vSlider");
	viewport = document.querySelector("#vSlider #holder");
	var carous = new Carousel(container, viewport);
	carous.config.delay = 3500;
	carous.config.speed = 800;//in milliseconds
	carous.config.slideEffect = "cubic-bezier(0,.98,0,.98)";
	carous.config.buttonStyle = ["margin-top:13px;"];
	carous.initialize();
	carous.start();
}
function whyUsControl(){
	e1 = document.getElementById("vSlider");
	e2 = document.getElementById("main_content");
	hght1 = e1.clientHeight;
	hght2 = e2.clientHeight;
	startPoint = hght1+(hght2/5.5);

	trc = document.querySelectorAll("* .trc");
	blc = document.querySelectorAll("* .blc");
	content = document.querySelectorAll("* .mcon");
	lbl = document.querySelectorAll("* .lblCon");

	showComplete = 0;
	hideComplete = 0;
	onscroll = function (){
		if(window.scrollY >= startPoint){
			if(showComplete == 0){
				showCurves();
				setTimeout(function(){
					showContent();
					showLabels();
					showComplete =1;
				},600);
			}
		}
	}
}
function showCurves(){
	for(x=0; x<trc.length; x++){
		trc[x].style["visibility"] = "visible";
		blc[x].style["visibility"] = "visible";
		blc[x].style["height"] = "391px";
		trc[x].style["height"] = "391px";
		if(x == trc.length-1){
			blc[x].style["width"] = "98.5%";

			trc[x].style["width"] = "99%";
		}else {

			trc[x].style["width"] = "97%";


			blc[x].style["width"] = "99%";
		}
	}
}
function showLabels(){
	for(x=0; x<lbl.length; x++){
		lbl[x].style["visibility"] = "visible";
	}
}
function showContent(){
	for(x=0; x<content.length; x++){
		content[x].style["visibility"] = "visible";
		content[x].style["opacity"] = "1";
	}
}
function vNavController(){
	var vNavigation = new mobileNavigation();
	vNavigation.property.NextSlideInDirection = "rtl";
	vNavigation.property.NextSlideOutDirection = "rtl";
	vNavigation.property.BackSlideInDirection = "ltr";
	vNavigation.property.BackSlideOutDirection = "ltr";
	vNavigation.property.subMenuHeight = 32;
	vNavigation.initializeNavigation();
}
function typo(){
	var baseGrid = new vGrid();
	baseGrid.height = 23;
	baseGrid.lineColor = "yellow";
	baseGrid.toolFontColor = "cyan";
	baseGrid.initialize();
	baseGrid.ForceONWithGrid();
}