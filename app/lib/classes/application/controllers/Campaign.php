<?php
class Campaign extends Controller
{
    function __construct(){
        parent::__construct();
        CSRF::validateToken("CSRF_Token", "newsletterForm");
        $this->campaignModel = $this->loader->loadModel("campaign");
        $this->middleWare("checkIP");
    }

    public function subscribe(){
        $email = $this->xhr->data("clean")->fromPost("email");
        if($this->campaignModel->isSubscribed($email)){//already subscribed
            echo $this->validator->error("email", "Email already subscribed", null, CSRF::getNodeToken("newsletterForm"));
        }else{
            //validate here
            $emailRules = ["required"=>"Email address is needed", "email"=>"This is not a valid email address format"];
            $this->validator->validateInput("email", $emailRules, $email);
            if($this->validator->status){ //no error
                //parse and append Data here
                $data   = [];
                $data[] = $email;
                $data[] = 0;
            
                //send parsed data to model
                $send = $this->campaignModel->subscribe($data);

                if($send["status"]){
                    echo json_encode_with_csrf($send, CSRF::getNodeToken("newsletterForm"));
                }
            }else{
                echo $this->validator->errorsWithCSRF(CSRF::getNodeToken("newsletterForm"));
            }  
        }  
    }

}