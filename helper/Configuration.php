<?php
include_once("helper/MysqlDatabase.php");
include_once("helper/Render.php");
include_once("helper/UrlHelper.php");

include_once ("model/UsuarioModel.php");
include_once ("model/AdministradorModel.php");
include_once ("model/ViajeModel.php");
include_once ("model/VehiculoModel.php");
include_once ("model/ReportesModel.php");

include_once ("controller/UsuarioController.php");
include_once ("controller/AdministradorController.php");
include_once ("controller/SupervisorController.php");
include_once ("controller/MecanicoController.php");
include_once ("controller/ChoferController.php");

include_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once("Router.php");
include_once('third-party/phpqrcode/qrlib.php');
include_once('third-party/libchart/libchart/classes/libchart.php');
include_once('vendor/autoload.php');

class Configuration{
    private function getDatabase(){
        $config = $this->getConfig();
        return new MysqlDatabase(
            $config["servername"],
            $config["username"],
            $config["password"],
            $config["dbname"]
        );
    }

    private function getConfig(){
        return parse_ini_file("config/config.ini");
    }

    public function getRender(){
        return new Render('view/partial');
    }

    public function getRouter(){
        return new Router($this, $this->getUsuarioModel());
    }

    public function getUrlHelper(){
        return new UrlHelper();
    }

    public function getUsuarioController(){
        $usuarioModel = $this->getUsuarioModel();
        $vehiculoModel = $this->getVehiculoModel();
        return new UsuarioController($usuarioModel,$vehiculoModel, $this->getRender());
    }
    public function getUsuarioModel(){
        $database = $this->getDatabase();
        return new UsuarioModel($database);
    }

    public function getAdministradorController(){
        $usuarioModel = $this->getUsuarioModel();
        $administradorModel = $this->getAdministradorModel();
        $vehiculoModel = $this->getVehiculoModel();
        $viajeModel = $this->getViajeModel();
        $reportesModel = $this->getReportesModel();
        return new AdministradorController($administradorModel, $usuarioModel, $vehiculoModel,$viajeModel,$reportesModel, $this->getRender());
    }
    public function getAdministradorModel(){
        $database = $this->getDatabase();
        return new AdministradorModel($database);
    }
    public function getSupervisorController(){
        $usuarioModel = $this->getUsuarioModel();
        $viajeModel = $this->getViajeModel();
        $vehiculoModel = $this->getVehiculoModel();
        return new SupervisorController($usuarioModel, $viajeModel, $vehiculoModel, $this->getRender());
    }
    public function getViajeModel(){
        $database = $this->getDatabase();
        return new ViajeModel($database);
    }
    public function getVehiculoModel(){
        $database = $this->getDatabase();
        return new VehiculoModel($database);
    }
    public function getReportesModel(){
        $database = $this->getDatabase();
        return new ReportesModel($database);
    }
    public function getMecanicoController(){
        $viajeModel = $this->getViajeModel();
        $vehiculoModel = $this->getVehiculoModel();
        $usuarioModel = $this->getUsuarioModel();
        return new MecanicoController($vehiculoModel, $viajeModel, $usuarioModel, $this->getRender());
    }
    public function getChoferController(){
        $usuarioModel= $this->getUsuarioModel();
        $viajeModel= $this->getViajeModel();
        return new ChoferController($usuarioModel, $viajeModel, $this->getRender());
    }
}