<?php

class Router{
    private $configuration;
    private $usuarioModel;

    public function __construct($configuration,$usuarioModel)
    {
        $this->configuration = $configuration;
        $this->usuarioModel = $usuarioModel;
    }

    public function executeActionFromModule($action, $module){
        $controller = $this->getControllerFrom($module);
        $this->executeMethodFromController($controller,$action);
    }

    private function getControllerFrom($module){
          $controllerName = "get" . ucfirst($module) . "Controller";
          $validController = method_exists($this->configuration, $controllerName) &&
          $this->validateRole(strtolower($module)) ?$controllerName : "getUsuarioController";
          return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method){
        $validMethod = method_exists($controller, $method) ?$method : "execute";
        call_user_func(array($controller, $validMethod));
    }

    private function validateRole($module){
        $this->usuarioModel->isSessionStarted() ? $sessionRole=$this->usuarioModel->getRol($_SESSION["user"]):$sessionRole="";
        return $sessionRole==$module ? true : false;
    }
}