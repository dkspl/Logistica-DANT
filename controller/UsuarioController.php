<?php


class UsuarioController
{
    private $usuarioModel;
    private $vehiculoModel;
    private $render;

    public function __construct($usuarioModel,$vehiculoModel, $render)
    {
        $this->usuarioModel = $usuarioModel;
        $this->vehiculoModel = $vehiculoModel;
        $this->render = $render;
    }

    public function register()
    {
        $data["dni"] = $_POST["dni"];
        $data["nombre"] = $_POST["nombre"];
        $data["apellido"] = $_POST["apellido"];
        $data["email"] = $_POST["email"];
        $data["pass"] = md5($_POST["pass"]);
        $data["fnac"] = $_POST["fnac"];
        $data["rol"] = $_POST["rol"];
        $this->usuarioModel->register($data);
        switch($data["rol"]){
            case 1:
            case 2: $data["noRol"]=true;
                    break;
            case 3: $data["mecanico"]=true;
                    break;
            case 4: $data["chofer"]=true;
                    break;
        }
        echo $this->render->render("view/inicioView.php",$data);
    }
    public function completeSignin(){
        $data["dni"] = $_POST["dni"];
        $data["rol"] = $_POST["rol"];

        if($data["rol"]==3){
            $data["matricula"]=$_POST["matricula"];
        }
        else {
            $data["tipoLicencia"] = $_POST["tipoLicencia"];
        }
        $this->usuarioModel->completeSignin($data);
        $data["noRol"]=true;
        echo $this->render->render("view/inicioView.php",$data);
    }
    public function login(){
        $data["dni"] = $_POST["dni"];
        $data["pass"] = md5($_POST["pass"]);
        $acceso = $this->usuarioModel->login($data);
        if($acceso){
            header("Location: /");
            exit();
        }
        else{
            $data["errorLogin"] = true;
            echo $this->render->render("view/inicioView.php", $data);
        }
    }

    private function getViewByRol()
    {
        return $this->usuarioModel->isSessionStarted() ? $this->usuarioModel->getRol($_SESSION["user"]) : "inicio";
    }
    public function execute(){
        echo $this->render->render("view/".$this->getViewByRol()."View.php");
    }
    public function logout(){
        $this->usuarioModel->logout();
        header("Location: /");
        exit();
    }
    public function profile(){
        if($this->usuarioModel->isSessionStarted()){
            $data["usuario"]=$this->usuarioModel->getUserByDni($_SESSION["user"]);
            //$data["session"]=$this->usuarioModel->isSessionStarted();
            echo $this->render->render("view/profileView.php",$data);
        }
        else{
            header("Location: /");
            exit();
        }
    }
    public function editUser(){
        if($this->usuarioModel->isSessionStarted()) {
            $data["dni"] = $_POST["dni"];
            $data["email"] = $_POST["email"];
            $data["fnac"]= $_POST["fnac"];
            $this->usuarioModel->editUser($data);
            header("Location: /usuario/profile");
            exit();
        }
        header("Location: /");
        exit();
    }
    public function editPassword(){
        if($this->usuarioModel->isSessionStarted()) {
            $data["dni"] = $_SESSION["user"];
            $data["actual"] = md5($_POST["actual"]);
            $data["nueva"] = md5($_POST["nueva"]);
            $data["confirmar"] = md5($_POST["confirmar"]);
            $this->usuarioModel->editPassword($data);
            header("Location: /usuario/profile");
            exit();
        }
        else{
            header("Location: /");
            exit();
        }
    }
    public function vehicle(){
        if($this->usuarioModel->isSessionStarted()) {
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
            $data["ubicacion"]=$this->vehiculoModel->getLastLocalizationFrom($id);
            echo $this->render->render("view/vehicleDetailView.php",$data);
        }
        else{
            header("Location: /");
            exit();
        }
    }
}