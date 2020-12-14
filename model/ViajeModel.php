<?php


class ViajeModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    /*Viaje*/
    public function getTravels(){
        return $this->database->query("SELECT * FROM Viaje");
    }

    public function setTravel($data){
        $this->setCliente($data);
        $data["kmEstimado"]=$this->calculateDistance($data["latOrigen"],$data["longOrigen"],
            $data["latDestino"], $data["longDestino"]);
        $data["consumoEstimado"]=$this->calculateConsumoPorKm($data["vehiculo"], $data["kmEstimado"]);
        $sqlViaje="INSERT INTO Viaje (origen,latOrigen, longOrigen,
        destino, latDestino, longDestino,kmEstimado, consumoEstimado, eta,etd,chofer,tractor,cliente,arrastrado) 
        VALUES ('".$data["origen"]."',".$data["latOrigen"].",".$data["longOrigen"].",'".$data["destino"]."',
        ".$data["latDestino"].",".$data["longDestino"].",".$data["kmEstimado"].",".$data["consumoEstimado"].",
        '".$data["eta"]."','".$data["etd"]."',".$data["chofer"].",".$data["tractor"].",
        ".$data["cuit"].",".$data["arrastrado"].")";
        $idViaje=$this->database->executeId($sqlViaje);
        if($idViaje!=0){
            $this->createQrCode($idViaje);
            $this->setCarga($idViaje, $data);
            $this->setRelativeCost($idViaje, $data);
            return $idViaje;
        }
        return false;
    }

    public function isModifiableList($data){
        for ($i=0; $i<sizeof($data);$i++){
            $data[$i]["isModifiable"]=$this->isModifiable($data[$i]);
        }
        return $data;
    }

    public function isModifiable($viaje){
        return (strcmp($viaje["estado"],'cancelado')!=0) && (strcmp($viaje["estado"],'finalizado')!=0);
    }

    public function setCliente($data)
    {
        $sql="INSERT INTO Cliente (cuit,denominacion,direccion,telefono,
        email,contacto1,contacto2) VALUES (".$data["cuit"].",
        '".$data["denominacion"]."',
        '".$data["direccion"]."',
        ".$data["telefono"].",
        '".$data["email"]."',
        ".$data["contacto1"].",
        ".$data["contacto2"].")";
        $this->database->execute($sql);
    }

    public function createQrCode($codViaje){
        $size=10;
        $level='L';
        $frameSize=3;
        $url="tmp/".$codViaje.".png";
        QRcode::png($codViaje, $url,$level,$size, $frameSize);
    }

    public function getTravel($codigo){
        $sql="SELECT * FROM Viaje WHERE codViaje=".$codigo;
        return $this->database->query($sql);
    }

    public function canBeCanceled($data){
        if ((strcmp($data["estado"], "en carga") == 0)
            || (strcmp($data["estado"], "en curso") == 0)) {
            return true;
        } else {
            return false;
        }
    }

    public function cancelTravel($data){
        $dato=$this->getTravel($data["codViaje"])[0];
        if($this->canBeCanceled($dato)) {
            $data["estado"]="cancelado";
            $this->updateTravelStatus($data);
            return true;
        }
        return false;
    }

    public function canBeUpdated($data){
        if ((strcmp($data["estado"], "finalizado") != 0)
            && (strcmp($data["estado"], "cancelado") != 0)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateTravelStatus($data){
        $dato=$this->getTravel($data["codViaje"])[0];
        if($this->canBeUpdated($dato)){
            $sql="UPDATE Viaje SET estado='".$data["estado"]."' WHERE codViaje=".$data["codViaje"];
            $this->database->execute($sql);
        }
    }

    public function decodeQrCode($file){
        $qrCode=new \Zxing\QrReader($file);
        return $qrCode->text();
    }

    public function validateChofer($chofer){
        $sessionUser=$_SESSION["user"];
        return strcmp($chofer, $sessionUser)==0;
    }

    public function getTravelByQr($variable){
        if(isset($variable) && $variable != false){
            $result=$this->getTravel($variable);
            if(!is_null($result) && $this->validateChofer($result[0]["chofer"]) && $this->isModifiable($result[0])){
                return $result;
            }
        }
        return false;
    }

    public function updateTravelPoint($data){
        $date=new DateTime();
        $fecha = $date->format('Y-m-d H:i:s');
        $datosViaje=$this->getTravel($data["codViaje"])[0];
        if(strcmp($datosViaje["estado"],'cancelado')!=0 && is_null($datosViaje["fllegada"])){
            $data["kmRecorridos"]=$this->setKm($data["codViaje"], $data["latitud"], $data["longitud"]);
            $sql="INSERT INTO Ubicacion(viaje, kmRecorridos, fecha, latitud, longitud, 
            viaticos, peajes,pesajes,consumo, extras)
            VALUES (".$data["codViaje"].",
            ".$data["kmRecorridos"].",
            '".$fecha."',".$data["latitud"].",
            ".$data["longitud"].",
            ".$data["viaticos"].",
            ".$data["peajes"].",
            ".$data["pesajes"].",
            ".$data["consumo"].",
            ".$data["extras"].")";
            $this->database->execute($sql);
        }
    }

    public function updatePuntoCombustible($data){
        $datosViaje=$this->getTravel($data["codViaje"])[0];
        if(strcmp($datosViaje["estado"],'cancelado')!=0 && is_null($datosViaje["fllegada"])){
            $sqlCombustible="INSERT INTO Combustible(numViaje,latComb,longComb,cantidad,importe) 
            VALUES (".$data["codViaje"].",
            ".$data["latitud"].",
            ".$data["longitud"].",
            ".$data["cantidad"].",
            ".$data["importe"].")";
            $this->database->execute($sqlCombustible);
            $data["kmRecorridos"]=$this->setKm($data["codViaje"], $data["latitud"], $data["longitud"]);
            $sqlUbicacion="INSERT INTO Ubicacion(latitud,longitud,viaje,kmRecorridos,consumo) 
            VALUES (".$data["latitud"].",
            ".$data["longitud"].",
            ".$data["codViaje"].",
            ".$data["kmRecorridos"].",
            ".$data["consumo"].")";
            $this->database->execute($sqlUbicacion);
        }
    }

    public function endTravel($data){
        $this->updateTravelPoint($data);
        $llegada=date('Y-m-d');
        $kmFinal=$this->getSumaFrom($data["codViaje"], "kmRecorridos")[0]["sumakmRecorridos"];
        $consumoFinal=$this->getSumaFrom($data["codViaje"],"consumo")[0]["sumaconsumo"];
        $desvio=$this->calculateDesvio($data["codViaje"],$kmFinal);
        $sql = "UPDATE Viaje SET
        fllegada = '".$llegada."',
        estado = 'finalizado',
        kmTotales =  ".$kmFinal.", 
        consumoTotal = ".$consumoFinal.",
        desvio = ".$desvio."  
        WHERE codViaje = ".$data["codViaje"]." AND fllegada IS NULL";
        $this->database->execute($sql);
        $this->setRealCost($data["codViaje"]);
        return $data["codViaje"];
    }

    public function getTravelsFrom($chofer){
        return $this->validateChofer($chofer) ? $this->getTravelsByChofer($chofer) : false;
    }

    public function getTravelsByChofer($chofer){
        $sql="SELECT * FROM Viaje WHERE chofer=".$chofer;
        return $this->database->query($sql);
    }

    public function getChoferFrom($viaje){
        $sql="SELECT * FROM Viaje
        INNER JOIN Empleado
        ON Viaje.chofer=Empleado.dni
        WHERE Viaje.codViaje=".$viaje;

        return $this->database->query($sql);
    }

    public function getTractorFrom($viaje){
        $sql="SELECT * FROM Viaje
        INNER JOIN Vehiculo as Tractor
        ON Viaje.tractor=Tractor.codVehiculo
        WHERE Viaje.codViaje=".$viaje;
        return $this->database->query($sql);
    }

    public function getArrastradoFrom($viaje){
        $sql="SELECT * FROM Viaje
        INNER JOIN Vehiculo as Arrastrado
        ON Viaje.arrastrado=Arrastrado.codVehiculo
        WHERE Viaje.codViaje=".$viaje;
        return $this->database->query($sql);
    }

    public function getClienteFrom($viaje){
        $sql="SELECT cuit, denominacion, telefono, email, direccion, contacto1, contacto2
            FROM Cliente INNER JOIN Viaje ON Cliente.cuit=Viaje.cliente WHERE Viaje.codViaje=".$viaje;
        return $this->database->query($sql);
    }
    public function calculateDesvio($codViaje,$kmFinal){
        $datosViaje=$this->getTravel($codViaje)[0];
        $desvio=$kmFinal-$datosViaje["kmEstimado"];
        return $desvio > 0 ? $desvio : 0;
    }

    /*Costo*/
    public function calculateDistance($lat1, $long1, $lat2, $long2){
        $radioTierra = 6371.00;// en kilómetros
        $dLat = deg2rad ($lat2 - $lat1);
        $dLng = deg2rad ($long2 - $long1);
        $sindLat = sin($dLat / 2);
        $sindLng = sin($dLng / 2);
        $va1 = pow($sindLat, 2) + pow($sindLng, 2) * cos(deg2rad($lat1)) * cos(deg2rad($lat2));
        $va2 = 2 * atan2(sqrt($va1), sqrt(1 - $va1));
        return round($radioTierra * $va2, 2);
    }

    public function setKm($viaje, $lat, $long){
        $sql= "SELECT latitud, longitud from Ubicacion WHERE viaje = ".$viaje.
            " AND fecha = (SELECT MAX(fecha) FROM Ubicacion WHERE viaje = ".$viaje.")";
        $data=$this->database->query($sql);
        if(empty($data)){
            $sqlViaje="SELECT latOrigen as latitud,longOrigen as longitud FROM Viaje WHERE codViaje=".$viaje;
            $data=$this->database->query($sqlViaje);
        }
        $result = $this->calculateDistance($data[0]["latitud"], $data[0]["longitud"], $lat, $long);
        return $result;
    }
    public function calculateCostoKmRecorridos($km){
        return $km * 5;
    }

    public function calculateReefer($temperatura){
        return abs($temperatura) * 3;
    }
    public function calculateCostoConsumo($consumo){
        return $consumo * 30;
    }

    public function calculateConsumoPorKm($vehiculo, $km){
        return $km * $vehiculo["consumo"] / 100;
    }

    public function calculateCostoDesvio($desvio){
        return $desvio * 10;
    }
    public function calculateRelativeCost($data){
        $costo=array();
        $data["hazard"] == 1 ? $costo["hazard"] = 1000 : $costo["hazard"] = 0;
        $data["reefer"] == 1 ? $costo["reefer"] = $this->calculateReefer($data["temperatura"]) : $costo["reefer"] = 0;
        $costo["km"] = $this->calculateCostoKmRecorridos($data["kmEstimado"]);
        $costo["consumo"]=$this->calculateCostoConsumo($data["consumoEstimado"]);
        return $costo;
    }
    public function setRelativeCost($viaje,$data){
        $costo=$this->calculateRelativeCost($data);
        $sqlCostoRelativo="INSERT INTO Costeo (viaje, tipo, km, consumo,hazard, reefer) VALUES
            (".$viaje.",'relativo',".$costo["km"].",".$costo["consumo"].",".$costo["hazard"].",".$costo["reefer"].")";
        $sqlCostoReal="INSERT INTO Costeo (viaje, tipo, hazard, reefer) VALUES
        (".$viaje.", 'real', ".$costo["hazard"].",".$costo["reefer"].")";
        $this->database->execute($sqlCostoRelativo);
        $this->database->execute($sqlCostoReal);
    }

    public function calculateRealCost($viaje){
        $datosViaje=$this->getTravel($viaje)[0];
        $data["precioKm"]=$this->calculateCostoKmRecorridos($datosViaje["kmTotales"]);
        $data["precioConsumo"]=$this->calculateCostoConsumo($datosViaje["consumoTotal"]);
        $data["precioViaticos"]=$this->getSumaFrom($viaje, "viaticos")[0]["sumaviaticos"];
        $data["precioPeajes"]=$this->getSumaFrom($viaje, "peajes")[0]["sumapeajes"];
        $data["precioPesajes"]=$this->getSumaFrom($viaje, "pesajes")[0]["sumapesajes"];
        $data["precioExtras"]=$this->getSumaFrom($viaje, "extras")[0]["sumaextras"]+
                        $this->calculateExtrasPorConsumoExtra($datosViaje["codViaje"],$data["precioConsumo"]);
        $data["precioDesvio"]=$this->calculateCostoDesvio($datosViaje["desvio"]);
        return $data;
    }

    public function getSumaFrom($viaje, $campo){
        $sql = "SELECT SUM(".$campo.") as suma".$campo." FROM Ubicacion WHERE viaje=".$viaje;
        return $this->database->query($sql);
    }
    public function getImporteCargas($idViaje){
        $sql = "SELECT SUM(importe) as sumaImporte FROM Combustible WHERE numViaje=".$idViaje;
        $data=$this->database->query($sql);
        if(is_null($data[0]["sumaImporte"])){
            return 0;
        }
        return $data[0]["sumaImporte"];
    }
    public function calculateExtrasPorConsumoExtra($idViaje, $consumo){
        $importeTotal = $this->getImporteCargas($idViaje);
        $diferencia = $importeTotal - $consumo;
        return $diferencia > 0 ? $diferencia * 40 : 0;
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
        WHERE viaje=".$viaje." AND tipo like 'real'";

        $this->database->execute($sql);
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

    /*Carga*/
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

    /* Documentacion */
    public function getDatosProforma($viaje){
        $data["travel"]=$this->getTravel($viaje)[0];
        $data["cliente"]=$this->getClienteFrom($viaje)[0];
        $data["costo"]=$this->getCostByTypeAndTravel($viaje,'relativo')[0];
        $data["carga"]=$this->getCargaByTravel($viaje)[0];
        $data["chofer"]=$this->getChoferFrom($viaje)[0];
        $data["tractor"]=$this->getTractorFrom($viaje)[0];
        $data["arrastrado"]=$this->getArrastradoFrom($viaje)[0];
        $data["importe"]=$this->getCostoTotalPorTipoYViaje($viaje,'relativo');
        return $data;
    }

    public function getDatosFactura($viaje){
        $data["travel"]=$this->getTravel($viaje)[0];
        $data["cliente"]=$this->getClienteFrom($viaje)[0];
        $data["costo"]=$this->getCostByTypeAndTravel($viaje,'real')[0];
        $data["carga"]=$this->getCargaByTravel($viaje)[0];
        $data["importe"]=$this->getCostoTotalPorTipoYViaje($viaje,'real');
        return $data;
    }

    /* En desarrollo */
    public function convertTimeDiffToNumber($time1, $time2){
        $inicio = new DateTime($time1);
        $fin = new DateTime($time2);
        $diff = $inicio->diff($fin);
        return $diff->days;
    }
    public function getKmRecorridosEnViaje(){
        $sql="SELECT Vehiculo.patente as Vehiculo, SUM(Viaje.kmTotales) as TotalViajado
        FROM Viaje
        JOIN Vehiculo
        ON Viaje.tractor=Vehiculo.codVehiculo
        GROUP BY Vehiculo.codVehiculo
        WHERE Viaje.kmTotales IS NOT NULL
        ORDER BY Viaje.kmTotales DESC";

        return $this->database->query($sql);
    }

    public function getLongestTravel(){
        $sql="SELECT codViaje, origen, destino,fllegada, MAX(kmTotales) as Recorrido
        FROM Viaje WHERE kmTotales IS NOT NULL";
        return $this->database->query($sql);
    }
    public function getShortestTravel(){
        $sql="SELECT codViaje, origen, destino, fllegada, MIN(kmTotales) as Recorrido
        FROM Viaje WHERE kmTotales IS NOT NULL";
        return $this->database->query($sql);
    }
    public function getConsumoPromedioEnViajes(){
        $sql="SELECT Vehiculo.patente as Vehiculo, AVG(Viaje.consumoTotal*100/Viaje.kmTotales) as Promedio
        FROM Viaje
        JOIN Vehiculo
        ON Viaje.tractor=Vehiculo.codVehiculo
        GROUP BY Vehiculo.codVehiculo
        WHERE Viaje.kmTotales IS NOT NULL
        ORDER BY Promedio DESC";
        return $this->database->query($sql);
    }
    public function getPromedioDesvios(){
        $sql="SELECT AVG(Viaje.desvio) as promedio
        FROM Viaje 
        WHERE Viaje.desvio IS NOT NULL";
        return $this->database->query($sql);
    }
    public function getTotalRecorrido(){
        $sql="SELECT SUM(kmRecorridos) as Total FROM Ubicacion";
        return $this->database->query($sql);
    }
    public function getTotalConsumo(){
        $sql="SELECT SUM(consumo) as Total FROM Ubicacion";
        return $this->database->query($sql);
    }
}