<?php


class VehiculoModel
{
    private $database;

    public function __construct($database){
        $this->database=$database;
    }
    public function getVehiculos(){
        $sql="SELECT * FROM Vehiculo";
        return $this->database->query($sql);
    }
    public function getTractoresDisponibles(){
        $sql="SELECT * FROM Vehiculo WHERE tipo='tractor'
                AND estado=1";
        return $this->database->query($sql);
    }
    public function getArrastradosDisponibles(){
        $sql="SELECT * FROM Vehiculo WHERE tipo='arrastrado'
                AND estado=1";
        return $this->database->query($sql);
    }
    public function setVehicle($data){
        $data["type"]=$this->setType($data["tipo"]);
        $sql="INSERT INTO Vehiculo (patente,nroChasis,marca,modelo,
            tipo,kmTotales,anoFabricacion,fechaService, estado) VALUES
            ('".$data["patente"]."',".$data["nroChasis"].",'".$data["marca"]."','".$data["modelo"]."',
            '".$data["type"]."',".$data["kmTotales"].",".$data["anoFabricacion"].",'".$data["fechaService"]."', true)";
        $this->database->execute($sql);
        $this->setVehicleType($data);
    }
    public function setVehicleType($data){
        if($data["tipo"]==1){
            $sql="INSERT INTO Arrastrado(patArrastrado, tipoCarga)
              VALUES ('".$data["patente"]."','".$data["tipoCarga"]."')";
        }
        else{
            $sql="INSERT INTO Tractor(patTractor, nroMotor, consumo)
              VALUES ('".$data["patente"]."',".$data["nroMotor"].",".$data["consumo"].")";
        }
        $this->database->execute($sql);
    }
    public function setType($type){
        return $type == 1 ? "arrastrado" : "tractor";
    }
    public function deleteVehicle($patente){
        $sql="DELETE FROM Vehiculo WHERE patente='".$patente."'";
        $this->database->execute($sql);
    }
    public function needServiceList($data){
        for ($i=0; $i<sizeof($data);$i++){
            $data[$i]["necesitaService"]=$this->needService($data[$i]);
        }
        return $data;
    }
    public function needService($vehiculo){
        $fecha = new DateTime($vehiculo["fechaService"]);
        $fechaHoy = new DateTime();
        return $fechaHoy>$fecha ? true : false;
    }
    public function setService($data){
        $fecha=date("Y-m-d");
        $sql="INSERT INTO Service(fechaInicio, intext, mecanico, vehiculo)
            VALUES ('".$fecha."',".$data["intext"].",".$data["mecanico"].",'".$data["vehiculo"]."')";
        $this->database->execute($sql);
    }
    public function getServicesByMecanico($id){
        $sql="SELECT * FROM Service WHERE mecanico=".$id;
        return $this->database->query($sql);
    }
    public function getServiceById($id){
        $sql="SELECT * FROM Service WHERE codigo=".$id;
        return $this->database->query($sql)[0];
    }
    public function endService($data){
        $fechaHoy=date("Y-m-d");
        $fechaNuevoService=date("Y-m-d",strtotime($fechaHoy."+ 1 year"));
        $sql="UPDATE Service SET
            costo=".$data["costo"].", observaciones='".$data["observaciones"]."', 
            fechaFin='".$fechaHoy."' WHERE codigo=".$data["codigo"];
        $this->database->execute($sql);
        $this->setFechaService($data["vehiculo"], $fechaNuevoService);
    }
    public function getVehiculosDisponibles(){
        $sql="SELECT * FROM Vehiculo WHERE estado=1";
        return $this->database->query($sql);
    }
    public function setDisponibilidad($patente){
        $vehiculo=$this->getVehicleById($patente);
        $estadoActual=$vehiculo["estado"];
        $nuevoEstado=$this->parseBooleanToBin(!($estadoActual));
        $sqlCambio="UPDATE Vehiculo SET estado=".$nuevoEstado." WHERE patente='".$patente."'";
        $this->database->execute($sqlCambio);
    }
    public function getVehicleById($patente){
        $sql="SELECT * FROM Vehiculo WHERE patente='".$patente."'";
        return $this->database->query($sql)[0];
    }
    public function setFechaService($patente, $fecha){
        $sql="UPDATE Vehiculo SET fechaService='".$fecha."'
            WHERE patente='".$patente."'";
        $this->database->execute($sql);
    }
    public function parseBooleanToBin($status){
        return $status ? 1 : 0;
    }
    public function getTractorById($patente){
        $sql="SELECT * FROM Vehiculo 
        JOIN Tractor
        ON Vehiculo.patente=Tractor.patTractor
        WHERE patente='".$patente."'";
        return $this->database->query($sql)[0];
    }
    public function getArrastradoById($patente){
        $sql="SELECT * FROM Vehiculo 
        JOIN Arrastrado
        ON Vehiculo.patente=Arrastrado.patArrastrado
        WHERE patente='".$patente."'";
        return $this->database->query($sql)[0];
    }
    public function getLastLocalizationFrom($patente){
        $sql="SELECT Ubicacion.latitud, Ubicacion.longitud, Ubicacion.fecha,Vehiculo.patente FROM Vehiculo
            JOIN Viaje 
            ON Vehiculo.patente = Viaje.tractor 
            JOIN Ubicacion 
            ON Viaje.codViaje = Ubicacion.viaje 
            WHERE Vehiculo.patente = '".$patente."'
            AND Ubicacion.fecha = (SELECT MAX(fecha) FROM Ubicacion as ubi 
                                    JOIN Viaje as travel
                                    ON ubi.viaje = travel.codViaje
                                    JOIN Vehiculo as vehicle
                                    ON travel.tractor=vehicle.patente
                                    WHERE vehicle.patente = Vehiculo.patente)";
        return $this->database->query($sql);
    }
    public function editArrastrado($data){
        $sql="UPDATE Arrastrado SET
        tipoCarga='".$data["tipoCarga"]."' 
        WHERE patArrastrado like '".$data["patente"]."'";
        $this->database->execute($sql);
    }
    public function editTractor($data){
        $sql="UPDATE Tractor SET
        nroMotor=".$data["nroMotor"].", 
        consumo=".$data["consumo"]." 
        WHERE patTractor like '".$data["patente"]."'";
        $this->database->execute($sql);
    }
    public function editVehicle($data){
        $sql="UPDATE Vehiculo SET 
        nroChasis=".$data["nroChasis"].",
        marca='".$data["marca"]."',
        modelo='".$data["modelo"]."',
        kmTotales=".$data["kmTotales"].",
        anoFabricacion=".$data["anoFabricacion"]."
        WHERE patente like '".$data["patente"]."'";
        $this->database->execute($sql);
    }
}