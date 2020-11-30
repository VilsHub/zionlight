addEventListener("load", function(){
	setUpCustomFields()
}, false)
function setUpCustomFields(){
    var selectCategoriesLarge = document.querySelector("#largeSearchCon #categoriesInput");
    var selectCategoriesSmall = document.querySelector("#smallSearchCon #categoriesInput");
    var CustomFormComponentsObj = new FormComponents(); 
	var customCategoriesField = CustomFormComponentsObj.select();
    customCategoriesField.config.selectSize = ["100%", "36px"];
    customCategoriesField.config.className = "customSelect";
	customCategoriesField.config.wrapperStyle = "position:absolute; font-size:11px;";
	customCategoriesField.config.selectFieldStyle = "background-color:#C2EBF0; color:black;";
	customCategoriesField.config.optionswrapperStyle = "background-color:#3f8088; color:white;";
	customCategoriesField.config.optionStyle = "color:white; border-bottom:solid 1px #31A9B8; ";
	customCategoriesField.config.inputIconStyle = "content:'\\e902';font-family:vicon;color:black;background-color:transparent; background-image:none;font-size:8px;";
	customCategoriesField.config.inputButtonStyle = "border:1px; background-color:#31A9B8; background-image:none;";
	customCategoriesField.autoBuild();
}