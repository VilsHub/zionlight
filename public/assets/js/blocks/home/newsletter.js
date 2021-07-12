// JavaScript Document
window.addEventListener("load", function() {
  registerNewEmail();
},false)


function registerNewEmail(){
  form = $$.ss("#newsletter");
  var validator = new FormValidator(form);
  validator.config.progressIndicatorStyle = "content:'\\e988'; font-family:vicon; color:blue; font-size:40px";
  validator.config.feedBackController = feedBackContl;
  validator.config.submitButtonClass = "subBt";
  validator.config.loaderLocation = "button";
  validator.config.CSRFTokenElement = $$.ss("[name='newsletterForm'");
  validator.initialize();

  function feedBackContl(validatorObj, data){
    try {
        jd = JSON.parse(data);
        if (jd["status"]) {
            validatorObj.showFeedback(null, "Subscribed Successfully", "success");
        }else if(!jd["status"]){
            validatorObj.logServerError(jd["log"]);
        }
        validatorObj.updateCSRFToken(jd["csrf"]);
    } catch (e) {
        validatorObj.showFeedback(null, "Error processing newsletter subscribtion", "error");
    }
  }

  function validate(){
      var email = document.querySelector("#newsletter #email");
      var emailRules = {
          "required"  : "Please provide your email address",
          "email"     : "Invalid email format",
          "trim"      : ""
      }

      validator.validate(email, emailRules, "bottom", [35, null]);
  }

  button = document.querySelector("#newsletter button");

  button.addEventListener("click", function() {
      validate();
      if (validator.formOk()) {
          validator.submit({task:"newsletter"}, "/api/newsletter");
      }
  }, false);

}
