<?php
namespace MyApp;
class BaseLocal{
  private $servidor = "localhost";
  private $usu = "root";
  private $pass = "";
  private $bd = "fourplay";
  private $conexion;
  public function __construct(){
    //settear la conexion a la variable conexion
    $this->conexion = mysqli_connect($this->servidor, $this->usu, $this->pass, $this->bd);
  }
  public function prepararConsulta($sql){
    return $this->conexion->prepare($sql);
  }
  public function ejecutarConsulta($stmt){
    return $stmt->execute();
  }
  public function obtenerResultado($stmt){
    return $stmt->get_result();
  }
  public function desconectar(){
    mysqli_close($this->conexion);
  }
}
?>