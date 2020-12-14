<?php


class SupervisorController
{
    private $usuarioModel;
    private $viajeModel;
    private $vehiculoModel;
    private $cargaModel;
    private $costoModel;
    private $render;

    public function __construct($usuarioModel, $viajeModel, $vehiculoModel, $cargaModel, $costoModel, $render)
    {
        $this->usuarioModel = $usuarioModel;
        $this->viajeModel = $viajeModel;
        $this->vehiculoModel = $vehiculoModel;
        $this->cargaModel = $cargaModel;
        $this->costoModel = $costoModel;
        $this->render = $render;
    }
    public function execute(){
        echo $this->render->render("view/supervisorView.php");
    }
    public function startTravelForm(){
        $data["arrastrados"]=$this->vehiculoModel->getArrastradosDisponibles();
        $data["choferes"]=$this->usuarioModel->getChoferesDisponibles();
        $data["tractores"]=$this->vehiculoModel->getTractoresDisponibles();
        $data["clientes"]=$this->viajeModel->getClientes();
        echo $this->render->render("view/startTravelView.php",$data);
    }
    public function travels(){
        $data["viajes"]=$this->viajeModel->isModifiableList($this->viajeModel->getTravels());
        $data["permissions"]=$this->usuarioModel->validatePermissions($_SESSION["user"]);
        echo $this->render->render("view/travelsView.php",$data);
    }
    public function setTravel(){
        $data["cliente"]=$_POST["cliente"];
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
        if($final["viaje"]!=0){
            $this->cargaModel->setCarga($final["viaje"],$data);
            $this->costoModel->setRelativeCost($this->viajeModel->getTravel($final["viaje"])[0],$data);
        }
        $final["viajes"]=$this->viajeModel->isModifiableList($this->viajeModel->getTravels());
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
        $data["travel"]=$this->viajeModel->getTravel($id)[0];
        $data["cliente"]=$this->viajeModel->getClienteFrom($id)[0];
        $data["chofer"]=$this->viajeModel->getChoferFrom($id)[0];
        $data["tractor"]=$this->viajeModel->getTractorFrom($id)[0];
        $data["arrastrado"]=$this->viajeModel->getArrastradoFrom($id)[0];
        $data["costo"]=$this->costoModel->getCostByTypeAndTravel($id,'relativo')[0];
        $data["importe"]=$this->costoModel->getCostoTotalPorTipoYViaje($id,'relativo');
        $data["carga"]=$this->cargaModel->getCargaByTravel($id)[0];
        $this->render->renderPdf("view/pdfTemplates/proformaView.mustache", $data);
    }
    public function factura(){
        $id=$_GET["id"];
        $data["travel"]=$this->viajeModel->getTravel($id)[0];
        $data["cliente"]=$this->viajeModel->getClienteFrom($id)[0];
        $data["costo"]=$this->costoModel->getCostByTypeAndTravel($id,'real')[0];
        $data["importe"]=$this->costoModel->getCostoTotalPorTipoYViaje($id,'real');
        $data["carga"]=$this->cargaModel->getCargaByTravel($id)[0];
        $this->render->renderPdf("view/pdfTemplates/facturaView.mustache", $data);
    }
    public function getImoSubclass(){
        $id=$_POST["id"];
        $result = $this->cargaModel->getImoSubclassByClass($id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function getImoClasses(){
        $result = $this->cargaModel->getImoClasses();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}