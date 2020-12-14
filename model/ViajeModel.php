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
        $data["cliente"]=$this->setCliente($data);
        $data["kmEstimado"]=$this->calculateDistance($data["latOrigen"],$data["longOrigen"],
            $data["latDestino"], $data["longDestino"]);
        $data["consumoEstimado"]=$this->calculateConsumoPorKm($data["vehiculo"], $data["kmEstimado"]);
        $sqlViaje="INSERT INTO Viaje (origen,latOrigen, longOrigen,
        destino, latDestino, longDestino,kmEstimado, consumoEstimado, eta,etd,chofer,tractor,cliente,arrastrado) 
        VALUES ('".$data["origen"]."',".$data["latOrigen"].",".$data["longOrigen"].",'".$data["destino"]."',
        ".$data["latDestino"].",".$data["longDestino"].",".$data["kmEstimado"].",".$data["consumoEstimado"].",
        '".$data["eta"]."','".$data["etd"]."',".$data["chofer"].",".$data["tractor"].",
        ".$data["cliente"].",".$data["arrastrado"].")";
        $idViaje=$this->database->executeId($sqlViaje);
        if($idViaje!=0){
            $this->createQrCode($idViaje);
            return $idViaje;
        }
        return false;
    }
    public function setCliente($data)
    {
        $cuit=$data["cliente"];
        if($cuit=="" && isset($data["cuit"])){
            $sql="INSERT INTO Cliente (cuit,denominacion,direccion,telefono,
            email,contacto1,contacto2) VALUES (".$data["cuit"].",
            '".$data["denominacion"]."',
            '".$data["direccion"]."',
            ".$data["telefono"].",
            '".$data["email"]."',
            ".$data["contacto1"].",
            ".$data["contacto2"].")";
            $this->database->execute($sql);
            $cuit=$data["cuit"];
        }
        elseif(!isset($data["cuit"])){
            return false;
        }
        return $cuit;
    }
    public function getClientes(){
        $sql="SELECT * FROM Cliente";
        return $this->database->query($sql);
    }
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
    public function calculateConsumoPorKm($vehiculo, $km){
        return $km * $vehiculo["consumo"] / 100;
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
    public function cancelTravel($data){
        $dato=$this->getTravel($data["codViaje"])[0];
        if($this->canBeCanceled($dato)) {
            $data["estado"]="cancelado";
            $this->updateTravelStatus($data);
            return true;
        }
        return false;
    }
    public function canBeCanceled($data){
        if ((strcmp($data["estado"], "en carga") == 0)
            || (strcmp($data["estado"], "en curso") == 0)) {
            return true;
        } else {
            return false;
        }
    }
    public function updateTravelStatus($data){
        $dato=$this->getTravel($data["codViaje"])[0];
        if($this->isModifiable($dato)){
            $sql="UPDATE Viaje SET estado='".$data["estado"]."' WHERE codViaje=".$data["codViaje"];
            $this->database->execute($sql);
        }
    }
    public function isModifiable($viaje){
        return (strcmp($viaje["estado"],'cancelado')!=0) && (strcmp($viaje["estado"],'finalizado')!=0);
    }
    public function isModifiableList($data){
        for ($i=0; $i<sizeof($data);$i++){
            $data[$i]["isModifiable"]=$this->isModifiable($data[$i]);
        }
        return $data;
    }
    public function decodeQrCode($file){
        $qrCode=new \Zxing\QrReader($file);
        return $qrCode->text();
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
    public function validateChofer($chofer){
        $sessionUser=$_SESSION["user"];
        return strcmp($chofer, $sessionUser)==0;
    }
    public function updateTravelPoint($data){
        $date=new DateTime();
        $fecha = $date->format('Y-m-d H:i:s');
        $datosViaje=$this->getTravel($data["codViaje"])[0];
        if($this->isModifiable($datosViaje) && is_null($datosViaje["fllegada"])){
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
    public function updatePuntoCombustible($data){
        $datosViaje=$this->getTravel($data["codViaje"])[0];
        if($this->isModifiable($datosViaje) && is_null($datosViaje["fllegada"])){
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
        return $data["codViaje"];
    }
    public function getSumaFrom($viaje, $campo){
        $sql = "SELECT SUM(".$campo.") as suma".$campo." FROM Ubicacion WHERE viaje=".$viaje;
        return $this->database->query($sql);
    }
    public function calculateDesvio($codViaje,$kmFinal){
        $datosViaje=$this->getTravel($codViaje)[0];
        $desvio=$kmFinal-$datosViaje["kmEstimado"];
        return $desvio > 0 ? $desvio : 0;
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

    /* Datos para reportes */
    public function getKmRecorridosEnViaje(){
        $sql="SELECT Vehiculo.patente as Vehiculo, SUM(Viaje.kmTotales) as TotalViajado
        FROM Viaje
        JOIN Vehiculo
        ON Viaje.tractor=Vehiculo.codVehiculo
        WHERE Viaje.kmTotales IS NOT NULL
        GROUP BY Vehiculo.codVehiculo
        ORDER BY Vehiculo.patente";

        return $this->database->query($sql);
    }
    public function getKmRecorridosPorChofer(){
        $sql="SELECT Empleado.dni, Empleado.nombre, Empleado.apellido, SUM(Viaje.kmTotales) as TotalViajado
        FROM Viaje
        JOIN Empleado
        ON Viaje.chofer=Empleado.dni
        WHERE Viaje.kmTotales IS NOT NULL
        GROUP BY Empleado.dni
        ORDER BY Empleado.apellido DESC";
        return $this->database->query($sql);
    }
    public function getConsumoPromedioEnViajes(){
        $sql="SELECT Vehiculo.patente as Vehiculo, AVG(Viaje.consumoTotal*100/Viaje.kmTotales) as Promedio
        FROM Viaje
        JOIN Vehiculo
        ON Viaje.tractor=Vehiculo.codVehiculo
        WHERE Viaje.kmTotales IS NOT NULL
        GROUP BY Vehiculo.codVehiculo
        ORDER BY Vehiculo.patente DESC";
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
    public function getLongestTravel(){
        $sql="SELECT codViaje, origen, destino,fllegada, kmTotales as Recorrido
        FROM Viaje WHERE kmTotales = (SELECT MAX(kmTotales) FROM Viaje WHERE kmTotales IS NOT NULL)";
        return $this->database->query($sql);
    }
    public function getShortestTravel(){
        $sql="SELECT codViaje, origen, destino,fllegada, kmTotales as Recorrido
        FROM Viaje WHERE kmTotales = (SELECT MIN(kmTotales) FROM Viaje WHERE kmTotales IS NOT NULL)";
        return $this->database->query($sql);
    }
    /* En desarrollo */
    public function convertTimeDiffToNumber($time1, $time2){
        $inicio = new DateTime($time1);
        $fin = new DateTime($time2);
        $diff = $inicio->diff($fin);
        return $diff->days;
    }
}