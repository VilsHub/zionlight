runUserAction();
var slideConfig = {
    timingFunction:"cubic-bezier(.43,.14,0,1.49)", 
    speed:200,
    use:"dimension",
    dimensions:{}
}
function runUserAction(){
    $$.attachEventHandler("click", ["q-action", "a-exit"], handleUserAction);
}

function handleUserAction(e, id){
    if(id == "q-action"){
        switch (e.target.id) {
            case 'userAction':
                var state = e.target.getAttribute("data-state");
                if(state == "opened"){
                    e.target.setAttribute("data-state", "closed");
                    slideConfig.dimensions.y = [null]
                    $$.sm("[data-for='userAction']").slide.toTop(slideConfig);
                }else{
                    e.target.setAttribute("data-state", "opened");
                    slideConfig.dimensions.y = [0]
                    $$.sm("[data-for='userAction']").slide.toBottom(slideConfig);
                }
                
                break;
        
            default:
                break;
        }
    }else{
        var button = $$.ss("#userAction");
        button.click();
    }
 
}