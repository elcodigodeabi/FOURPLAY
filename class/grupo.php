<?php

class Grupo {

    private $id;
    private $nombre;
    private $descripcion;
    private $foto;
    private $base;
    public function __construct() {
        $this->base = new BaseLocal();
    }

    public function crearGrupo($usu_id, $gru_nombre, $gru_descrip) {
        $sql = "INSERT INTO grupos (gru_nombre, gru_desc, gru_fecha_creacion, gru_estado, gru_fecha_estado) VALUES (?, ?, now(), true, now())";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $gru_nombre, $gru_descrip);
        $this->base->ejecutarConsulta($stmt);

        // Obtener el ID del grupo recién creado

        // $gru_id = $this->id->lastInsertId();

        // $this->unirseGrupoRecienCreado($usu_id, $gru_id);
        $sql = "SELECT gru_id from grupos WHERE gru_nombre = ? AND gru_desc = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $gru_nombre,$gru_descrip);
        $this->base->ejecutarConsulta($stmt);

        $result = $this->base->obtenerResultado($stmt);
        //$result = objeto MySql
        $result = mysqli_fetch_assoc($result);
        //el objeto MySql lo transforma a un array 
        return $this->añadirModerador($usu_id, $result["gru_id"]);

    }

    public function obtenerIdPorNonbreYDesc($gru_nombre, $gru_descrip) {
        $sql = "SELECT * FROM grupos WHERE gru_nombre = ? AND gru_desc = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $gru_nombre, $gru_descrip);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);

    }
    public function obtenerIdPorNombre($gru_nombre) {
        $sql = "SELECT * FROM grupos WHERE gru_nombre = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("s", $gru_nombre);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }
    //Agregar miembro
    public function unirseGrupo($usu_id, $gru_id) {
        $sql = "INSERT INTO miembros (usu_id, gru_id, rol_id) VALUES (?,?,4)";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usu_id, $gru_id);
        return $this->base->ejecutarConsulta($stmt);
    }
 
    public function eliminarGrupo($gru_id) {
        $sql = "DELETE FROM grupos WHERE gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $gru_id);
        $this->base->ejecutarConsulta($stmt);
    }

    public function listarMisGrupos($usu_id) {
        $sql = "SELECT gru_id FROM miembros WHERE usu_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $usu_id);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        return $result;    
    }

    public function editarGrupo($gru_id, $gru_nombre, $gru_descrip, $gru_foto) {
        $sql = "UPDATE grupos SET gru_nombre = ?, gru_desc = ?, gru_foto = ? WHERE gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("sssi", $gru_nombre, $gru_descrip, $gru_foto, $gru_id);
        $this->base->ejecutarConsulta($stmt);
    }

    public function getInfo($gru_id) {
        $sql = "SELECT * FROM grupos WHERE gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $gru_id);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }

    /*metodos para miembros*/

    public function eliminarMiembro($usu_id, $gru_id) {
        $sql = "DELETE FROM miembros WHERE usu_id = ? AND gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usu_id, $gru_id);
        $this->base->ejecutarConsulta($stmt);
    }

    public function listarMiembros($gru_id) {
        $sql = "SELECT * FROM miembros WHERE gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $gru_id);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        return $result;
    }

    public function existeMiembro($usu_id, $gru_id) {
        $sql = "SELECT mie_id FROM miembros WHERE usu_id = ? AND gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usu_id, $gru_id);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }

    public function añadirModerador($usu_id, $gru_id) {
        $sql = "INSERT INTO miembros (usu_id, gru_id, rol_id) VALUES (?,?, 3)";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usu_id, $gru_id);
        return $this->base->ejecutarConsulta($stmt);
    }

    /*metodos para peticiones*/
    public function listarPeticiones($gru_id) {
        $sql = "SELECT usu_id FROM peticiones WHERE gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $gru_id);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        return $result;    
    }
    public function crearPeticion($usu_id, $gru_id) {
        $sql = "INSERT INTO peticiones (pet_fecha, gru_id, usu_id) VALUES (now(), ?, ?)";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $gru_id, $usu_id);
        $this->base->ejecutarConsulta($stmt);
    }

    /* metodos para el chat */
    
    // Verifica si un usuario es miembro de un grupo
    public function esMiembro($usuarioId, $grupoId) {
        $sql = "SELECT * FROM miembros WHERE usu_id = ? AND gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usuarioId, $grupoId);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        return mysqli_num_rows($result) > 0;
    }

    public function __destruct(){
        $this->base->desconectar();
    }
}
?>