<?php
class Juego {
    private $nombre;
    private $descripcion;
    private $foto;
    private $base;
    public function __construct() {
        $this->base = new BaseLocal();
    }

    public function crearJuego($jue_nombre, $jue_descrip) {
        $sql = "INSERT INTO juegos (jue_nombre, jue_desc) VALUES (?, ?)";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $jue_nombre, $jue_descrip);
        $this->base->ejecutarConsulta($stmt);
    }
    
    public function listarJuegos() {
        $sql = "SELECT * FROM juegos";
        $stmt = $this->base->prepararConsulta($sql);
        // $stmt->bind_param("i", $gru_id);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        return $result;
    }

    public function listarJuegosPorUsuario($usu_id) {
        $sql = "SELECT * FROM usuxjue WHERE usu_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $usu_id);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        return $result;
    }

    public function verificarJuego($jue_id, $usu_id) {
        $sql = "SELECT * FROM usuxjue WHERE jue_id = ? AND usu_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $jue_id, $usu_id);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }

    //añadir juego al perfil del usuario
    public function añadirJuego($jue_id, $usu_id) {
        $sql = "INSERT INTO usuxjue (jue_id, usu_id) VALUES (?, ?)";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $jue_id, $usu_id);
        return $this->base->ejecutarConsulta($stmt);
    }

    public function eliminarJuego($usu_id, $jue_id) {
        $sql = "DELETE FROM usuxjue WHERE usu_id = ? AND jue_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usu_id, $jue_id);
        return $this->base->ejecutarConsulta($stmt);
    }

    public function getInfo($jue_id) {
        $sql = "SELECT * FROM juegos WHERE jue_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $jue_id);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }

    public function __destruct(){
        $this->base->desconectar();
    }
}
?>
