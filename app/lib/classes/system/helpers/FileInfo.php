<?php
/**
 * This class helps to provide file information of a file like mime type, file name, file extenstion and others
 * @author : Stalryvil
 */

 class FileInfo {
    private $file;
    private $extensions = [
        "PNG"                   => "png",
        "JPEG"                  => "jpg,jpeg",
        "SVG"                   => "svg",
        "GIF image"             => "gif",
        "Microsoft Excel"       => "xlsx,xls",
        "Microsoft Word"        => "docx,doc",
        "7-zip"                 => "7z",
        "PDF document"          => "pdf",
        "Audio file with ID3"   => "mp3",
        "WAVE audio"            => "wav",
        "Media, MP4"            => "mp4",
        "Adobe Photoshop"       => "psd",
        "PostScript document"   => "ai",
        "ASCII text"            => "txt,sql,csv",
        "ASCII text"            => "txt,svg,sql,csv",
    ];

    public function __construct($file){
        if(file_exists($file)){
            $this->file = $file;
        }else{
            trigger_error("File info");
        }
    }
 
    public function name(){

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