// JavaScript Document
window.addEventListener("load",function (){
	beakPoints = {
		largeStart:1000,
		mediumStart: 600
	}
	sbPoint = new ScreenBreakPoint(beakPoints);
	mHanler = new ModalDisplayer();
	formComponents = new FormComponents();
	taskDatePicker = formComponents.datePicker();
	scrollController();
	DomainListController();
	DomainContentController();
	//typo();
	gridFix();
	subContentLoader();
	modalHandler();
	anchor();
},false);
function scrollController(){
	scrolled = 0;
	arrowComp1 = document.getElementById("leftArrowSide");
	arrowComp2 = document.getElementById("rightArrowSide");
	arrowComp3 = document.getElementById("line");
	scrollButton = document.getElementById("scrollBt");
	scrollCon = document.getElementById("scrollCon");
	scrollButton.addEventListener("click", function(){
		scrollCon.style["animation-name"] = "none";
		arrowComp1.style["animation-name"] = "none";
		arrowComp2.style["animation-name"] = "none";
		arrowComp3.style["animation-name"] = "none";
		scrollCon.style["opacity"] = "0";
		DOMelement.animate(animateScroll, 428, 400, "swingEaseIn");
		scrolled =1;
	},false);
	function animateScroll(x){
		window.scrollTo(0, x);
	};
	scrollCon.style["animation-name"] = "bounce";
	arrowComp1.style["animation-name"] = "fade";
	arrowComp2.style["animation-name"] = "fade";
	arrowComp3.style["animation-name"] = "fade";

	window.addEventListener("scroll", function(){
		if(scrolled !=1){
			scrollCon.style["animation-name"] = "none";
			arrowComp1.style["animation-name"] = "none";
			arrowComp2.style["animation-name"] = "none";
			arrowComp3.style["animation-name"] = "none";
			scrollCon.style["opacity"] = "0";
		}
	},false);
	scrollCon.addEventListener("transitionend",function(){
		this.style["display"] = "none";
		this.style["opacity"] = "0";
		scrolled =1;
	},false);
}
function DomainContentController(){
	buttons = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .subNav .Btcon");

	buttons.addEventListener("click", function(e){
		if(e.target.nodeName == "BUTTON" && e.target.classList.contains("Bselected") == false){
			id = e.target.id;

			detailsCon = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent .subContent .det");
			channelCon = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent .subContent .chnl");

			if(id == "channels"){
				buttons.querySelector(".Bselected").classList.remove("Bselected");

				detailsCon.style["display"] = "none";
				channelCon.style["opacity"] = 0;
				channelCon.style["display"] = "block";
				setTimeout(function(){
					e.target.classList.add("Bselected");
					channelCon.style["opacity"] = "1";

				},300);
			}else if (id == "details"){
				buttons.querySelector(".Bselected").classList.remove("Bselected");

				channelCon.style["display"] = "none";
				detailsCon.style["opacity"] = 0;
				detailsCon.style["display"] = "block";
				setTimeout(function(){
					e.target.classList.add("Bselected");
					detailsCon.style["opacity"] = "1";

				},300);
			}
		}
	},false);

}
function DomainListController(){
	list = document.getElementById("domainList");
	listCon = list.querySelector("ul");
	LeftBt = document.getElementById("upbt");
	RightBt = document.getElementById("downbt");

	container = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent");


	listControllerObj = new  ListScroller(list, listCon);
	
	listControllerObj.config.Xbuttons = [LeftBt, RightBt];
	listControllerObj.config.listPlane = "x";
	listControllerObj.config.inactiveButtonsClassName = ["deactivate"];
	listControllerObj.config.effects = [0.3, "linear"];
	listControllerObj.config.scrollSize = 175 // 170+5px margin for each
	listControllerObj.config.paddingLeft = 50;
	listControllerObj.config.paddingRight = 50;
	listControllerObj.initialize();


	list.addEventListener("click", function(e){
		if(e.target.nodeName == "LI" && e.target.classList.contains("Dselected") == false){
			targetContent = e.target.id;
			xmlhttp = Ajax.create();
			anyLoader = list.querySelectorAll(".loader");
			activeDomain = list.querySelector(".Dselected");

			//remover any other loader
			for(x=0; x<anyLoader.length; x++){
				anyLoader[x].classList.remove("loading");
			}

			e.target.classList.add("loading");
			xmlhttp.addEventListener("load", function(){
				container.style["display"] = "none";
				container.style["opacity"] = "0";




				activeDomain.classList.remove("Dselected");
				e.target.classList.remove("loading");
				e.target.classList.add("Dselected");
				container.innerHTML = xmlhttp.responseText;
				setSubTabs();
				setTimeout(function(){

					container.style["display"] = "block";

					container.scrollHeight;
					container.style["opacity"] = "1";
				},300);
			}, false);
			xmlhttp.open("GET", "/content/services/"+targetContent, "async");
			xmlhttp.send();
		}
	}, false);

	if(sbPoint.screen.mode == "large"){
		listControllerObj.offScroller();
	}else if (sbPoint.screen.mode == "medium" && sbPoint.screen.actualSize < 900) {
		listControllerObj.onScroller();
	}else if (sbPoint.screen.mode == "small") {
		listControllerObj.onScroller();
	}
}
function setSubTabs(){
	buttons = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .subNav .Btcon");
	activeButton = buttons.querySelector(".Bselected");
	if(activeButton.id != "details"){
		activeButton.classList.remove("Bselected");
		buttons.querySelector("#details").classList.add("Bselected");
	}
}
function typo(){
	var baseGrid = new vGrid();
	baseGrid.height = 23;
	baseGrid.lineColor = "yellow";
	baseGrid.toolFontColor = "cyan";
	baseGrid.initialize();
	baseGrid.ForceONWithNoGrid();
}
function gridFix(){
	var TargetElement = document.getElementById("pID");
	var summaryCon = document.getElementById("contentCon");
	ToBaseGridMultiple.setHeight(TargetElement, 23);
	ToBaseGridMultiple.centerVertically (summaryCon, 23);
}
function subContentLoader(){
	var DomainInitial = "/content/services/programming";
	var DateLine = document.getElementById("countDown").innerHTML;
	var targetCon = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent");
	var apiUrl = "http://api.vilshub.com/countdown";
	var DateLineWrap = new FormData();
	DateLineWrap.append("dateline", DateLine);
	var XHR = Ajax.create();
	XHR.open("GET", DomainInitial, true);
	XHR.addEventListener("load", function(){
		targetCon.innerHTML = this.response;
	}, false);
	XHR.send(null);



	//Count down
	var XHR2 = Ajax.create();
	XHR2.open("POST", apiUrl, true);
	XHR2.addEventListener("load", function(){
		var decoded = JSON.parse(this.responseText);
		var compBlock = document.getElementById("vCountDown");
		var CountDownRunner = new countDown(decoded, compBlock);
		CountDownRunner.initialize();
		CountDownRunner.start();
	}, false);
	XHR2.send(DateLineWrap);
}
function validateChannels(form, target){
	id = target.id;
	equivInput = form.querySelector("input[data-refId='"+id+"']");
	if(target.checked){
		equivInput.getAttribute("data-cache") != undefined?equivInput.value = equivInput.getAttribute("data-cache"):null;
		equivInput.classList.toggle("disabled");
		equivInput.disabled = false;
	}else {
		equivInput.setAttribute("data-cache", equivInput.value);
		equivInput.classList.toggle("disabled");
		equivInput.value = "";
		equivInput.disabled = true;
	}

}
function Form1customFormComponentsBuilder(){
	DurationSelectEle = document.querySelector("#pduration select");
	BudgetSelectEle = document.querySelector("#pbudget .pbs");
	radioButtons = document.querySelectorAll("#pPltfm fieldset input[type='radio']");
	DurationCustomSelect = new customFormComponent("selectDuration");
	BudgetCustomSelect = new customFormComponent("selectBudget");
	pPltfCustomRadio = new customFormComponent("pltform");

	DurationCustomSelect.select.config.selectSize = ["60%", "34px"];
	DurationCustomSelect.select.config.wrapperStyle = "position:absolute; right:5px; top:4px; font-size:13px;";
	DurationCustomSelect.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	DurationCustomSelect.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc";
	DurationCustomSelect.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc";
	DurationCustomSelect.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	DurationCustomSelect.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	DurationCustomSelect.select.config.arrowIconOpen = "content:'\\ea4d';";
	DurationCustomSelect.select.build(DurationSelectEle);

	BudgetCustomSelect.select.config.selectSize = ["40%", "34px"];
	BudgetCustomSelect.select.config.wrapperStyle = "position:absolute; left:5px; top:4px; font-size:13px;";
	BudgetCustomSelect.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	BudgetCustomSelect.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc";
	BudgetCustomSelect.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc";
	BudgetCustomSelect.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	BudgetCustomSelect.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	BudgetCustomSelect.select.config.arrowIconOpen = "content:'\\ea4d';";
	BudgetCustomSelect.select.build(BudgetSelectEle);

	pPltfCustomRadio.radio.config.radioButtonSize = ["15px","15px"];
	pPltfCustomRadio.radio.config.wrapperStyle = "margin-right:20px;";
	pPltfCustomRadio.radio.config.radioButtonStyle = "border-color:purple;";
	pPltfCustomRadio.radio.config.labelStyle = "color:white; font-size:12px; margin-left:5px;";
	pPltfCustomRadio.radio.config.deselectedStyle = "background-color:#ccc; background-image:linear-gradient(to bottom, #ccc 0%, white 100%);";
	pPltfCustomRadio.radio.config.selectedStyle = "background-color:green; background-image:linear-gradient(to bottom, #BA26C7 0%, #73157B 100%);";
	hover = "box-shadow:0 0 6px #ba26c7;";
	clicked = "background-image:linear-gradient(to top, #ccc 0%, white 100%)!important;";
	pPltfCustomRadio.radio.config.mouseEffectStyle = [hover, clicked];
	if(innerWidth <= 470){
		pPltfCustomRadio.radio.config.groupAxis = "y";
	}else {
		pPltfCustomRadio.radio.config.groupAxis = "x";
	}
 	pPltfCustomRadio.radio.build(radioButtons[0]);
	pPltfCustomRadio.radio.build(radioButtons[1]);
	pPltfCustomRadio.radio.build(radioButtons[2]);

	window.addEventListener("resize", function(){
		if(innerWidth <= 470){
			pPltfCustomRadio.radio.config.groupAxis = "y";
		}else {
			pPltfCustomRadio.radio.config.groupAxis = "x";
		}
	})
}
function Form2customFormComponentsBuilder(){
	form = document.querySelector("#rtimesoltn");
	TargetDoaminSelectElement = document.querySelector(".selRow #tdomain .select select");
	TargetDoaminCustomSelect = new customFormComponent("tdomain");
	domainChannelCheckBox = new customFormComponent("domainDet");
	channelBoxes = document.querySelectorAll("#domainInformation .groupInput fieldset input[type='checkBox']")

	TargetDoaminCustomSelect.select.config.selectSize = ["100%", "34px"];
	TargetDoaminCustomSelect.select.config.wrapperStyle = "position:absolute; font-size:13px;";
	TargetDoaminCustomSelect.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	TargetDoaminCustomSelect.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc";
	TargetDoaminCustomSelect.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc";
	TargetDoaminCustomSelect.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	TargetDoaminCustomSelect.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	TargetDoaminCustomSelect.select.config.arrowIconOpen = "content:'\\ea4d';";
	TargetDoaminCustomSelect.select.build(TargetDoaminSelectElement);


	domainChannelCheckBox.checkBox.config.checkBoxSize = ["17px","17px"];
	domainChannelCheckBox.checkBox.config.wrapperStyle = "margin-right:20px; margin-top: 10px;";
	domainChannelCheckBox.checkBox.config.checkBoxStyle = "border-color:purple;";
	domainChannelCheckBox.checkBox.config.labelStyle = "color:white; font-size:12px; margin-left:5px;";
	domainChannelCheckBox.checkBox.config.uncheckedStyle = "color:#ba26c7;font-family:vicon; content:'\\ea5f'; font-size:17px;background-color:#cec1c1";
	domainChannelCheckBox.checkBox.config.checkedStyle = "color:#ba26c7;font-family:vicon; content:'\\ea5e'; font-size:17px; background-color:#cec1c1";
	domainChannelCheckBox.checkBox.config.afterSelectionFn = function(){
		validateChannels(form, domainChannelCheckBox.checkBox.target);
	}
	hover = "box-shadow:0 0 6px #ba26c7;";
	clicked = "background-image:linear-gradient(to top, #ccc 0%, white 100%)!important;";
	domainChannelCheckBox.checkBox.config.mouseEffectStyle = [hover, clicked];
	if(innerWidth <= 500){
		domainChannelCheckBox.checkBox.config.groupAxis = "y";
	}else {
		domainChannelCheckBox.checkBox.config.groupAxis = "x";
	}
	window.addEventListener("resize", function(){
		if(innerWidth <= 500){
			domainChannelCheckBox.checkBox.config.groupAxis = "y";
		}else {
			domainChannelCheckBox.checkBox.config.groupAxis = "x";
		}
	})
	for (x=0; x<channelBoxes.length ; x++){
		domainChannelCheckBox.checkBox.build(channelBoxes[x]);
	}
}
function Form3customFormComponentsBuilder(){
	TaskTypeSelectElement = document.querySelector("#hireus #ttype select");
	TaskLocationSelectElement = document.querySelector("#hireus #tlocation select");
	TaskDateElement = document.querySelector("#hireus #itdate");

	bookDevTaskType = new customFormComponent("bookSelect1");
	bookDevTaskLocation = new customFormComponent("bookSelect2");


	bookDevTaskType.select.config.selectSize = ["100%", "34px"];
	bookDevTaskType.select.config.wrapperStyle = "position:absolute; font-size:13px; z-index:4";
	bookDevTaskType.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	bookDevTaskType.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc;";
	bookDevTaskType.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc;";
	bookDevTaskType.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	bookDevTaskType.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	bookDevTaskType.select.config.arrowIconOpen = "content:'\\ea4d';";
	bookDevTaskType.select.build(TaskTypeSelectElement);

	bookDevTaskLocation.select.config.selectSize = ["100%", "34px"];
	bookDevTaskLocation.select.config.wrapperStyle = "position:absolute; font-size:13px;";
	bookDevTaskLocation.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	bookDevTaskLocation.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc";
	bookDevTaskLocation.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc;";
	bookDevTaskLocation.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	bookDevTaskLocation.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	bookDevTaskLocation.select.config.arrowIconOpen = "content:'\\ea4d';";
	bookDevTaskLocation.select.build(TaskLocationSelectElement);

	taskDatePicker.config.inputIcon = "color:#858585; border:none; border-left:solid 1px #635f5f;content:'\\e95f'; font-family:vicon;  font-size:20px; line-height:38px;";
	taskDatePicker.config.dateType = "future";
	taskDatePicker.config.includeTime = true;
	taskDatePicker.config.shiftPoint = 382;
	// taskDatePicker.config.daysToolTip = true;
	taskDatePicker.config.furtureStopDate = [2020, 12, 31];
	taskDatePicker.initialize(TaskDateElement);

}
function Form4customFormComponentsBuilder(){
	platformTypeSelectElement = document.querySelector("#trninfo #ptype select");
	packageSelectElement = document.querySelector("#trninfo #pkgtype select");

	platformTypeCustomSelect = new customFormComponent("pltfrmselect");
	packageCustomSelect = new customFormComponent("pakgselect");

	platformTypeCustomSelect.select.config.selectSize = ["100%", "34px"];
	platformTypeCustomSelect.select.config.wrapperStyle = "position:absolute; font-size:13px; z-index:4";
	platformTypeCustomSelect.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	platformTypeCustomSelect.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc;";
	platformTypeCustomSelect.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc;";
	platformTypeCustomSelect.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	platformTypeCustomSelect.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	platformTypeCustomSelect.select.config.afterSelectionFn = function(){platformChange()};
	platformTypeCustomSelect.select.config.arrowIconOpen = "content:'\\ea4d';";
	platformTypeCustomSelect.select.build(platformTypeSelectElement);

	packageCustomSelect.select.config.selectSize = ["100%", "34px"];
	packageCustomSelect.select.config.wrapperStyle = "position:absolute; font-size:13px;  z-index:2";
	packageCustomSelect.select.config.selectFieldStyle = "background-color:#858585; color:white;";
	packageCustomSelect.select.config.optionsContainerStyle = "background-color:#858585; color:white; border-top:solid 1px #ccc";
	packageCustomSelect.select.config.optionStyle = "color:#474747; border-bottom:solid 1px #ccc;";
	packageCustomSelect.select.config.arrowConStyle = "border-left:solid 1px rgb(119, 113, 113); background-color:#c2c2c2";
	packageCustomSelect.select.config.arrowIconClose = "font-family:vicon; content:'\\ea4f'; font-size:20px; color:#858585;";
	packageCustomSelect.select.config.arrowIconOpen = "content:'\\ea4d';";
	packageCustomSelect.select.config.afterSelectionFn = function(){platformChange()};
	packageCustomSelect.select.build(packageSelectElement);

	// activeFormPlaceHolders();
	function platformChange(){
		pltfrmType = document.querySelector("#ptype select");
		packageType = document.querySelector("#pkgtype select");
		priceInput = document.querySelector("#price input");
		if (pltfrmType.value == "1"){
			switch (packageType.value) {
				case '0':
					priceInput.value = "";
			    break;
			  case '1':
					priceInput.value = "₦60,000.00";
			    break;
			  case '2':
					priceInput.value = "₦85,000.00";
					break;
			  case '3':
					priceInput.value = "₦110,000.00";
			    break;
			}
		}else {
			priceInput.value = "";
		}
	}
}
function activeFormPlaceHolders(){
	function activatePlaceHolder(e){
		var placeHolder = e.target.parentNode.previousElementSibling;
		if(placeHolder.classList.contains("placeholder")){
			placeHolder.style["color"] = "#47084e";
			(e.target.nodeName == "TEXTAREA" )?placeHolder.style["line-height"] = "45px" : placeHolder.style["line-height"] = "45px";
			placeHolder.style["padding-left"] = "0px";
			placeHolder.style["font-style"] = "normal";
			placeHolder.style["font-size"] = "13px";
			placeHolder.style["font-weight"] = "400";
		}
	}
	function deactivatePlaceHolder(e){
		var placeHolder = e.target.parentNode.previousElementSibling;
		if(placeHolder.classList.contains("placeholder")){
			placeHolder.style["color"] = "#bfbcbc";
			placeHolder.style["line-height"] = "100px";
			(e.target.nodeName == "TEXTAREA" )?placeHolder.style["padding-left"] = "10px" : placeHolder.style["padding-left"] = "54px" ;
			placeHolder.style["font-style"] = "italic";
			placeHolder.style["font-size"] = "14px";
			placeHolder.style["font-weight"] = "300";
		}
	}
	//formCon = document.querySelector(".formCon");
	document.body.addEventListener("focusin", function(e){
		if ((e.target.nodeName ==  "INPUT" && e.target.getAttribute("type") == "text" || e.target.nodeName == "TEXTAREA") && (e.target.classList.contains("hr") == false && e.target.classList.contains("min") == false )){
			activatePlaceHolder(e);
		}
		//console.log(formCon.parentNode)
	},false);
	document.body.addEventListener("focusout", function(e){
		if (e.target.nodeName ==  "INPUT" && e.target.getAttribute("type") == "text" || e.target.nodeName == "TEXTAREA"){

			if (e.target.value.length == 0){
				deactivatePlaceHolder(e);
			}
		}
	},false);
}
// function
function validateForm1(){
	form = document.querySelector("#sdevForm");
	validator = new formValidator(form);
	entetButton = document.querySelector("#sdevForm .action button");
	// validator.config.leftConStyle = "top:34px; height:36px;";
	// validator.config.rightConStyle = "top:34px; height:36px;";
	// validator.config.bottomConStyle  = "top:CALC(100% + 5px); height:36px;";
	validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
	validator.config.modal = mHanler;
	validator.config.feedBackController = feedBackController;
	validator.config.smallView = 877;

	//validator.config.leftConArrowColor = "blue";
	validator.initialize();

	projectName =  document.querySelector("#pName");
	
	description =  document.querySelector("#pDesc textarea");


	platform =  document.querySelectorAll("#pPltfm input[type='radio']");
	platformCon =  platform[0].parentNode.parentNode;
	platformWrapper =  platform[0].parentNode;

	projectDuration =  document.querySelector("#pbnpd #ipduration");
	validator.format.integerField(projectDuration);
	projectDurationCon =  projectDuration.parentNode.parentNode;

	durationUnit = document.querySelector("#pbnpd #durationUnit");
	budgetUnit = document.querySelector("#pbnpd #budgetUnit");

	projectBudget =  document.querySelector("#pbnpd #ipbudget");
	// projectBudgetCon =  projectBudget.parentNode.parentNode;
	validator.format.integerField(projectBudget);

	emailAddress =  document.querySelector("#diemail");

	phoneNumber = document.querySelector("#diphone");
	validator.format.integerField(phoneNumber);

	validator.format.integerField(projectDuration);
	button = document.querySelector("#sdevForm .action button");

	//format fields
	function validate(){
		data = new FormData();

		//TaskId
		data.append("task", "AppRequest");

		//Project name
		if(projectName.value.length == 0){
			validator.message.write(projectName, "error", "right", "Please provide the project name");
		}else if (projectName.value.length > 0) {
			if(validator.validate.customData(projectName.value, ["<", ">", "/", "@"])){
				validator.message.clear(projectName);
				//Append to form data
				data.append("ProjectName", projectName.value);
			}else{
				validator.message.write(projectName, "error", "left", "The followings characters are not allowed '<', '>', '/', '@'");
			}
		}

		//Description
		if(description.value.length == 0){
			validator.message.write(description, "error", "right", "Please provide the project description", [null, "height:auto"]);
		}else if (description.value.length > 0) {
			if(validator.validate.customData(description.value, ["<", ">", "/", "@"])){
				if(description.value.length <20){
					validator.message.write(description, "error", "right", "Project description too short");
				}else {
					validator.message.clear(description);
					//Append to form data
					data.append("ProjectDescription", description.value);
				}
			}else{
				validator.message.write(description, "error", "right", "The followings characters are not allowed '<', '>', '/', '@'");
			}
		}

		//platform
		if(validator.validate.selectField(platform) == false){
			validator.message.write(platformCon, "error", "bottom", "Please select the project platform");
		}else{
			validator.message.clear(platformCon, [platformWrapper, null]);
			//Append to form data
			data.append("ProjectPlatform",validator.getSelected(platform)[0]);
		}
		//project duration
		if(projectDuration.value.length == 0){
			validator.message.write(projectDurationCon, "error", "left", "Please provide the project duration");
		}else if (projectDuration.value.length > 0 && projectDuration.value > 0) {
			validator.message.clear(projectDurationCon, [projectDurationCon, null]);
			//Apend to form data
			data.append("ProjectDuration", projectDuration.value+""+durationUnit.value);
		}

		//Project budget
		if(projectBudget.value.length == 0){
			validator.message.write(projectBudget, "error", "right", "Please provide the project budget");
		}else if (projectBudget.value.length > 0 && projectBudget.value > 0) {
			validator.message.clear(projectBudget);
			//Apend to form data
			data.append("ProjectBudget", projectBudget.value+""+budgetUnit.value);
		}

		//Email address
		if(emailAddress.value.length == 0){
			validator.message.write(emailAddress, "error", "right", "Please provide your email address");
		}else if (emailAddress.value.length>0) {
			if (!validator.validate.emailAddress(emailAddress.value)){
				validator.message.write(emailAddress, "error", "right", "Email address format invalid");
			}else{
				 validator.message.clear(emailAddress);
				//Append to form data
				data.append("emailAddress", emailAddress.value);
			}
		}

		//Phone number
		if(phoneNumber.value.length == 0){
			validator.message.write(phoneNumber, "error", "right", "Please provide your phone number");
		}else if (phoneNumber.value.length > 0) {
			if (!validator.validate.phoneNumber(phoneNumber.value)){
				validator.message.write(phoneNumber, "error", "right", "Does not seem to be a valid phone number format");
			}else{
				 validator.message.clear(phoneNumber);
				//Append to form data
				data.append("phoneNumber", phoneNumber.value);
			}
		}


	}
	function feedBackController(data){
		try	{
			jd = JSON.parse(data);
			if(jd["status"] == true){
				validator.showFeedBack(null, "Request submitted successfully!", "success");
			}
		}catch(e){
			validator.showFeedBack(null, "Error submiting request", "error");
		}
		
	}
	button.addEventListener("click", function(){
		validate();
		if(validator.formOk()){
			validator.submit(data, location.origin+"/process/form/Appdev");
		}
	}, false);

}
function validateForm2(){
	form = document.querySelector("#rtimesoltn");
	validator = new formValidator(form);
	validator.config.leftConStyle = "top:34px; height:36px;";
	validator.config.rightConStyle = "top:34px; height:36px;";
	// validator.config.bottomConStyle  = "top:CALC(100% + 5px); height:36px;";
	validator.config.inputWrapperClass = "iwrapper"; ///Used if input wrapper is not specified in write method
	validator.config.placeholderClass = "placeholder"; // Used if input placeholder is not specified in write method
	validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
	validator.config.modal = mHanler;
	validator.config.smallView = 877;

	//validator.config.leftConArrowColor = "blue";
	validator.initialize();

	fname =  document.querySelector("#fName");
	fnameCon =  fname.parentNode.parentNode;

	email =  document.querySelector("#riemail");
	emailCon =  email.parentNode.parentNode;

	targetDomain = document.querySelector("#rdomain");

	channels =  form.querySelectorAll("input[type='checkbox']");
	channelsCOn = form.querySelector("#domainDet fieldset");
	validator.format.integerField(form.querySelector("INPUT[data-refid='phone']"));
	validator.format.integerField(form.querySelector("INPUT[data-refid='telegram']"));
	validator.format.integerField(form.querySelector("INPUT[data-refid='whatappp']"));
	validator.config.feedBackController = feedBackController;

	// validator.format.integerField(projectDuration);
	button = document.querySelector("#rtimesoltn .action button");

	function grabCheckedValues(checkBoxes){
		vdata = {}, err=0;
		for(x=0;x<checkBoxes.length; x++){
			if(checkBoxes[x].checked){
				val = form.querySelector("input[data-refid='"+checkBoxes[x].id+"']");
				if(val.value.length == 0){
					validator.message.write(channelsCOn, "error", "right", "Selected field(s) value cannot be empty", [channelsCOn, null], [null, "top:15px"]);
					err=1;
					break;
				}else {
					err=0;
					validator.message.clear(channelsCOn, [channelsCOn, null]);
					vdata[checkBoxes[x].value] = val.value;
				}
			}
		}
		if(err==1){
			return false;
		}else {
			return vdata;
		}
	}
	//format fields
	function validate(){
		data = new FormData();

		//TaskId
		data.append("task", "realTimeSub");

		//First name
		if(fname.value.length == 0){
			validator.message.write(fnameCon, "error", "right", "Please provide your first name");
		}else if (fname.value.length > 0) {
			if(validator.validate.alpha(fname.value)){
				validator.message.clear(fnameCon);
				//Append to form data
				data.append("fname", fname.value);
			}else{
				validator.message.write(fnameCon, "error", "left", "Only alphabets and spaces are allowed");
			}
		}

		// email
		if(email.value.length == 0){
			validator.message.write(emailCon, "error", "right", "Please provide your email address");
		}else if (email.value.length > 0) {
			if(validator.validate.emailAddress(email.value)){
				validator.message.clear(emailCon);
				//Append to form data
				data.append("email", email.value);
			}else{
				validator.message.write(emailCon, "error", "left", "Invalid email format");
			}
		}

		//Targrt domain
		data.append("domain", targetDomain.value);


		//Channels
		if(validator.validate.selectField(channels) == false){
			validator.message.write(channelsCOn, "error", "bottom", "Please select your prefered channels", [channelsCOn, null]);
		}else{
			dataChk = grabCheckedValues(channels);
			if(dataChk != false){
				data.append("channels",JSON.stringify(dataChk));
			}
		}
	}
	function feedBackController(data){
		if(data == 1){
			validator.showFeedBack(null, "Request submitted successfully!", "success");
		}else{
			validator.showFeedBack(null, "Error while processing subscription", "error");
		}
	}
	button.addEventListener("click", function(){
		validate();
		if(validator.formOk()){
			validator.submit(data, location.origin+"/process/form/process");
		}
	}, false);

}
function validateForm3(){
	form = document.querySelector("#hireus");
	validator = new formValidator(form);
	validator.config.leftConStyle = "top:34px; height:36px;";
	validator.config.rightConStyle = "top:34px; height:36px;";
	// validator.config.bottomConStyle  = "top:CALC(100% + 5px); height:36px;";
	validator.config.inputWrapperClass = "iwrapper"; ///Used if input wrapper is not specified in write method
	validator.config.placeholderClass = "placeholder"; // Used if input placeholder is not specified in write method
	validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
	validator.config.modal = mHanler;
	validator.config.feedBackController = fdController;
	validator.config.smallView = 869;

	//validator.config.leftConArrowColor = "blue";
	validator.initialize();

	taskType =  document.querySelector("#hireus #ttypei");
	taskLocation =  document.querySelector("#hireus #tlocationi");

	dateinneed =  document.querySelector("#hireus #itdate");
	dateinneedCon =  dateinneed.parentNode.parentNode;

	taskdescription = document.querySelector("#hireus textarea[name='tdescription']");
	taskdescriptionCon =  taskdescription.parentNode.parentNode;

	email =  document.querySelector("#hireus #hiemail");
	emailCon =  email.parentNode.parentNode;

	phone =  document.querySelector("#hireus #hiphone");
	phoneCon =  phone.parentNode.parentNode;
	validator.format.integerField(phone);

	function validate(){
			data = new FormData();

			//TaskId
			data.append("task", "bookDev");

			//taskType
			data.append("taskType",taskType.options[taskType.selectedIndex].innerHTML);

			//taskLocation
			data.append("taskLocation", taskLocation.options[taskType.selectedIndex].innerHTML);

			//Date in need
			styleLeft = "top:2px;";
			if(taskDatePicker.status["set"] == false){
				validator.message.write(dateinneedCon, "error", "right", "Please provide date and time for task", null, [null, styleLeft]);
			}else if (taskDatePicker.status["set"] == true) {
				if(taskDatePicker.status["completed"] == false){
					validator.message.write(dateinneedCon, "error", "right", "In complete date provided", null, [null, styleLeft]);
				}else {
					validator.message.clear(dateinneedCon);
					//Append to form data
					data.append("dateInNeed", dateinneed.value);
				}
			}

			//task description
			if(taskdescription.value.length == 0){
				validator.message.write(taskdescriptionCon, "error", "right", "Please provide the task description");
			}else if (taskdescription.value.length > 0) {
				if(validator.validate.customData(taskdescription.value, ["<", ">", "/", "@"])){
					if(taskdescription.value.length <20){
						validator.message.write(taskdescriptionCon, "error", "right", "Task description too short");
					}else {
						validator.message.clear(taskdescriptionCon);
						//Append to form data
						data.append("TaskDescription", taskdescription.value);
					}
				}
			}

			// email
			if(email.value.length == 0){
				validator.message.write(emailCon, "error", "right", "Please provide your email address");
			}else if (email.value.length > 0) {
				if(validator.validate.emailAddress(email.value)){
					validator.message.clear(emailCon);
					//Append to form data
					data.append("email", email.value);
				}else{
					validator.message.write(emailCon, "error", "left", "Invalid email format");
				}
			}

			//Phone number
			if(phone.value.length == 0){
				validator.message.write(phoneCon, "error", "right", "Please provide your phone number");
			}else if (phone.value.length > 0) {
				if (!validator.validate.phoneNumber(phone.value)){
					validator.message.write(phoneCon, "error", "right", "Does not seem to be a valid phone number format");
				}else{
					 validator.message.clear(phoneCon);
					//Append to form data
					data.append("phoneNumber", phone.value);
				}
			}
		}

	button = document.querySelector("#hireus .action button");
	button.addEventListener("click", function(){
		validate();
		if(validator.formOk()){
			validator.submit(data, location.origin+"/process/form/process");
		}
	}, false);
	function fdController(data){
		try	{
			jd = JSON.parse(data)
			if(jd["status"] == true){
				validator.showFeedBack(null, "Booked successfully, you'll receive a response from us soon.","success");
			}
		}catch(e){
			validator.showFeedBack(null, "Error while processing booking", "error");
		}
	}
}
function validateForm4(){
	form = document.querySelector("#sdtrng");
	validator = new formValidator(form);
	validator.config.leftConStyle = "top:34px; height:36px;";
	validator.config.rightConStyle = "top:34px; height:36px;";
	// validator.config.bottomConStyle  = "top:CALC(100% + 5px); height:36px;";
	validator.config.inputWrapperClass = "iwrapper"; ///Used if input wrapper is not specified in write method
	validator.config.placeholderClass = "placeholder"; // Used if input placeholder is not specified in write method
	validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
	validator.config.modal = mHanler;
	validator.config.smallView = 1020;
	validator.config.feedBackController = fdbckController;
	//validator.config.leftConArrowColor = "blue";
	validator.initialize();

	fullName =  document.querySelector("#ifname");
	fullNameCon =  fullName.parentNode.parentNode;
	validator.format.fullNameField(fullName);


	email =  document.querySelector("#itemail");
	emailCon =  email.parentNode.parentNode;


	phone =  document.querySelector("#itphone");
	phoneCon =  phone.parentNode.parentNode;

	platform = document.querySelector("select[name='platfrm']");
	packageType = document.querySelector("select[name='pkg']");


	validator.format.integerField(phone);

	function validate(){
			data = new FormData();

			//TaskId
			data.append("task", "training");

			//Full name
			if(fullName.value.length == 0){
				validator.message.write(fullNameCon, "error", "right", "Please provide your full name sperated with space");
			}else if (fullName.value.length > 0) {
				check = validator.validate.fullName(fullName.value);
				if(check == true){
					validator.message.clear(fullNameCon);
					//Append to form data
					data.append("fullName", fullName.value);
				}else{
					switch (check) {
						case 2:
							validator.message.write(fullNameCon, "error", "left", "Atleast 2 names needed");
							break;
						case 3:
							validator.message.write(fullNameCon, "error", "left", "All names must be alphabets");
							break;
						case 4:
							validator.message.write(fullNameCon, "error", "left", "All names must be more than 2 characters");
					}
				}
			}

			// email
			if(email.value.length == 0){
				validator.message.write(emailCon, "error", "right", "Please provide your email address");
			}else if (email.value.length > 0) {
				if(validator.validate.emailAddress(email.value)){
					validator.message.clear(emailCon);
					//Append to form data
					data.append("email", email.value);
				}else{
					validator.message.write(emailCon, "error", "left", "Invalid email format");
				}
			}

			//Phone number
			if(phone.value.length == 0){
				validator.message.write(phoneCon, "error", "right", "Please provide your phone number");
			}else if (phone.value.length > 0) {
				if (!validator.validate.phoneNumber(phone.value)){
					validator.message.write(phoneCon, "error", "right", "Does not seem to be a valid phone number format");
				}else{
					 validator.message.clear(phoneCon);
					//Append to form data
					data.append("phoneNumber", phone.value);
				}
			}

			//platform type
			data.append("platformType", platform.value);

			//package type
			data.append("packageType", packageType.value);

		}

	button = document.querySelector("#sdtrng .action button");
	button.addEventListener("click", function(){
		validate();
		if(validator.formOk()){
			validator.submit(data, location.origin+"/process/form/training");
		}
	}, false);
	function fdbckController(data){
		try	{
			jd = JSON.parse(data);
			if(jd["status"] == true){
				validator.showFeedBack(null, "successfully initiated training enrollment, you'll be reached soon.", "success");
			}
		}catch(e){
			validator.showFeedBack(null, "Error while enrolling", "error");
		}
	}

}
function modalHandler(){
	softDev = document.querySelector("#sdevForm");
	rtimesoltn = document.querySelector("#rtimesoltn");
	hireus = document.querySelector("#hireus");
	softdevtrn = document.querySelector("#sdtrng");
	modalCloseButton = document.querySelector("#sdevForm .closeButtonCon button");


	mHanler.config.effect = "split";
	mHanler.config.overlayType = "color";
	mHanler.config.colorOverlayStyle = "background-color:rgba(81, 72, 72, 0.88)";
	// mHanler.config.modalWidths = ["500px", "500px", "86%"];
	// mHanler.config.screenBreakPoints = ["500px", "500px"];
	mHanler.config.openProcessor = function(){
		// console.log(mHanler.thisForm);
		switch (mHanler.thisForm.id) {
			case "sdevForm":
				Form1customFormComponentsBuilder();
				validateForm1();
				break;
			case "rtimesoltn":
				Form2customFormComponentsBuilder();
				validateForm2();
				break;
			case "hireus":
				Form3customFormComponentsBuilder();
				validateForm3();
				break;
			case "sdtrng":
				Form4customFormComponentsBuilder();
				validateForm4();
				break;
		}
		activeFormPlaceHolders();
		if (scrollY>0){
			var hdr = document.querySelector("header");
			hdr.style["position"] = "absolute";
			hdr.style["top"] = scrollY+"px";
		}
	};
	mHanler.config.closeProcessor = function(){
		var hdr = document.querySelector("header");
		hdr.style["position"] = "fixed";
		hdr.style["top"] = "0px";
	};
	mHanler.config.closeButton = modalCloseButton;
	mHanler.initialize();


	//Soft dev application show
	softDevModalAction = document.querySelector("#softDev");
	rtimesoltnModalAction = document.querySelector("#hexpsys");
	hireusModalAction = document.querySelector("#hdevBt");
	softdevtrnModalAction = document.querySelector("#softDevTrn");

	// console.log(softDevModalAction);
	softDevModalAction.addEventListener("click", function(){
		mHanler.show(softDev);
		// console.log(33)
	}, false)
	rtimesoltnModalAction.addEventListener("click", function(){
		mHanler.show(rtimesoltn);
	}, false)
	hireusModalAction.addEventListener("click", function(){
		mHanler.show(hireus);
	}, false)
	softdevtrnModalAction.addEventListener("click", function(){
		mHanler.show(softdevtrn);
	}, false)
}
function anchor(){
		n1 = innerHeight;
		n2 = document.querySelector("#sftdev").clientHeight;
		n3 = document.querySelector("#humanexpsys").clientHeight;
		n4 = document.querySelector("#hireDev").clientHeight;
		n5 = document.querySelector("#sdtrn").clientHeight;

		urlSplit = location.href.split("/");
		if(urlSplit[3] == "services"){
			switch (urlSplit[4]) {
				case "humanexpsys":
					to = n1+n2;
					break;
				case "appdevelopment":
					to = n1;
					break;
				case "hireus":
					to = n1+n2+n3;
					break;
				case "training":
					to = n1+n2+n3+n4;
					break;
				default :
					to = 0;
			}
		}
		scrollTo(0, to);
}
