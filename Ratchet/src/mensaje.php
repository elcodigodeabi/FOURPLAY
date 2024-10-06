<?php
namespace MyApp;
class Mensaje {
    private $base;

    public function __construct() {
        $this->base = new BaseLocal();
    }

    // Método para insertar un nuevo mensaje en la base de datos
    public function insertarMensaje($grupoId, $usuarioId, $texto) {
        $sql = "INSERT INTO mensajes (gru_id, usu_id, men_text, men_fecha, men_hora) VALUES (?, ?, ?, CURDATE(), CURTIME())";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("iis", $grupoId, $usuarioId, $texto);
        return $this->base->ejecutarConsulta($stmt);
    }

    // Método para obtener los mensajes de un grupo
    public function obtenerMensajesPorGrupo($grupoId) {
        $sql = "SELECT mensajes.men_id, mensajes.men_text, mensajes.men_fecha, mensajes.men_hora, usuarios.usu_id, usuarios.usu_alias 
                FROM mensajes 
                INNER JOIN usuarios ON mensajes.usu_id = usuarios.usu_id 
                WHERE mensajes.gru_id = ? 
                ORDER BY mensajes.men_fecha DESC, mensajes.men_hora DESC";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $grupoId);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }
}
?>