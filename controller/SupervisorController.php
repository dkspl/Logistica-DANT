<?php


class SupervisorController
{
    private $usuarioModel;
    private $viajeModel;
    private $render;
    private $vehiculoModel;

    public function __construct($usuarioModel, $viajeModel, $vehiculoModel, $render)
    {
        $this->usuarioModel = $usuarioModel;
        $this->viajeModel = $viajeModel;
        $this->vehiculoModel = $vehiculoModel;
        $this->render = $render;
    }
    public function execute(){
        echo $this->render->render("view/supervisorView.php");
    }
    public function startTravelForm(){
        $data["arrastrados"]=$this->vehiculoModel->getArrastradosDisponibles();
        $data["choferes"]=$this->usuarioModel->getChoferesDisponibles();
        $data["tractores"]=$this->vehiculoModel->getTractoresDisponibles();
        echo $this->render->render("view/travelFormView.php",$data);
    }
    public function travels(){
        $data["viajes"]=$this->viajeModel->isModifiableList($this->viajeModel->getTravels());
        $data["permissions"]=$this->usuarioModel->validatePermissions($_SESSION["user"]);
        echo $this->render->render("view/travelsView.php",$data);
    }
    public function setTravel(){
        $data["cuit"]=$_POST["cuit"];
        $data["denominacion"]=$_POST["denominacion"];
        $data["direccion"]=$_POST["direccion"];
        $data["email"]=$_POST["email"];
        $data["telefono"]=$_POST["telefono"];
        $data["contacto1"]=$_POST["contacto1"];
        $data["contacto2"]=$_POST["contacto2"];

        $data["origen"]=$_POST["textOrigen"];
        $data["latOrigen"]=$_POST["latOrigen"];
        $data["longOrigen"]=$_POST["longOrigen"];
        $data["destino"]=$_POST["textDestino"];
        $data["latDestino"]=$_POST["latDestino"];
        $data["longDestino"]=$_POST["longDestino"];
        $data["eta"]=$_POST["eta"];
        $data["etd"]=$_POST["etd"];
        $data["chofer"]=$_POST["chofer"];
        $data["tractor"]=$_POST["tractor"];

        $data["arrastrado"]=$_POST["arrastrado"];
        $data["pesoNeto"]=$_POST["pesoNeto"];
        $data["hazard"]=$_POST["hazard"];
        $data["reefer"]=$_POST["reefer"];
        $data["vehiculo"]=$this->vehiculoModel->getTractorById($data["tractor"]);

        if(isset($_POST["temperatura"]))
            $data["temperatura"]=$_POST["temperatura"];
        if(isset($_POST["imoClass"]) && isset($_POST["imoSubclass"])){
            $data["imoClass"]=$_POST["imoClass"];
            $data["imoSubclass"]=$_POST["imoSubclass"];
        }

        $final["viaje"]=$this->viajeModel->setTravel($data);
        $final["viajes"]=$this->viajeModel->getTravels();
        $final["permissions"]=$this->usuarioModel->validatePermissions($_SESSION["user"]);
        echo $this->render->render("view/travelsView.php",$final);
    }
    public function updateTravel(){
        $data["codViaje"]=$_POST["codViaje"];
        $data["estado"]=$_POST["estado"];
        $this->viajeModel->updateTravelStatus($data);
        header("Location: /Supervisor/travels");
        exit();
    }
    public function cancelTravel(){
        $data["codViaje"]=$_POST["codViaje"];
        $data["chofer"]=$_POST["chofer"];
        $data["tractor"]=$_POST["tractor"];
        $data["arrastrado"]=$_POST["arrastrado"];
        $this->viajeModel->cancelTravel($data);
        $this->usuarioModel->changeStatus($data["chofer"]);
        $this->vehiculoModel->setDisponibilidad($data["tractor"]);
        $this->vehiculoModel->setDisponibilidad($data["arrastrado"]);
        header("Location: /Supervisor/travels");
        exit();
    }
    public function qr(){
        $this->viajeModel->createQrCode();
        header("Location: /");
        exit();
    }
    public function vehicleDetail(){
        $vehiculo=$_GET["id"];
        $this->vehiculoModel->getVehicleById($vehiculo);
    }
    public function vehiculos(){
        $data["vehiculos"]=$this->vehiculoModel->getVehiculos();
        $data["permissions"]=$this->usuarioModel->validatePermissions($_SESSION["user"]);
        echo $this->render->render("view/vehiculosView.php",$data);
    }
    public function travel(){
        $id=$_GET["id"];
        $data=$this->viajeModel->getDatosProforma($id);
        $this->render->renderPdf("view/pdfTemplates/proformaView.mustache", $data);
    }
    public function factura(){
        $id=$_GET["id"];
        $data=$this->viajeModel->getDatosFactura($id);
        $this->render->renderPdf("view/pdfTemplates/facturaView.mustache", $data);
    }
    public function getImoSubclass(){
        $id=$_POST["id"];
        $result = $this->viajeModel->getImoSubclassByClass($id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}