addEventListener("load", function(){
    mobileOn =false;
    launchContentLoader();

}, false);

function launchContentLoader(){
    n = 0;
    var contentLoader = new ContentLoader();
    contentLoader.config.loaderItemClass = "cloader";
    contentLoader.config.dataAttributes = {
        containerId:"data-target",
		url:"data-url",
		trigger:"data-triggerOn",
		cache:"data-cache",
		loaderMode:"data-viewport" //mobile or desktop
    }
    contentLoader.config.switchPoint = 823;
    contentLoader.config.callback = function(e){
        // #linksCon is the parent of links
        id = e.getAttribute("data-id");

        if(innerWidth <= 823) { // small
            $$.sm(e).makeActive("lactive", $$.ss("#mNav > #linksCon"));
            //set mobile active
            
            DesktopButton = $$.ss("#myNavLinkCon > #linksCon [data-id='"+id+"']")
            $$.sm(DesktopButton).makeActive("lactive", $$.ss("#myNavLinkCon > #linksCon"));


            //set desktop button
            mobileMenuList();

            //fix nav
            if (n==0) $$.ss("#mNav > #linksCon [data-id='bi']").classList.add("lactive");
            n++;
        }else{ //large
            $$.sm(e).makeActive("lactive", $$.ss("#myNavLinkCon > #linksCon"));
            
            //set mobile active
            mobileButton = $$.ss("#mNav > #linksCon [data-id='"+id+"']")
            $$.sm(mobileButton).makeActive("lactive", $$.ss("#mNav > #linksCon"));
        }
        writeIntro();
        cardsOverlay();
        if (e.id == "cr") buildTimelineList();
    };
    var loaderStyle 						= "font-family:vicon;content:'\\e987'; font-size:30px;";
	contentLoader.config.customStyle 		= [null, null, loaderStyle];
    contentLoader.initialize();
}
function cardsOverlay(){
    style =" background-image: linear-gradient(to top, #312f304f 0%, #000 100% )";
    $$.sm(".card").addOverlay(style);
}
function buildTimelineList(){
    var timeLineListObj = new TimeLineList();
    timeLineListObj.config.className = "timeLlist";
    timeLineListObj.config.dataAttributes = {
        timeLineBorderStyle:"data-bcolor",
        listStyle:"data-liStyle",
        listIconStyle:"",
        timeLineLabel:"",
        smallView:"data-mobileView"
    };
    timeLineListObj.autoBuild();
}
function mobileMenuList(){
    if(!mobileOn){
        listParent = $$.ss("#mNav > #linksCon");
        listCon = listParent.querySelector("ul");
        listControllerObj = new ListScroller(listParent, listCon);
        LeftBt = $$.ss("#lft button");
        RightBt = $$.ss("#rgt button");

        listControllerObj.config.buttons = [LeftBt, RightBt];
        listControllerObj.config.inactiveButtonsClassName = ["off"];
        listControllerObj.config.effects = [0.3, "linear"];
        listControllerObj.config.scrollSize = 155 // 170+5px margin for each
        listControllerObj.config.menuWidth = 155 // 170+5px margin for each
        listControllerObj.config.paddingLeft = 60;
        listControllerObj.config.paddingRight = 60;
        listControllerObj.initialize();
        listControllerObj.onScroller();
        mobileOn = true;
    }
    
}
function writeIntro(){
    if(!sessionStorage.intro){
        $$.ss("#blurBg").style["display"] = "block";
        $$.sm("#page").filter(["grayscale", "blur"], [100, "10px"]);

        $$.sm("#intro").center();
        $$.ss("#intro").style["width"] = "0%";
        $$.ss("#intro").scrollHeight;
        $$.ss("#intro").style["width"] = "70%";

        writer = new AutoWriter();
        writer.config.callBackDelay = 1100;
        writer.config.typingSpeed = [100, 200];
        writer.config.showCursor = true;
        writer.config.cursorBlinkDelay = 350;
        writer.config.cursorStyle = {style:"solid", width:"2px", color:"cyan"};
        textBox = $$.ss("#intro p");

        text = "I have been hidden in the past for so long, building~2500~ up my self. Now, it's time time*4* to let the world know about me.";
        $$.delay(280);
        writer.writeText(textBox, text, future);
    }
}
function future(){
    var options = {
        callBack: showContent,
        duration:"1.5s"
    }
    $$.sm("#page").filter(["grayscale", "blur"], [0, 0], options); 
}
function showContent(){
    var height = $$.sm("#intro").cssStyle("height");
    console.log(height);
    var consoleBox = $$.ss("#intro");
    consoleBox.style["height"] = height;
    consoleBox.style["transform"] = "scale(0)";
    $$.delay(300, function(){
        $$.ss("#blurBg").style["display"] = "none";
        sessionStorage.intro = true;
    });
}