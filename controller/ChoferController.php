<?php


class ChoferController
{
    private $usuarioModel;
    private $viajeModel;
    private $render;
    private $vehiculoModel;

    public function __construct($usuarioModel, $vehiculoModel, $viajeModel, $render)
    {
        $this->usuarioModel = $usuarioModel;
        $this->viajeModel = $viajeModel;
        $this->vehiculoModel = $vehiculoModel;
        $this->render = $render;
    }
    public function execute(){
        echo $this->render->render("view/choferView.php");
    }
    public function myTravels(){
        $data["viajes"]=$this->viajeModel->getTravelsFrom($_SESSION["user"]);
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/travelsView.php",$data);
    }
    public function searchMyTravel(){
        echo $this->render->render("view/searchTravelView.php");
    }
    public function decodeQr(){
        if(($_FILES['file']['error'])==0){
            $file=$_FILES["file"]["tmp_name"];
            $data["travel"]=$this->viajeModel->getTravelByQr($this->viajeModel->decodeQrCode($file));
            if($data["travel"] != false){
                echo $this->render->render("view/travelDetailView.php",$data);
            }
            else{
                header("Location: /Chofer/searchMyTravel");
                exit();
            }
        }
        else{
            header("Location: /Chofer/searchMyTravel");
            exit();
        }
    }
    public function decodedQr(){
        $qr=$_POST["decodedQr"];
        $data["travel"]=$this->viajeModel->getTravelByQr($qr);
        if(!$data["travel"]){
            echo $this->render->render("view/travelDetailView.php",$data);
        }
        else{
            header("Location: /Chofer/searchMyTravel");
            exit();
        }
    }
    public function actualizarViaje(){
        $data["codViaje"]=$_POST["codViaje"];
        $data["latitud"]=$_POST["latitud"];
        $data["longitud"]=$_POST["longitud"];
        $data["viaticos"]=$_POST["viaticos"];
        $data["peajes"]=$_POST["peajes"];
        $data["pesajes"]=$_POST["pesajes"];
        $data["consumo"]=$_POST["consumo"];
        $data["extras"]=$_POST["extras"];
        $this->viajeModel->updateTravelPoint($data);
        header("Location: /Chofer");
        exit();
    }
    public function cargaCombustible(){
        $data["codViaje"]=$_POST["codViaje"];
        $data["latitud"]=$_POST["latitud"];
        $data["longitud"]=$_POST["longitud"];
        $data["consumo"]=$_POST["consumo"];
        $data["importe"]=$_POST["importe"];
        $data["cantidad"]=$_POST["cantidad"];
        $this->viajeModel->updatePuntoCombustible($data);
        header("Location: /Chofer");
        exit();
    }
    public function finalizarViaje(){
        $data["codViaje"]=$_POST["codViaje"];
        $data["latitud"]=$_POST["latitud"];
        $data["longitud"]=$_POST["longitud"];
        $data["viaticos"]=$_POST["viaticos"];
        $data["peajes"]=$_POST["peajes"];
        $data["pesajes"]=$_POST["pesajes"];
        $data["consumo"]=$_POST["consumo"];
        $data["extras"]=$_POST["extras"];
        $final["factura"]=$this->viajeModel->endTravel($data);
        echo $this->render->render("view/choferView.php",$final);
    }
    public function factura(){
        $id = $_GET["id"];
        $data=$this->viajeModel->getDatosFactura($id);
        $this->render->renderPdf("view/pdfTemplates/facturaView.mustache", $data);
    }
}