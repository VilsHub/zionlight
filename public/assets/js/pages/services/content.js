// JavaScript Document
window.addEventListener("load", function() {
    beakPoints = {
        largeStart: 1000,
        mediumStart: 600
    }
    sbPoint = new ScreenBreakPoint(beakPoints);
    mHanler = new ModalDisplayer();
    CustomFormComponentsObj = new FormComponents();
    fix();
    scrollController();
    DomainListController();
    //typo();
    gridFix();
    countdown();
    modalHandler();
    anchor();
    customFormComponentsBuilder();
    domainContentLoader();
    DomainSubContentController();
}, false);

function scrollController() {
    scrolled = 0;
    arrowComp1 = document.getElementById("leftArrowSide");
    arrowComp2 = document.getElementById("rightArrowSide");
    arrowComp3 = document.getElementById("line");
    scrollButton = document.getElementById("scrollBt");
    scrollCon = document.getElementById("scrollCon");
    scrollButton.addEventListener("click", function() {
        scrollCon.style["animation-name"] = "none";
        arrowComp1.style["animation-name"] = "none";
        arrowComp2.style["animation-name"] = "none";
        arrowComp3.style["animation-name"] = "none";
        scrollCon.style["opacity"] = "0";
        $$.animate(animateScroll, 428, 400, "swingEaseIn");
        scrolled = 1;
    }, false);

    function animateScroll(x) {
        window.scrollTo(0, x);
    };
    scrollCon.style["animation-name"] = "bounce";
    arrowComp1.style["animation-name"] = "fade";
    arrowComp2.style["animation-name"] = "fade";
    arrowComp3.style["animation-name"] = "fade";

    window.addEventListener("scroll", function() {
        if (scrolled != 1) {
            scrollCon.style["animation-name"] = "none";
            arrowComp1.style["animation-name"] = "none";
            arrowComp2.style["animation-name"] = "none";
            arrowComp3.style["animation-name"] = "none";
            scrollCon.style["opacity"] = "0";
        }
    }, false);
    scrollCon.addEventListener("transitionend", function() {
        this.style["display"] = "none";
        this.style["opacity"] = "0";
        scrolled = 1;
    }, false);
}

function DomainSubContentController() {
    buttons = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .subNav .Btcon");

    buttons.addEventListener("click", function(e) {
        if (e.target.nodeName == "BUTTON" && e.target.classList.contains("Bselected") == false) {
            id = e.target.id;

            detailsCon = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent .subContent .det");
            channelCon = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent .subContent .chnl");

            if (id == "channels") {
                buttons.querySelector(".Bselected").classList.remove("Bselected");

                detailsCon.style["display"] = "none";
                channelCon.style["opacity"] = 0;
                channelCon.style["display"] = "block";
                setTimeout(function() {
                    e.target.classList.add("Bselected");
                    channelCon.style["opacity"] = "1";

                }, 300);
            } else if (id == "details") {
                buttons.querySelector(".Bselected").classList.remove("Bselected");

                channelCon.style["display"] = "none";
                detailsCon.style["opacity"] = 0;
                detailsCon.style["display"] = "block";
                setTimeout(function() {
                    e.target.classList.add("Bselected");
                    detailsCon.style["opacity"] = "1";

                }, 300);
            }
        }
    }, false);

}

function DomainListController() {
    list = document.getElementById("domainList");
    listCon = list.querySelector("ul");
    LeftBt = document.getElementById("upbt");
    RightBt = document.getElementById("downbt");

    container = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .content .mainContent");


    listControllerObj = new ListScroller(list, listCon);

    listControllerObj.config.buttons = [LeftBt, RightBt];
    listControllerObj.config.inactiveButtonsClassName = ["deactivate"];
    listControllerObj.config.effects = [0.3, "linear"];
    listControllerObj.config.scrollSize = 175 // 170+5px margin for each
    listControllerObj.config.paddingLeft = 50;
    listControllerObj.config.paddingRight = 50;
    listControllerObj.initialize();
    listControllerObj.onScroller();
}

function domainContentLoader(){
    var contentLoader = new ContentLoader();
    contentLoader.config.loaderItemClass    = "cloader";
    contentLoader.config.cache              = true;
    contentLoader.config.dataAttributes     = {
        containerId:"data-target",
		url:"data-url",
		trigger:"data-triggerOn",
		cache:"data-cache"
    }
    contentLoader.config.callback           = function(e){
        $$.sm(e).makeActive("Dselected", $$.ss("#domainList"))
        setSubTabs();
    };
    var loaderStyle 					    = "font-family:vicon;content:'\\e987'; font-size:30px;";
	contentLoader.config.customStyle 		= [null, null, loaderStyle];
    contentLoader.initialize();
}

function setSubTabs() {
    buttons = document.querySelector(".page #content .serDetailsCon .details .detailsSummary .domainCon #domContent .subNav .Btcon");
    activeButton = buttons.querySelector(".Bselected");
    if (activeButton.id != "details") {
        activeButton.classList.remove("Bselected");
        buttons.querySelector("#details").classList.add("Bselected");
    }
}

function typo() {
    var baseGrid = new vGrid();
    baseGrid.height = 23;
    baseGrid.lineColor = "yellow";
    baseGrid.toolFontColor = "cyan";
    baseGrid.initialize();
    baseGrid.ForceONWithNoGrid();
}

function gridFix() {
    var TargetElement = document.getElementById("pID");
    var summaryCon = document.getElementById("contentCon");
    vRhythm.setHeight(TargetElement, 23);
    vRhythm.placeAtCenter(summaryCon, 23);
}

function countdown() {
    var DateLine = document.getElementById("countDown").innerHTML;
    var apiUrl = "http://api.vilshub.com/countdown";
    var DateLineWrap = new FormData();
    DateLineWrap.append("dateline", DateLine);

    //Count down
    var XHR = $$.ajax({method:"POST", url:apiUrl}, "text");
    XHR.addEventListener("load", function() {
        var decoded = JSON.parse(this.responseText);
        var compBlock = document.getElementById("vCountDown");
        var CountDownRunner = new countDown(decoded, compBlock);
        CountDownRunner.initialize();
        CountDownRunner.start();
    }, false);
    XHR.send(DateLineWrap);
}

function toggleChannel(target) {
    equivInput = document.querySelector("input[data-refId='"+target.id+"']");
    if (target.checked) {
        equivInput.getAttribute("data-cache") != undefined ? equivInput.value = equivInput.getAttribute("data-cache") : null;
        equivInput.classList.toggle("disabled");
        equivInput.disabled = false;
    } else {
        equivInput.setAttribute("data-cache", equivInput.value);
        equivInput.classList.toggle("disabled");
        equivInput.value = "";
        equivInput.disabled = true;
    }

}

function customFormComponentsBuilder() {
    // Select
    var customSelect = CustomFormComponentsObj.select();
    customSelect.config.sizeAttribute = "data-dim";
    customSelect.config.className = "customSelect";
    customSelect.config.includeSearchField = false;
    customSelect.config.wrapperStyle = "font-size:11px;";
    customSelect.config.selectFieldStyle = "background-color:rgb(195, 205, 206); color:black;";
    customSelect.config.optionswrapperStyle = " color:white;";
    customSelect.config.optionStyle = "color:white; background-color:rgb(195, 205, 206);";
    customSelect.config.inputIconStyle = "content:'\\e902';font-family:vicon;color:purple;background-color:transparent; background-image:none;font-size:8px;";
    customSelect.config.inputButtonStyle = "border:1px; background-color:#9b9794; background-image:none;";
    customSelect.config.optionStateStyle = ["color:purple", ""];
    customSelect.config.wrapAttribute = "data-wrapViewPort";
    customSelect.autoBuild();

    // Radio
    var customRadio = CustomFormComponentsObj.radio();
    customRadio.config.className = "customRadio";
    customRadio.config.axisClass = ["radio-group-x", "radio-group-y"];
    customRadio.config.radioButtonSize = ["15px", "15px"];
    customRadio.config.selectedRadioStyle = "border:solid 1px #ddb2d8; content:''; background-image:linear-gradient(to bottom, #BA26C7 0%, #73157B 100%);";
    customRadio.config.deselectedRadioStyle = "background-color:#ccc; content:''; background-image:linear-gradient(to bottom, #ccc 0%, white 100%);";
    hover = "box-shadow:0 0 6px #ba26c7;";
    clicked = "background-image:linear-gradient(to top, #ccc 0%, white 100%)!important;";
    customRadio.config.mouseEffectStyle = [hover, clicked];
    customRadio.autoBuild();

    //Check box
    var customCheckBox = CustomFormComponentsObj.checkbox();
    customCheckBox.config.className = "customCheckBox";
    customCheckBox.config.checkboxSize = ["15px", "15px"];
    customCheckBox.config.checkedCheckboxStyle = "content:'\\ea5e'; font-family:vicon; line-height:15px; color:purple; border:solid 1px #ddb2d8; background-color:white;";
    customCheckBox.config.uncheckedCheckboxStyle = "content:'\\ea5f'; font-family:vicon;  line-height:15px; color:purple; background-color:#ccc;; background-image:linear-gradient(to bottom, #ccc 0%, white 100%);";
    customCheckBox.config.wrapperStyle = "background-color:white; margin-top:11px";
    hover = "box-shadow:0 0 6px #ba26c7;";
    clicked = "background-image:linear-gradient(to top, #ccc 0%, white 100%)!important;";
    customCheckBox.config.mouseEffectStyle = [hover, clicked];
    customCheckBox.autoBuild();

    //Date picker
    var vUxDatePicker = CustomFormComponentsObj.datePicker();
    var IconStyle = "color:#858585; border:none; content:'\\e95f'; font-family:vicon; font-size:20px; line-height:38px;";
    vUxDatePicker.config.inputIconStyle = [IconStyle];
    vUxDatePicker.config.sizeAttribute = "data-dim";
    vUxDatePicker.config.className = "myCustomDatePicker";
    vUxDatePicker.config.validationAttribute = "data-ok";
    vUxDatePicker.config.mobileView = 600;
    vUxDatePicker.config.daysToolTip = true;
    vUxDatePicker.config.wrapAttribute = "data-wrapViewPort";
    vUxDatePicker.autoBuild();
}

function validateForm1() {
    form = document.querySelector("#sdevForm");
    validator = new FormValidator(form);
    tokenElement =  $$.ss("[name='appDev']");
    // validator.config.leftConStyle = "top:34px; height:36px;";
    // validator.config.rightConStyle = "top:34px; height:36px;";
    // validator.config.bottomConStyle  = "top:CALC(100% + 5px); height:36px;";
    validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
    validator.config.modal = mHanler;
    validator.config.feedBackController = feedBackController;
    validator.config.smallView = 877;
    validator.config.CSRFTokenElement = tokenElement;
    validator.config.wrapperClassAttribute = "data-wrapperClass";
    validator.initialize();

    //inputs
    projectName = $$.ss("#pName");
    description = $$.ss("#pDesc");
    platform = $$.sa("#pPltfm input[type='radio']");
    projectDuration = $$.ss("#ipduration");
    projectBudget = $$.ss("#ipbudget");
    durationUnit = $$.ss("#durationUnit");
    budgetUnit = $$.ss("#budgetUnit");
    emailAddress = $$.ss("#diemail");
    phoneNumber = $$.ss("#diphone");


    //Format fields
    FormValidator.format.integerField(projectDuration);
    FormValidator.format.integerField(projectBudget);
    FormValidator.format.integerField(phoneNumber);
    FormValidator.format.integerField(projectDuration);

    button = document.querySelector("#sdevForm .action button");

    function validateFields() {
        var projectNameRules = {
            "required": "Please provide the name of the project",
            "alphaNum": "Project name can only be alpha numeric",
            "notIn:< > / @": "The followings characters are not allowed '<', '>', '/', '@'"
        }
        var descriptionRules = {
            "required": "Please provide the description of the project",
            "notIn:! < >": "Please exclude the following charaters <, >, !"
        };
        var platformRules = {
            "required": "Please select the project platform",
        }
        var projectDurationRules = {
            "required": "Please provide the project duration"
        }
        var projectBudgetRules = {
            "required": "Please provide the project budget"
        }
        var emailAddressRules = {
            "required": "Please provide your email address",
            "email": "The provided email is not a valid format"
        }
        var phoneNumberRules = {
            "required": "Please provide your phone number",
            "minLength:6": "The provided phone number is too short",
            "maxLength:16": "The provided phone number is too long"
        }

        validator.validate(projectName, projectNameRules, "left");
        validator.validate(description, descriptionRules, "bottom");
        validator.validate(platform, platformRules, "right");
        validator.validate(projectDuration, projectDurationRules, "left");
        validator.validate(projectBudget, projectBudgetRules, "bottom");
        validator.validate(emailAddress, emailAddressRules, "right");
        validator.validate(phoneNumber, phoneNumberRules, "right");
    }

    function feedBackController(validatorObj, data) {
        
        try {
            jd = JSON.parse(data);
            if (jd["status"]) {
                validatorObj.showFeedback(null, "Request submitted successfully!", "success");
            }else if(!jd["status"]){
                validatorObj.logServerError(jd["log"]);
            }
            validatorObj.updateCSRFToken(jd["csrf"]);
        } catch (e) {
            validatorObj.showFeedback(null, "Error submiting request", "error");
        }

    }

    button.addEventListener("click", function() {
        validateFields();
        if(validator.formOk()){
        	validator.submit({"task": "AppRequest", "appDev": tokenElement.value}, "/api/services/appDev");
        }
    }, false);

}

function validateForm2() {  
    form = document.querySelector("#rtimesoltn");
    validator = new FormValidator(form);
    tokenElement = $$.ss("[name='realTimeSub']");

    validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
    validator.config.modal = mHanler;
    validator.config.CSRFTokenElement = tokenElement;
    validator.config.feedBackController = feedBackController;
    validator.config.smallView = 877;
    validator.config.wrapperClassAttribute = "data-wrapperClass";
    validator.initialize();

    //inputs
    firstName = $$.ss("#fName");
    email = $$.ss("#riemail");
    domain = $$.ss("#rdomain");
    channels = form.querySelectorAll("input[type='checkbox']");

    FormValidator.format.integerField(form.querySelector("INPUT[data-refid='phone']"));
    FormValidator.format.integerField(form.querySelector("INPUT[data-refid='telegram']"));
    FormValidator.format.integerField(form.querySelector("INPUT[data-refid='whatsapp']"));
    
    //rules
   

    button = $$.ss("#rtimesoltn .action button");

    function validateChannelValues(checkBoxes) {
        var rules = [
            {
                "required"  : "Please provide the selected channel data",
                "integer"   : "Only numeric values are allowed",
            },
            {
                "required"  : "Please provide the selected channel data",
                "trim"      : "",
                "noSpace"   : "No space is allowed"
            },
        ]
        for (x = 0; x < checkBoxes.length; x++) {
            val = form.querySelector("input[data-refid='" + checkBoxes[x].id + "']");  
            if (checkBoxes[x].checked) {
                var targetRule = (x > 0) ? rules[0]:rules[1];
                validator.validate(val, targetRule, "right");                
            }
        }
    }

    //format fields
    function validate() {
        var firstNameRules = {
            "required"      : "Please provide your first name",
            "alpha"         : "Only alphabets are allowed",
            "minLength:2"   : "Provided name is too short",
            "noSpace"       : "No space is allowed",
            "trim"          : ""
        }
        var emailRules = {
            "required"  : "Please provide your email address",
            "email"     : "Email supplied is not of a valid format"
        }
        var channelsRules  = {
            "required" : "Please select at least a channel to be used for communication"
        }

        //First name
        validator.validate(firstName, firstNameRules, "right");

        //Email
        validator.validate(email, emailRules, "right");


        //Channels
        validator.validate(channels, channelsRules, "bottom", [null, "height:35px;"]);

        if(validator.formOk()){
            //validated selected channels
            var selectedChannels = form.querySelectorAll("input[type='checkbox']:checked");
            validateChannelValues(selectedChannels);

        }
    }

    function feedBackController(validatorObj, data) {
        try {
            jd = JSON.parse(data);
            if (jd["status"]) {
                validatorObj.showFeedback(null, "Subscribed successfuly! \n to be expired on the: " +jd["expires"], "success");
            }else{
                
            }
            validatorObj.updateCSRFToken(jd["csrf"]);
        } catch (e) {
            validatorObj.showFeedback(null, "Error while processing subscription", "error");
        }
    }

    $$.attachEventHandler("click", "customCheckBox", (e)=>{
        toggleChannel(e.target);
    })

    button.addEventListener("click", function() {
        validate();
        if (validator.formOk()) {
            validator.submit({task:"realTimeSub", "realTimeSub":tokenElement.value}, location.origin + "/api/services/realTimeSub");
        }
    }, false);

}

function validateForm3() {
    form = document.querySelector("#hireus");
    validator = new FormValidator(form);
    tokenElement = $$.ss("[name='bookDev']");

    validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
    validator.config.modal = mHanler;
    validator.config.CSRFTokenElement = tokenElement;
    validator.config.feedBackController = feedBackController;
    validator.config.smallView = 877;
    validator.config.wrapperClassAttribute = "data-wrapperClass";
    validator.initialize();

    //inputs
    taskDescription = $$.ss("#tdescription");
    email = $$.ss("#hiemail");
    phone = $$.ss("#hiphone");
    taskDate = $$.ss("#itdate");

    //format fields
    FormValidator.format.integerField(phone);

    button = $$.ss("#hireus .action button");

    function valDate(){
        var status = taskDate.getAttribute("data-ok");

        if(taskDate.value == ""){
            return 1;
        }else if(status == "false"){
            return 2;
        }else if(status){
            return true;
        }
    }

  
    //validate
    function validate() {
        //Rules
        taskDescriptionRules = {
            "required" : "Please provide the task description",
            "notIn:< > / @": "The cahracters '<', '>', '/' and '@' are not allowed",
            "minLength:15" : "Task description too short"
        }
        taskDateRules = {
            "callBack" : [valDate, "Please provide the date and time for the task", "Incomplete date provided"]
        }
        emailRules = {
            "required" : "Please provide your email address",
            "email" : "Invalid email format provided"
        }
        phoneRules = {
            "required"      : "Please provide your phone number",
            "minLength:7"   : "Phone number too short"
        }

        //validate
        validator.validate(taskDescription, taskDescriptionRules, "bottom");
        validator.validate(taskDate, taskDateRules, "right");
        validator.validate(email, emailRules, "right");
        validator.validate(phone, phoneRules, "right");
    }

    function feedBackController(validatorObj, data) {
        try {
            jd = JSON.parse(data);
            if (jd["status"]) {
                validatorObj.showFeedback(null, "Booked successfully, you'll receive a response from me within 24 hours.",  "success");
            }else{
                
            }
            validatorObj.updateCSRFToken(jd["csrf"]);
        } catch (e) {
            validatorObj.showFeedback(null, "Error while processing booking", "error");
        }
    }

    button.addEventListener("click", function() {
        validate();
        if (validator.formOk()) {
            validator.submit({task:"bookDev", "bookDev":tokenElement.value}, location.origin + "/api/services/bookDev");
        }
    }, false);
}

function validateForm4() {
    form = document.querySelector("#sdtrng");
    validator = new FormValidator(form);
    tokenElement = $$.ss("[name='training']");

    validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
    validator.config.modal = mHanler;
    validator.config.CSRFTokenElement = tokenElement;
    validator.config.feedBackController = feedBackController;
    validator.config.smallViewAttribute = "data-smallView";
    validator.config.wrapperClassAttribute = "data-wrapperClass";
    validator.initialize();

    //inputs
    fullName = document.querySelector("#ifname");
    email = document.querySelector("#itemail");
    phone = document.querySelector("#itphone");

    packageType = document.getElementsByName("pkg");
    packageType[0].addEventListener("change", ()=>{
        platformChange();
    }, false)

    //format fields
    FormValidator.format.wordSeperator(fullName, " ");
    FormValidator.format.integerField(phone);

    
    //validate
    function validate() {
        fullNameRules = {
            "required"  : "Please provide your full name",
            "alpha"     : "Only alphabets is allowed",
            "trim"      : "",// 
            "fullName:3": ["Atleast 2 full names needed", "Maximum of 3 full names need", "All names must be more than 1 charater"]
        }

        emailRules = {
            "required"  : "Please provide your email address",
            "email"     : "Invalid email format",
            "trim"      : ""
        }

        phoneRules = {
            "required"      : "Please provide your phone number",
            "minLength:7"   : "Phone number too short",
            "maxLength:15"  : "Phone number too long",
            "trim"          : ""
        }

        validator.validate(fullName, fullNameRules, "right");
        validator.validate(email, emailRules, "right");
        validator.validate(phone, phoneRules, "right");

    }

    button = document.querySelector("#sdtrng .action button");

    function feedBackController(validatorObj, data) {
        try {
            jd = JSON.parse(data);
            if (jd["status"]) {
                validatorObj.showFeedback(null, "successfully initiated training enrollment, you'll be contacted soon.", "success");
            }
            validatorObj.updateCSRFToken(jd["csrf"]);
        } catch (e) {
            validatorObj.showFeedback(null, "Error processing enrollment", "error");
        }
    }   

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

    button.addEventListener("click", function() {
        validate();
        if (validator.formOk()) {
            validator.submit({task:"training", "training":tokenElement.value}, location.origin + "/api/services/training");
        }
    }, false);
}

function modalHandler() {
    mHanler.config.effect = "split";
    mHanler.config.overlayBackgroundType = "blur";
    mHanler.config.pageContainer = document.querySelector(".page");
    mHanler.config.modalWidthsAttribute = "data-modalWidths";
    mHanler.config.className = "vUxModal";
    mHanler.config.formIdAttribute = "data-formId";
    mHanler.config.openProcessor = function() {
        switch (mHanler.thisForm.id) {
            case "sdevForm":
                validateForm1();
                break;
            case "rtimesoltn":
                validateForm2();
                break;
            case "hireus":
                validateForm3();
                break;
            case "sdtrng":
                validateForm4();
                break;
        }
        if (scrollY > 0) {
            var hdr = document.querySelector("header");
            hdr.style["position"] = "absolute";
            hdr.style["top"] = scrollY + "px";
        }
    };
    mHanler.config.closeProcessor = function() {
        var hdr = document.querySelector("header");
        hdr.style["position"] = "fixed";
        hdr.style["top"] = "0px";
    };
    mHanler.config.closeButtonClass = "closeBtn";
    mHanler.initialize();
}

function anchor() {
    n1 = innerHeight;
    n2 = $$.ss("#sftdev").clientHeight;
    n3 = $$.ss("#humanexpsys").clientHeight;
    n4 = $$.ss("#hireDev").clientHeight;
    n5 = $$.ss("#sdtrn").clientHeight;

    urlSplit = location.href.split("/");
    if (urlSplit[3] == "services") {
        switch (urlSplit[4]) {
            case "realtimesolution":
                to = n1 + n2;
                break;
            case "appdevelopment":
                to = n1;
                break;
            case "hireme":
                to = n1 + n2 + n3;
                break;
            case "training":
                to = n1 + n2 + n3 + n4;
                break;
            default:
                to = 0;
        }
    }
    scrollTo(0, to);
}
function fix(){
    var t = $$.ss("#trninfo .labeled-input.pox-x");
    if(innerWidth <= 519){
        t.classList.remove("pox-x");
        t.classList.add("wrap");
    }else{
        t.classList.add("pox-x");
        t.classList.remove("wrap");
    }

    $$.browserType("chrome", ()=> $$.linkStyleSheet("/assets/css/pages/services/chromiumFix.css"));
}