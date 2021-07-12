addEventListener("load", function(){
    // slideShow();
}, false)
function slideShow(){
	var container = document.getElementById("container");
	var viewport = document.querySelector("#container #viewport");
	var carous = new Carousel(container, viewport);
	carous.config.delay = 3500;
	carous.config.speed = 1500;
	carous.config.buttonStyle = ["margin-top:13px;"];
	carous.initialize();
	carous.start();
}
