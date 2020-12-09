<?php

class UrlHelper
{
    public function getModuleFromRequestOr(){
        return isset($_GET["module"]) ? $_GET["module"] : "usuario";
    }

    public function getActionFromRequestOr(){
        return isset($_GET["action"]) ? $_GET["action"] : "execute";
    }
    /*public function getDefaultModule(){
        return isset($_SESSION["user"]) ? "usuario" : "index";
    }
    public function getDefaultAction(){
        return isset($_SESSION["user"]) ? "interno" : "execute";
    }*/
}