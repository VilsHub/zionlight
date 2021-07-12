<?php
class ContentLoader extends Controller
{
    function __construct(){
        parent::__construct();
    }
    public function getHomepage(){
        echo $this->loader->loadPage("/home/main.php", null);
    }
    public function getServicesContent($serviceID){
        if($serviceID == "programming"){
            echo $this->loader->loadPage("/services/programming.php", null);
        }else if($serviceID == "systemadmin"){
            echo $this->loader->loadPage("/services/systemadmin.php", null);
        }
    }
    public function getAboutMeContent($contentID){
        switch ($contentID) {
            case 'bdetails':
                echo $this->loader->loadPage("/aboutme/bdetails.php", null);
                break;
            case 'career':
                echo $this->loader->loadPage("/aboutme/career.php", null);
                break;
            case 'contacts':
                echo $this->loader->loadPage("/aboutme/contacts.php", null);
                break;
            default:
                # code...
                break;
        }
    }
}
?>