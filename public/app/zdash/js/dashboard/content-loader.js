(function(){
    var contentLoader = new ContentLoader("/{^(admin|student|staff)$}/dashboard/", $$.ss("#content"));
    contentLoader.config.loaderItemClass    = "cloader";
    contentLoader.config.dataAttributes     = {
        containerId:"data-target", /* where to put the loaded content, the id name of the element (no #)*/
		url:"data-url", /*url for ajax requst */
		trigger:"data-triggerOn", /*both or click or load*/
		cache:"data-cache", /* true or false*/
        loaderMode:"data-viewport", //mobile or desktop,
        urlPath:"data-slug" ,
        addToHistory:"data-use-history"
    }
    // contentLoader.config.switchPoint        = 823;
    contentLoader.config.loadCallback       = postLoad;
    contentLoader.config.historyCallback    = postHistory;
    // contentLoader.config.thread             = "single"

    var loaderStyle 						= "font-family:vicon;content:'\\e987'; font-size:30px;";
	contentLoader.config.customStyle 		= [null, null, loaderStyle];
    contentLoader.initialize();
})()

function postLoad(element){
    trailBack(element)
}
function trailBack(element){
    var parentUls = element;
    var links = [element];
    while(parentUls.id != "links-con"){
        parentUls = parentUls.parentNode;
        if(parentUls.nodeName == "UL"){
            var targetLink = parentUls.previousElementSibling;
            if(targetLink != null ) links.push(targetLink);
        }
    }
    links.reverse();
    links.forEach(element => {
        element.click();
    });
}
function postHistory(){

}