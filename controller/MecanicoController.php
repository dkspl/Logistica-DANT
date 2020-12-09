<?php


class MecanicoController
{
    private $vehiculoModel;
    private $viajeModel;
    private $usuarioModel;

    public function __construct($vehiculoModel, $viajeModel, $usuarioModel, $render)
    {
        $this->vehiculoModel = $vehiculoModel;
        $this->viajeModel = $viajeModel;
        $this->usuarioModel = $usuarioModel;
        $this->render = $render;
    }
    public function startService(){
        $data["vehiculos"]=$this->vehiculoModel->needServiceList($this->vehiculoModel->getVehiculosDisponibles());
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/startServiceView.php",$data);
    }
    public function setService(){
        $data["mecanico"]=$_SESSION["user"];
        $data["vehiculo"]=$_POST["vehiculo"];
        $data["intext"]=$_POST["intext"];
        $this->vehiculoModel->setService($data);
        header("Location: /Mecanico/myServices");
        exit();
    }
    public function myServices(){
        $data["services"]=$this->vehiculoModel->getServicesByMecanico($_SESSION["user"]);
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/servicesView.php",$data);
    }
    public function endingService(){
        $service=$_GET["codigo"];
        $data["service"]=$this->vehiculoModel->getServiceById($service);
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/endServiceView.php",$data);
    }
    public function endService(){
        $data["codigo"]=$_POST["codigo"];
        $data["vehiculo"]=$_POST["vehiculo"];
        $data["costo"]=$_POST["costo"];
        $data["observaciones"]=$_POST["observaciones"];
        $this->vehiculoModel->endService($data);
        header("Location: /Mecanico/myServices");
        exit();
    }
    public function service(){
        $codigo=$_GET["codigo"];
        $data["service"]=$this->vehiculoModel->getServiceById($codigo);
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/serviceDetailView.php",$data);
    }
    public function vehiculos(){
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        $data["vehiculos"]=$this->vehiculoModel->needServiceList($this->vehiculoModel->getVehiculos());
        $data["permissions"]=$this->usuarioModel->validatePermissions($_SESSION["user"]);
        echo $this->render->render("view/vehiculosView.php",$data);
    }
}