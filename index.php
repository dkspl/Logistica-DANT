<?php
include_once("helper/Configuration.php");

session_start();
$configuration = new Configuration();

$urlHelper = $configuration->getUrlHelper();
$module = $urlHelper->getModuleFromRequestOr();
$action = $urlHelper->getActionFromRequestOr();


$router = $configuration->getRouter();
$router->executeActionFromModule($action, $module);
