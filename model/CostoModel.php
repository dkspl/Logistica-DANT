<?php


class CostoModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }
    public function setRelativeCost($viaje,$data){
        $costo=$this->calculateRelativeCost($viaje,$data);
        $sqlCostoRelativo="INSERT INTO Costeo (viaje, tipo, km, consumo,hazard, reefer) VALUES
            (".$viaje["codViaje"].",'relativo',".$costo["km"].",".$costo["consumo"].",".$costo["hazard"].",".$costo["reefer"].")";
        $sqlCostoReal="INSERT INTO Costeo (viaje, tipo, hazard, reefer) VALUES
        (".$viaje["codViaje"].", 'real', ".$costo["hazard"].",".$costo["reefer"].")";
        $this->database->execute($sqlCostoRelativo);
        $this->database->execute($sqlCostoReal);
    }
    public function calculateRelativeCost($viaje,$data){
        $costo=array();
        $data["hazard"] == 1 ? $costo["hazard"] = 1000 : $costo["hazard"] = 0;
        $data["reefer"] == 1 ? $costo["reefer"] = $this->calculateReefer($data["temperatura"]) : $costo["reefer"] = 0;
        $costo["km"] = $this->calculateCostoKmRecorridos($viaje["kmEstimado"]);
        $costo["consumo"]=$this->calculateCostoConsumo($viaje["consumoEstimado"]);
        return $costo;
    }
    public function calculateReefer($temperatura){
        return abs($temperatura) * 3;
    }
    public function calculateCostoKmRecorridos($km){
        return $km * 5;
    }
    public function calculateCostoConsumo($consumo){
        return $consumo * 30;
    }

    public function setRealCost($viaje){
        $data=$this->calculateRealCost($viaje);
        $sql="UPDATE Costeo SET km=".$data["precioKm"].",
        viaticos=".$data["precioViaticos"].",
        peajes=".$data["precioPeajes"].",
        pesajes=".$data["precioPesajes"].",
        extras=".$data["precioExtras"].",
        consumo=".$data["precioConsumo"].",
        desvio=".$data["precioDesvio"]." 
        WHERE viaje=".$viaje["codViaje"]." AND tipo like 'real'";

        $this->database->execute($sql);
    }
    public function calculateRealCost($viaje){
        $data["precioKm"]=$this->calculateCostoKmRecorridos($viaje["kmTotales"]);
        $data["precioConsumo"]=$this->calculateCostoConsumo($viaje["consumoTotal"]);
        $data["precioViaticos"]=$this->getSumaFrom($viaje["codViaje"], "viaticos")[0]["sumaviaticos"];
        $data["precioPeajes"]=$this->getSumaFrom($viaje["codViaje"], "peajes")[0]["sumapeajes"];
        $data["precioPesajes"]=$this->getSumaFrom($viaje["codViaje"], "pesajes")[0]["sumapesajes"];
        $data["precioExtras"]=$this->getSumaFrom($viaje["codViaje"], "extras")[0]["sumaextras"]+
            $this->calculateExtrasPorConsumoExtra($viaje["codViaje"],$data["precioConsumo"]);
        $data["precioDesvio"]=$this->calculateCostoDesvio($viaje["desvio"]);
        return $data;
    }
    public function getSumaFrom($viaje, $campo){
        $sql = "SELECT SUM(".$campo.") as suma".$campo." FROM Ubicacion WHERE viaje=".$viaje;
        return $this->database->query($sql);
    }
    public function calculateExtrasPorConsumoExtra($idViaje, $consumo){
        $importeTotal = $this->getImporteCargas($idViaje);
        $diferencia = $importeTotal - $consumo;
        return $diferencia > 0 ? $diferencia * 40 : 0;
    }
    public function calculateCostoDesvio($desvio){
        return $desvio * 10;
    }
    public function getImporteCargas($idViaje){
        $sql = "SELECT SUM(importe) as sumaImporte FROM Combustible WHERE numViaje=".$idViaje;
        $data=$this->database->query($sql);
        if(is_null($data[0]["sumaImporte"])){
            return 0;
        }
        return $data[0]["sumaImporte"];
    }
    public function getRealCost($viaje){
        $sql="SELECT * FROM Costeo WHERE viaje=".$viaje." AND tipo like 'real'";
        return $this->database->query($sql);
    }
    public function getCostByTypeAndTravel($viaje, $tipo){
        $sql="SELECT * FROM Costeo WHERE viaje=".$viaje." 
        AND tipo like '".$tipo."'";
        return $this->database->query($sql);
    }
    public function getCostoTotalPorTipoYViaje($viaje, $tipo){
        $sql="SELECT km+viaticos+peajes+pesajes+extras+hazard+reefer+consumo+desvio as Total
        FROM Costeo WHERE viaje=".$viaje." AND tipo like '".$tipo."'";
        return $this->database->query($sql);
    }

}