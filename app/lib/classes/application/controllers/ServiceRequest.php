<?php
class ServiceRequest extends Controller
{
    function __construct($serviceId){
        parent::__construct();
        $this->serviceId = $serviceId;
        CSRF::validateToken("CSRF_Token", $serviceId);
        $this->serviceRequestModel = $this->loader->loadModel("serviceRequest");
    }
    public $serviceId;
    public function softwareDevlopment(){
        //validate here

        //parse and append Data here
        $data = [];
        $data[] = $this->xhr->data("clean")->fromPost("ProjectName");
        $data[] = $this->xhr->data("clean")->fromPost("ProjectDescription");
        $data[] = $this->xhr->data("clean")->fromPost("ProjectPlatform");
        $data[] = $this->xhr->data("clean")->fromPost("ProjectDuration");
        $data[] = $this->xhr->data("clean")->fromPost("ProjectDurationUnit");
        $data[] = $this->xhr->data("clean")->fromPost("ProjectBudget");
        $data[] = $this->xhr->data("clean")->fromPost("ProjectBudgetUnit");
        $data[] = $this->xhr->data("clean")->fromPost("emailAddress");
        $data[] = $this->xhr->data("clean")->fromPost("phoneNumber");
        
        //send parsed data to model
        $send = $this->serviceRequestModel->appDev($data);

        
        if($send["status"]){
            echo json_encode_with_csrf($send, CSRF::getNodeToken($this->serviceId));
        }
    }
    public function realtimeSolutionSub(){
        
        //validate here

        //parse and append Data here
        $time = time();
        $data = [];
        $data[] = $this->xhr->data("clean")->fromPost("fName");
        $data[] = $this->xhr->data("clean")->fromPost("riemail");
        $data[] = $this->xhr->data("clean")->fromPost("rdomain");
        $data[] = $time;
        $data[] = "1";
        
        //send parsed data to model
        //Register user
        $send = $this->serviceRequestModel->subscribeUser($data);

        
        if($send["status"]){
            //validate here
            $channelsID     =$this->xhr->data()->fromPost("channelID");
            $channelsValue  =$this->xhr->data()->fromPost("channelValue");

            //build data
            $data = [];
            foreach ($channelsID as $key=>$value){
                $data[] = [$send["lastInsertId"], $value, $channelsValue[$key]];
            };

            //add selected domains to db
            $bsend =  $this->serviceRequestModel->addUserDomains($data);

            if($bsend["status"]){
                $date   = new DateTime('@'.$time);
                $date->add(new DateInterval("P30D"));
                $bsend["expires"]   = $date->format('jS \o\\f F, Y');
                echo json_encode_with_csrf($bsend, CSRF::getNodeToken($this->serviceId));
            }
        }
    }
    public function bookForDev(){

        //validate here


        //parse and append Data here
        $data = [];
        $data[] = $this->xhr->data("clean")->fromPost("ttypei");
        $data[] = $this->xhr->data("clean")->fromPost("tdescription");
        $data[] = $this->xhr->data("clean")->fromPost("itdate");
        $data[] = $this->xhr->data("clean")->fromPost("hiemail");
        $data[] = $this->xhr->data("clean")->fromPost("hiphone");

        //send parsed data to model
        //Book me
        $send = $this->serviceRequestModel->bookMe($data);
        
        if($send["status"]){
            echo json_encode_with_csrf($send, CSRF::getNodeToken($this->serviceId));
        }

    }
    public function enroll(){

        //validate here


        //parse and append Data here
        $data = [];
        $data[] = $this->xhr->data("clean")->fromPost("ifname");
        $data[] = $this->xhr->data("clean")->fromPost("itemail");
        $data[] = $this->xhr->data("clean")->fromPost("itphone");
        $data[] = $this->xhr->data("clean")->fromPost("platfrm");
        $data[] = $this->xhr->data("clean")->fromPost("pkg");

        //send parsed data to model
        //Book me
        $send = $this->serviceRequestModel->enroll($data);
        
        if($send["status"]){
            echo json_encode_with_csrf($send, CSRF::getNodeToken($this->serviceId));
        }
    }
}
?>