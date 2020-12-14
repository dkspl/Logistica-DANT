<?php


class UsuarioModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function register($data){
        $data["rol"]=$this->getRolNumber($data["rol"]);
        $sql="INSERT INTO Empleado (dni, nombre, apellido, email, password, fnac, rol)
                values(".$data["dni"].", '".$data["nombre"]."', '".$data["apellido"]."',
                 '".$data["email"]."', '".$data["pass"]."', '".$data["fnac"]."', '".$data["rol"]."')";
        $this->database->execute($sql);
    }
    public function login($data){
        $sql="SELECT * FROM Empleado WHERE dni=".$data["dni"]." AND
        password='".$data["pass"]."'";
        $query=$this->database->query($sql);
        return $this->acceso($query);
    }
    public function completeSignin($data){
        if($data["rol"]==3){
            $sql = "INSERT INTO Mecanico (dniMec, matricula) 
                VALUES(".$data["dni"].",".$data["matricula"].")";
        }
        else{
            $sql = "INSERT INTO Chofer (dniChof, tipoLicencia) 
                VALUES(".$data["dni"].",'".$data["tipoLicencia"]."')";
        }
        $this->database->execute($sql);
    }
    public function getRol($dni)
    {
        $user=$this->getUserByDni($dni);
        return (!(is_null($user[0]["rol"]))) ? $user[0]["rol"] : "inicio";;
    }
    public function acceso($user){
        if((!(empty($user)))) {
            if ($user[0]["estado"]!=0) {
                $_SESSION["user"] = $user[0]["dni"];
                return true;
            }
        }
        else
            return false;
    }
    public function getUserByDni($dni)
    {
        $sql = "SELECT * FROM Empleado WHERE dni=".$dni;
        return $this->database->query($sql);
    }
    public function logout(){
        session_unset();
        session_destroy();
    }
    public function isSessionStarted(){
        return isset($_SESSION["user"]);
    }
    public function getUsuarios(){
        return $this->database->query("SELECT * FROM Empleado WHERE fechaBaja IS NULL");
    }
    public function editUser($data){
        $sql="UPDATE Empleado SET email='".$data["email"]."', fnac='".$data["fnac"]."' WHERE dni=".$data["dni"];
        $this->database->execute($sql);
    }
    public function editPassword($data){
        $user=$this->getUserByDni($data["dni"]);
        if(strcmp($user[0]["password"],$data["actual"]) == 0
        && strcmp($data["nueva"],$data["confirmar"]) == 0){
            $sql = "UPDATE Empleado SET password='".$data["nueva"]."' WHERE dni=".$user[0]["dni"];
            $this->database->execute($sql);
        }
    }
    public function getChoferesDisponibles(){
        $sql="SELECT * FROM Empleado
            JOIN Chofer
            ON Empleado.dni = Chofer.dniChof
            WHERE Chofer.disponibilidad=1
            AND estado=1";
        return $this->database->query($sql);
    }
    public function changeStatus($dni){
        $user=$this->getUserByDni($dni);
        $newStatus=$this->parseBooleanToBin(!($user[0]["estado"]));
        $sql="UPDATE Empleado SET estado=".$newStatus." WHERE dni=".$dni;
        $this->database->execute($sql);
    }
    public function parseBooleanToBin($status){
        return $status ? 1 : 0;
    }
    public function validateAdminPermissions($dni){
        $user=$this->getUserByDni($dni)[0];
        if(is_array($user)){
            if(strcmp($user["rol"],"administrador")==0)
                return true;
        }
        return false;
    }
    public function validatePermissions($dni){
        $user=$this->getUserByDni($dni)[0];
        if(is_array($user)){
            if(strcmp($user["rol"],"chofer")!=0)
                return true;
        }
        return false;
    }
    public function getRolNumber($numero){
        switch($numero){
            case 1: return 'administrador';
            case 2: return 'supervisor';
            case 3: return 'mecanico';
            case 4: return 'chofer';
        }
    }
    public function editUserByAdmin($data){
        $sql="UPDATE Empleado SET 
        nombre='".$data["nombre"]."', 
        apellido='".$data["apellido"]."', 
        email='".$data["email"]."', 
        fnac='".$data["fnac"]."' 
        WHERE dni=".$data["dni"];
        $this->database->execute($sql);
    }
    public function getCantidadEmpleadosPorRol(){
        $empleados=$this->getUsuarios();
        $admin=0;
        $supervisor=0;
        $mecanico=0;
        $chofer=0;
        for($i=0;$i<sizeof($empleados);$i++){
            if($empleados[$i]["estado"]!=0){
                switch($empleados[$i]["rol"]){
                    case "administrador": $admin++;
                        break;
                    case "supervisor": $supervisor++;
                        break;
                    case "mecanico": $mecanico++;
                        break;
                    case "chofer": $chofer++;
                        break;
                }
            }
        }
        return array("administradores"=>$admin,
            "supervisores"=>$supervisor,
            "mecanicos"=>$mecanico,
            "choferes"=>$chofer);
    }
    public function getUsuariosSinAsignar(){
        $sql="SELECT * FROM Empleado WHERE estado=0
        AND fechaBaja IS NULL";
        return $this->database->query($sql);
    }
    public function getUsuariosEliminados(){
        $sql="SELECT count(*) as Total, fechaBaja
        FROM Empleado 
        WHERE fechaBaja IS NOT NULL";
        return $this->database->query($sql);
    }
}