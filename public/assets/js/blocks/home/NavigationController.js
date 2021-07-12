window.addEventListener("load", function (){
	vNavController();
	activePage();
},false);
function vNavController(){
	var vNavigation = new mobileNavigation();
	vNavigation.property.NextSlideInDirection = "rtl";
	vNavigation.property.NextSlideOutDirection = "rtl";
	vNavigation.property.BackSlideInDirection = "ltr";
	vNavigation.property.BackSlideOutDirection = "ltr";
	vNavigation.property.subMenuHeight = 32;
	vNavigation.initializeNavigation();
}
function activePage(){
	url = location.pathname;
	targets = [/services/, /portfolio/,/aboutus/];
	if(url.length == 1){

	}else if(url.length > 1){
		for(x=0;x<targets.length;x++){
			pattern = targets[x];
			if(pattern.test(url)){
				existing = document.querySelector(".active");
				existing.classList.remove("active");
				id = pattern.toString();
				CleanId = id.replace(/\//g, "");
				newActive = document.getElementById(CleanId);
				newActive.classList.add("active");
			}
		}
	}

}
