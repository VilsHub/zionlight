const mobileView = 650;
let desktopMode="maximised";
runNav();
const defaultNavfamily = $$.ss("#dashboard").getAttribute("data-family");
function runNav(){
    setMode(true);
    $$.attachEventHandler("click", "sub", showSubMenu);
    $$.attachEventHandler("click", "no-sub", activateMenu);
    $$.attachEventHandler("click", ["xbutton","button-proxy"], hideMenu);
    $$.attachEventHandler("click", "menu-button", menuStateController);
   
    var resizer = new Resizer("#sidebar-box");
    resizer.config.callBack = function(){
        fixWidth(resizer.resizedWidth);
    };
    resizer.config.resizeHandlerProperties = {
        parent:$$.ss("#content-box"),
        position:{
            x:"left"
        },
        styles:{
            x:"background-color:red;"
        }
    }
    resizer.config.thresholdValues = {
        minWidth:230,
        maxWidth:302
    }
    resizer.initialize();
    addEventListener("resize", setMode, false);
    $$.sm("#sidebar-content").xScroll();
}
var navFamily = defaultNavfamily;
var level = 0;

function showSubMenu(e){
    var subMenu = e.target.parentNode.querySelector("li > ul");
    var closed = e.target.classList.contains("closed");
    var dontOpen = e.target.classList.contains("dont-open");

    var openedMenu = $$.ss("#links-con p1.opened");
    var effectConfig = {
        timingFunction:"cubic-bezier(.43,.14,0,1.49)", 
        speed:200,
        use:"dimension"
    }

    if((closed && !dontOpen) || (closed && dontOpen == null)){
        if(openedMenu != null){
            $$.sm(openedMenu).slide.toTop(effectConfig);
            openedMenu.classList.remove("opened");
        }
        e.target.classList.remove("closed");
        
        if(!e.target.classList.contains("track")){
            e.target.classList.add("opened", "track-temp");
            if(e.target.getAttribute("data-family") != null) navFamily = e.target.getAttribute("data-family");
            if(e.target.getAttribute("data-family") == null) e.target.classList.add(navFamily);
            resetTrail(e.target);
        }

        $$.sm(subMenu).slide.toBottom(effectConfig, function(){});
    }else{
        e.target.classList.remove("opened");
        e.target.classList.add("closed");
        $$.sm(subMenu).slide.toTop(effectConfig, function(){});
    }

    if(dontOpen){
        e.target.classList.remove("dont-open");
    }
}
function activateMenu(e){
    $$.sm(e.target).makeActive("active", $$.ss("#links-con"));
    if(e.target.getAttribute("data-family") != null) navFamily = e.target.getAttribute("data-family");
    resetTrail(e.target, true);
    markTrail();
}
function fixWidth(newWidth){
    var contentBox = $$.ss("#content-box");
    contentBox.style["width"] = "CALC(100% - "+newWidth+"px)";
    contentBox.style["left"] = newWidth+"px";
}
function markTrail(){
    var tempTrails = $$.sa("#links-con .track-temp");
    if(tempTrails != null){
        tempTrails.forEach(element => {
            element.classList.remove("track-temp");
            element.classList.add("track");
        });
    }
}
function resetTrail(element, removeTrack=false){
    var level = element.parentNode.getAttribute("data-level");
    var others = $$.sa("#links-con [data-level='"+level+"']");
    if(removeTrack){
        var otherTracks = $$.sa("#links-con .track:not(."+navFamily+")");
        var fam = "#links-con .track."+navFamily;
        var familyTracks = $$.sa(fam);
        var activeLevel =  parseInt(element.parentNode.getAttribute("data-level").replace("l", ""));

        if(otherTracks != null){
            otherTracks.forEach(ele => {
                if(ele.getAttribute("data-family") != navFamily){
                    runRemove(ele);
                }
            });
        }

        if(familyTracks != null){
            
            familyTracks.forEach(familyTrack => {
                var levelId = parseInt(familyTrack.parentNode.getAttribute("data-level").replace("l", ""));
                if (levelId >= activeLevel){
                    if(familyTrack != element){
                        runRemove(familyTrack);
                    }
                }else{
                    if(levelId+1 == activeLevel){
                        if(familyTrack != element.parentNode.parentNode.previousElementSibling){
                            runRemove(familyTrack);
                        }
                    }
                }
            });
        }
    }
    
    if(others != null){
        others.forEach(ele => {
            if(ele != element.parentNode){
                var tempTrails = ele.querySelectorAll(".track-temp");
                if(tempTrails != null){
                    tempTrails.forEach(tempTrail => {
                        tempTrail.classList.remove("track-temp");
                        if (tempTrail.classList.contains("closed")) tempTrail.classList.add("dont-open") ;
                        tempTrail.click();
                    });
                }
            }
        });
    }
}
function runRemove(element){
    element.classList.remove("track");
    element.click();
}
function x(ele, width){
    fixWidth(width)
}
function menuStateController(e){
    var xSlideConfigs = {
        timingFunction:"cubic-bezier(.43,.14,0,1.49)", 
        speed:200,
        positions:{
            
        },
        use:"dimension",
        dimensions:{}
    }

    var mobileMode = (innerWidth > mobileView) ? false : true;
    var sideBarBox= $$.ss("#sidebar-box");
    var menuState = e.target.getAttribute("data-state");
    if(!mobileMode){//desktop mode
        if(menuState == "maximised"){ // can minimise 
            xSlideConfigs.dimensions.x = [null, 50];
            $$.sm(sideBarBox).slide.toLeft(xSlideConfigs, x);
            e.target.setAttribute("data-state", "minimised");
            e.target.classList.remove("opened");
            e.target.classList.add("closed");
            minimiseHide(e)
        }else{ //can maximise
            xSlideConfigs.dimensions.x = [50, 230];
            $$.sm(sideBarBox).slide.toRight(xSlideConfigs, x);
            e.target.setAttribute("data-state", "maximised");
            e.target.classList.add("opened");
            e.target.classList.remove("closed");
            maximiseShow();
        }
    }else{//mobile mode
        if(menuState == "hidden"){ // can show 
            xSlideConfigs.positions.x = [-100, 0];
            xSlideConfigs.use = "position";
            sideBarBox.classList.add("mobile");
            $$.sm("#mobile-wrapper").unHide();
            $$.ss("#sidebar-box").style["width"] = "230px";

            $$.sm(sideBarBox).slide.toRight(xSlideConfigs);
            e.target.setAttribute("data-state", "show");
            e.target.classList.add("opened");
            e.target.classList.remove("closed");
        }else{//can hide

        }
    }
}
function setMode(load){
    var appMode =  (innerWidth > mobileView)?"desktop":"mobile";
    $$.ss("#menu-button").setAttribute("data-mode", appMode);

    if(load == true){
        if(appMode == "desktop"){
            $$.ss("#menu-button").setAttribute("data-state", "maximised");
            $$.sm("#links-con > ul > li > a").class.remove("maxMode");
        }else{
            mobileNavState();
        }
    }else{
        if(appMode == "mobile"){
            mobileNavState();
            $$.sm("#mobile-wrapper").hide();
        }else{
            $$.sm("#mobile-wrapper").class.remove("mobile");
            $$.sm("#mobile-wrapper").unHide();           
            $$.ss("#sidebar-box").style.left = "0px";

            desktopNavState(desktopMode);
        }
    }
}
function mMenu(){
    $$.sm("#mobile-wrapper").hide();
}
function hideMenu(e){
    var xslideConfig = {
        use:"position",
        speed:200,
        positions:{
            x:[0, -230]
        }
    }
    sideBarBox= $$.ss("#sidebar-box");
    contentBox= $$.ss("#content-box");
    var menuButton = $$.ss("#menu-button");
    $$.sm("#links-con > ul > li > a").class.remove("maxMode");
    if($$.ss("#links-con > ul > li > .opened") != null) $$.ss("#links-con > ul > li > .opened").click();
    menuButton.setAttribute("data-state", "hidden");
    menuButton.classList.remove("opened", "minimised");
    menuButton.classList.add("closed");
    $$.sm(sideBarBox).slide.toLeft(xslideConfig, mMenu);
}
function mobileNavState(){
    $$.ss("#menu-button").setAttribute("data-state", "hidden");
    $$.sm("#mobile-wrapper").class.add("mobile");
    $$.sm("#links-con > ul > li > a").class.remove("maxMode");
    $$.sm("#menu-button").class.remove("opened");
    $$.sm("#menu-button").class.add("closed");
}

function desktopNavState(state){
    if(state == "maximised"){
        $$.ss("#menu-button").setAttribute("data-state", "maximised");
        $$.sm("#links-con > ul > li > a").class.remove("maxMode");
        $$.sm("#menu-button").class.add("opened");
        $$.sm("#menu-button").class.remove("closed");
        $$.ss("#sidebar-box").style.width = "230px";
    }else{
        $$.ss("#menu-button").setAttribute("data-state", "minimised");
        $$.sm("#links-con > ul > li > a").class.add("maxMode");
        $$.sm("#menu-button").class.remove("opened");
        $$.sm("#menu-button").class.add("closed");
        $$.ss("#sidebar-box").style.width = "50px";
    }
}

function minimiseHide(e){
    $$.sm("#links-con > ul > li > a").class.add("maxMode");
    desktopMode = "minimised";
    if($$.ss("#links-con > ul > li > .track.opened") != null){
        $$.ss("#links-con > ul > li > .track.opened").click(); 
     }else if($$.ss("#links-con > ul > li > .track-temp.opened") != null){
         $$.ss("#links-con > ul > li > .track-temp.opened").click(); 
     } 
    $$.sm("#label img#word-logo").hide();
    $$.sm("#label img#icon-logo").unHide();
    $$.sm("#user-info").hide();
}
function maximiseShow(){
    $$.sm("#links-con > ul > li > a").class.remove("maxMode");
    desktopMode = "maximised";

    if($$.ss("#links-con > ul > li > .track.closed") != null){
       $$.ss("#links-con > ul > li > .track.closed").click(); 
    }else if($$.ss("#links-con > ul > li > .track-temp.closed") != null){
        $$.ss("#links-con > ul > li > .track-temp.closed").click(); 
    } 
    $$.sm("#label img#word-logo").unHide();
    $$.sm("#label img#icon-logo").hide();
    $$.sm("#user-info").unHide("flex");
}



