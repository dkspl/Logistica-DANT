<?php

class Render{
    private $mustache;

    public function __construct($partialsPathLoader){
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
            'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsPathLoader )
        ));
    }

    public function render($contentFile , $data = array() ){
        $contentAsString =  file_get_contents($contentFile);
        $data["session"] = isset($_SESSION["user"]);
        return  $this->mustache->render($contentAsString, $data);
    }
    public function renderPdf($contentFile , $data = array() ){
        $plantilla=$this->render($contentFile,$data);
        $mpdf=new \Mpdf\Mpdf();
        $css=file_get_contents("public/css/styles.css");
        $w3css=file_get_contents("https://www.w3schools.com/w3css/4/w3.css");
        $mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->writeHtml($w3css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->writeHtml($plantilla);
        $mpdf->output();
    }
}