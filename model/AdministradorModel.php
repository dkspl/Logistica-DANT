<?php


class AdministradorModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }
    public function editRol($dni){
        $sql="UPDATE Empleado SET estado=1 WHERE dni=".$dni;
        $this->database->execute($sql);
    }
    public function deleteUser($dni){
        $fecha = date("Y-m-d");
        $sql="UPDATE Empleado SET fechaBaja = '".$fecha."', estado=0 
        WHERE Empleado.dni = ".$dni;
        //die(var_dump($sql));
        $this->database->execute($sql);
    }
    public function getNotifications(){
        $data=array();
        $sql="SELECT * FROM Empleado WHERE rol IS NULL";
        $data=$this->database->query($sql);
        if(!empty($data)){
            $data["notificacion"]=("Hay usuarios sin rol");
        }
        return $data;
    }
    public function areThereNotifications($data){
        return empty($data);
    }
    public function needRoleList($data){
        for ($i=0; $i<sizeof($data);$i++){
            $data[$i]["validarRol"]=$this->needRole($data[$i]);
        }
        return $data;
    }
    public function needRole($data){
        return $data["estado"]==0 ? true : false;
    }
    public function getEmpleadosByRol($rol){
        $sql="SELECT * FROM Empleado WHERE rol='".$rol."' AND fechaBaja IS NULL AND estado=1";
        return $this->database->query($sql);
    }
    public function getMecanicos(){
        $sql="SELECT * FROM Empleado 
            JOIN Mecanico
            ON Empleado.dni=Mecanico.dniMec
            WHERE Empleado.fechaBaja IS NULL AND Empleado.estado=1";
        return $this->database->query($sql);
    }
    public function getChoferes(){
        $sql="SELECT * FROM Empleado 
            JOIN Chofer
            ON Empleado.dni=Chofer.dniChof
            WHERE Empleado.fechaBaja IS NULL AND Empleado.estado=1";
        return $this->database->query($sql);
    }
    public function getCantidadChoferesPorDisponibilidad(){
        $disponibles=0;
        $noDisponibles=0;
        $data=$this->getChoferes();
        for($i=0;$i<sizeof($data);$i++){
            $data[$i]["disponibilidad"]==1 ? $disponibles++ : $noDisponibles++;
        }
        return array("disponibles"=>$disponibles, "noDisponibles"=>$noDisponibles);
    }

}