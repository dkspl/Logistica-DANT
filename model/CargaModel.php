<?php


class CargaModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }
    public function getImoSubclassByClass($id){
        $sql="SELECT * FROM imoSubclass WHERE idClase=".$id;
        return $this->database->query($sql);
    }

    public function getImoClasses(){
        $sql="SELECT * FROM imoSubclass";
        return $this->database->query($sql);
    }

    public function setCarga($viaje,$data){
        $sql="INSERT INTO Carga(viaje,hazard,reefer,pesoNeto)
        VALUES (".$viaje.",".$data["hazard"].",".$data["reefer"].",".$data["pesoNeto"].")";
        $this->database->execute($sql);
        $this->setReeferHazard($data, $viaje);
    }

    public function setReeferHazard($data, $idViaje){
        if($data["reefer"]==1){
            if(isset($data["temperatura"])){
                $sql="UPDATE Carga SET temperatura=".$data["temperatura"]." WHERE viaje=".$idViaje;
                $this->database->execute($sql);
            }
        }
        if($data["hazard"]==1){
            if(isset($data["imoClass"]) && isset($data["imoSubclass"])){
                $sql="UPDATE Carga SET imoClass=".$data["imoClass"].",
                imoSubclass=".$data["imoSubclass"]." 
                WHERE viaje=".$idViaje;
                $this->database->execute($sql);
            }
        }
    }

    public function getCargaByTravel($viaje)
    {
        $sql="SELECT * FROM Viaje
        INNER JOIN Carga
        ON Viaje.codViaje=Carga.viaje
        WHERE Viaje.codViaje=".$viaje;
        return $this->database->query($sql);
    }
}