"use strict"
addEventListener("load", ()=>{
    placeHolderActivator("BlueBerry_v1_0_0");
    responsiveWrap();
}, false)
function placeHolderActivator(formVersion){
    setPlaceholderState(formVersion);
    var forms = document.querySelectorAll("."+formVersion);
    registerFields(forms);
    document.addEventListener("focusin", function(e){
        if(e.target.getAttribute("data-bb") == "1"){
            var parent = getInputMainWrapper(e.target);
            
            //check if active color is defined
            var activeColor = parent.getAttribute("data-active-color");
            if(activeColor != null){//set color
                //["active", normal]
                var colors = activeColor.split(",");
                e.target.style["border-color"] = colors[0];
                parent.style["border-color"] = colors[0];
            }
            parent.setAttribute("data-state", "active");
        }
    }, false);
    document.addEventListener("focusout", function(e){
        if(e.target.getAttribute("data-bb") == "1"){
            var parent = getInputMainWrapper(e.target);
            if(e.target.value.length < 1) parent.setAttribute("data-state", "inactive");
            
            //check if active color is defined
            var activeColor = parent.getAttribute("data-active-color");
            if(activeColor != null){//unset color
                //["active", normal]
                var colors = activeColor.split(",");
                e.target.style["border-color"] = colors[1];
                parent.style["border-color"] = colors[1];
            }
        }
        
    }, false);
}
function setPlaceholderState(formVersion){
    var formInput = document.querySelectorAll("."+formVersion+ " input");
    var formTextarea = document.querySelectorAll("."+formVersion+ " textarea");
    if(formInput != null){
        var total = formInput.length;
        for(var x = 0; x<total;x++){
            var parent = getInputMainWrapper(formInput[x]);
            if(formInput[x].value.length < 1){
                parent.setAttribute("data-state", "inactive");
            }else{
                parent.setAttribute("data-state", "active");
            }
        }
    }
    if(formTextarea != null){
        var total = formTextarea.length;
        for(var x = 0; x<total;x++){
            var parent = getInputMainWrapper(formTextarea[x]);
            if(formTextarea[x].value.length < 1){
                parent.setAttribute("data-state", "inactive");
            }else{
                parent.setAttribute("data-state", "active");
            }
        }
    }
}
function registerFields(forms){
    forms.forEach(element => {
        var allinputs = element.querySelectorAll("*");
        var total = allinputs.length;
        for(var x=0; x<total; x++){
            if(allinputs[x].getAttribute("type") == "text" || allinputs[x].nodeName == "TEXTAREA" || allinputs[x].getAttribute("type") == "password"){
                allinputs[x].setAttribute("data-bb", "1")
            }
        }
    });
}
function responsiveWrap(){
    wrapInput1();
    wrapInput2();
    addEventListener("resize", ()=>{
        wrapInput1();
        wrapInput2();
    }, false)

    function wrapInput1 (){
        var allWrappable = document.querySelectorAll(".family-input[data-wrapViewPort]");
        var totalWrappable = allWrappable.length;
        for (var x=0; x<totalWrappable; x++){
            var wrapViewPort  = allWrappable[x].getAttribute("data-wrapViewPort");
            if(innerWidth <= wrapViewPort){
                allWrappable[x].classList.add("wrap");
            }else{
                allWrappable[x].classList.remove("wrap");
            }
        }
    }
    function wrapInput2 (){
        var allWrappable = document.querySelectorAll(".labeled-input[data-wrapViewPort]");
        var totalWrappable = allWrappable.length;
        for (var x=0; x<totalWrappable; x++){
            var wrapViewPort  = allWrappable[x].getAttribute("data-wrapViewPort");
            if(innerWidth <= wrapViewPort){
                allWrappable[x].classList.add("wrap");
            }else{
                allWrappable[x].classList.remove("wrap");
            }
        }
    }
}
function getInputMainWrapper(e){
    if(e.parentNode.parentNode.classList.contains("twins-input")){
        return e.parentNode.parentNode;
    }else{
        return e.parentNode;
    }
}