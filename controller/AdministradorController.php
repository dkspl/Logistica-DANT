<?php


class AdministradorController
{
    private $administradorModel;
    private $usuarioModel;
    private $vehiculoModel;
    private $render;

    public function __construct($administradorModel, $usuarioModel, $vehiculoModel, $render)
    {
        $this->administradorModel = $administradorModel;
        $this->usuarioModel = $usuarioModel;
        $this->vehiculoModel = $vehiculoModel;
        $this->render = $render;
    }

    public function execute(){
        $data["notificaciones"]=$this->administradorModel->getNotifications();
        $data["buzon"]=$this->administradorModel->areThereNotifications($data["notificaciones"]);
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/administradorView.php",$data);
    }
    public function usersAdmin(){
        $data["usuarios"]=$this->administradorModel->needRoleList($this->usuarioModel->getUsuarios());
        echo $this->render->render("view/rolesUsuariosView.php",$data);
    }
    public function editRol(){
        $dni=$_POST["dni"];
        $this->administradorModel->editRol($dni);
        header("Location: /Administrador/roles");
        exit();
    }
    public function deleteUser(){
        $dni=$_POST["dni"];
        $this->administradorModel->deleteUser($dni);
        if($dni == $_SESSION["user"]){
            $this->usuarioModel->logout();
            header("Location: /");
            exit();
        }
        header("Location: /Administrador/roles");
        exit();
    }
    public function vehiculos(){
        $data["vehiculos"]=$this->vehiculoModel->getVehiculos();
        $data["permissions"]=$this->usuarioModel->validatePermissions($_SESSION["user"]);
        $data["admin"]=$this->usuarioModel->validateAdminPermissions($_SESSION["user"]);
        echo $this->render->render("view/vehiculosView.php",$data);
    }
    public function addFormVehicle(){
        //$data["session"]=$this->usuarioModel->isSessionStarted();
        echo $this->render->render("view/addFormVehicleView.php");
    }
    public function setVehicle(){
        $data["patente"]=$_POST["patente"];
        $data["nroChasis"]=$_POST["nroChasis"];
        $data["marca"]=$_POST["marca"];
        $data["modelo"]=$_POST["modelo"];
        $data["tipo"]=$_POST["tipo"];
        $data["kmTotales"]=$_POST["kmTotales"];
        $data["anoFabricacion"]=$_POST["anoFabricacion"];
        $data["fechaService"]=$_POST["fechaService"];
        if(isset($_POST["tipoCarga"])){
            $data["tipoCarga"]=$_POST["tipoCarga"];
        }
        if(isset($_POST["nroMotor"]) && isset($_POST["consumo"])){
            $data["nroMotor"]=$_POST["nroMotor"];
            $data["consumo"]=$_POST["consumo"];
        }
        $this->vehiculoModel->setVehicle($data);
        header("Location: /Administrador/vehiculos");
        exit();
    }
    public function eliminarVehiculo(){
        $patente=$_POST["patente"];
        $this->vehiculoModel->deleteVehicle($patente);
        header("Location: /Administrador/vehiculos");
        exit();
    }
    public function modificarVehiculo(){
        $id=$_GET["id"];
        $data["vehiculo"]=$this->vehiculoModel->getVehicleById($id);
        if(strcmp($data["vehiculo"]["tipo"],'tractor') == 0) {
            $data["tipoVehiculo"]=$this->vehiculoModel->getTractorById($id);
            $data["tractorArrastrado"]=1;
        }
        else{
            $data["tractorArrastrado"]=0;
            $data["tipoVehiculo"]=$this->vehiculoModel->getArrastradoById($id);
        }
        echo $this->render->render("view/editVehicleView.php",$data);
    }
    public function editVehicle(){
        $data["patente"]=$_POST["patente"];
        $data["nroChasis"]=$_POST["nroChasis"];
        $data["marca"]=$_POST["marca"];
        $data["modelo"]=$_POST["modelo"];
        $data["kmTotales"]=$_POST["kmTotales"];
        $data["anoFabricacion"]=$_POST["anoFabricacion"];
        $this->vehiculoModel->editVehicle($data);
        header("Location: /Administrador/modificarVehiculo/id=".$data["patente"]);
        exit();
    }
    public function editSpecificVehicle(){
        $data["patente"]=$_POST["patente"];
        $data["tipo"]=$_POST["tipo"];
        if(strcmp($data["tipo"],'arrastrado')==0){
            $data["tipoCarga"]=$_POST["tipoCarga"];
            $this->vehiculoModel->editArrastrado($data);
        }
        elseif (strcmp($data["tipo"],'tractor')==0){
            $data["nroMotor"]=$_POST["nroMotor"];
            $data["consumo"]=$_POST["consumo"];
            $this->vehiculoModel->editTractor($data);
        }
        header("Location: /Administrador/modificarVehiculo/id=".$data["patente"]);
        exit();
    }
    public function editarEmpleado(){
        $id=$_GET["id"];
        $data["user"]=$this->usuarioModel->getUserByDni($id);
        echo $this->render->render("view/editUserView.php",$data);
    }
    public function editEmployee(){
        $data["dni"] = $_POST["dni"];
        $data["nombre"]= $_POST["nombre"];
        $data["apellido"]= $_POST["apellido"];
        $data["email"] = $_POST["email"];
        $data["fnac"]= $_POST["fnac"];
        $this->usuarioModel->editUserByAdmin($data);
        header("Location: /Administrador/editarEmpleado/id=".$data["dni"]);
        exit();
    }
}