<?php
/**
 * This class helps to provide file information of a file like mime type, file name, file extenstion and others
 * @author : Stalryvil
 */
use vilshub\helpers\Style;

 class FileInfo {
    private $file;
    private $extensions = [
        "PNG"                       => "png",
        "JPEG"                      => "jpg,jpeg",
        "SVG"                       => "svg",
        "GIF image"                 => "gif",
        "Microsoft Excel"           => "xlsx,xls",
        "Microsoft Word"            => "docx,doc",
        "7-zip"                     => "7z",
        "PDF document"              => "pdf",
        "Audio file with ID3"       => "mp3",
        "WAVE audio"                => "wav",
        "Media, MP4"                => "mp4",
        "Adobe Photoshop"           => "psd",
        "PostScript document"       => "ai",
        "ASCII text"                => "txt,svg,sql,csv",
        "MS Windows icon resource"  => "cur",
        "TrueType Font"             => "ttf",
    ];

    public function __construct($file){
        if(file_exists($file)){
            $this->file = $file;
        }else{
            $msg1 = "Target file $file not found, for ".Style::color(__CLASS__, "black")." contructor. Contructor argument must be a valid file";
            trigger_error($msg1);
        }
    }
 
    public function mimeType(){
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->file);
    }
    public function rawInfo(){
        return finfo_file(finfo_open(FILEINFO_RAW), $this->file);
    }
    public function extension(){
        $rawInfo = $this->rawInfo();
        $extension ="";
        foreach ($this->extensions as $key => $value) {
 
            if(strpos($rawInfo, $key) !== FALSE){
                $extension = $this->extensions[$key];
                break;
            }
        }
        return explode(",", $extension);
    }
 }
?>