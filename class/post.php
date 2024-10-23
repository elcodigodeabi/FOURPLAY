<?php
//esta es la clase "Post".
class Post{
	public function __construct(){
		$this->base = new BaseLocal();
	}
	public function postear($pos_desc, $pos_dir, $usu_id, $jue_id, $cat_id){
        $sql = "INSERT INTO post (pos_desc, pos_dir, usu_id, jue_id, cat_id, pos_fecha, pos_hora) VALUES (?, ?, ?, ?, ?, CURDATE(), CURTIME())";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ssiii", $pos_desc, $pos_dir, $usu_id, $jue_id, $cat_id);
        return $this->base->ejecutarConsulta($stmt);
    }
	public function obtenerPostsDeInteres($usu_id){
        // Primero obtenemos los juegos de interés del usuario
        $sqlJuegos = "SELECT jue_id FROM usuxjue WHERE usu_id = ?";
        $stmtJuegos = $this->base->prepararConsulta($sqlJuegos);
        $stmtJuegos->bind_param("i", $usu_id);
        $this->base->ejecutarConsulta($stmtJuegos);
        $resultJuegos = $stmtJuegos->get_result();

        // Almacenamos los jue_id en un array
        $juegosIds = [];
        while ($row = $resultJuegos->fetch_assoc()) {
            $juegosIds[] = $row['jue_id'];
        }
        // Convertimos el array en una cadena separada por comas para usar en la consulta SQL
        $juegosIdsList = implode(',', $juegosIds);

        // Si no hay juegos de interés, retornar null
        if (empty($juegosIds)) {
            return null;
        }

        // Ahora obtenemos los posts que coinciden con los juegos de interés del usuario
        // Ordenamos por fecha y hora de manera descendente
        $sqlPosts = "SELECT * FROM post WHERE jue_id IN ($juegosIdsList) ORDER BY pos_fecha DESC, pos_hora DESC";
        $stmtPosts = $this->base->prepararConsulta($sqlPosts);
        $this->base->ejecutarConsulta($stmtPosts);

        // Retornamos el result set, dejando el fetch por fuera
        return $stmtPosts->get_result();
    }
    public function obtenerMisPosts($usu_id) {
        // Consulta para obtener los posts creados por el usuario, ordenados del más nuevo al más viejo
        $sql = "SELECT * FROM post WHERE usu_id = ? ORDER BY pos_fecha DESC, pos_hora DESC";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i", $usu_id);
        $this->base->ejecutarConsulta($stmt);

        // Retornamos el result set, dejando el fetch por fuera
        return $stmt->get_result();
    }
    public function obtenerTodosLosPosts() {
        // Consulta para obtener todos los posts, ordenados del más nuevo al más viejo
        $sql = "SELECT * FROM post ORDER BY pos_fecha DESC, pos_hora DESC";
        $stmt = $this->base->prepararConsulta($sql);
        $this->base->ejecutarConsulta($stmt);

        // Retornamos el result set, dejando el fetch por fuera
        return $stmt->get_result();
    }
    public function obtenerPostPorId($postId) {
    // Consulta para obtener los datos del post por su ID
        $sql = "SELECT * FROM post WHERE pos_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        
        // Vinculamos el parámetro del ID al query
        $stmt->bind_param("i", $postId);
        
        // Ejecutamos la consulta
        $this->base->ejecutarConsulta($stmt);
        
        // Obtenemos el resultado de la consulta
        $result = $stmt->get_result();
        
        // Verificamos si se encontró un registro y retornamos los datos
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // O retorna un array vacío si prefieres
        }
    }
    public function eliminarPostPorId($postId) {
        // Consulta para eliminar el post por su ID
        $sql = "DELETE FROM post WHERE pos_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        
        // Vinculamos el parámetro del ID al query
        $stmt->bind_param("i", $postId);
        
        // Ejecutamos la consulta
        if ($this->base->ejecutarConsulta($stmt)) {
            // Verificamos si se han afectado filas (es decir, si se ha eliminado un registro)
            if ($stmt->affected_rows > 0) {
                return true; // Eliminación exitosa
            } else {
                return false; // No se encontró el registro para eliminar
            }
        } else {
            return false; // Error en la consulta
        }
    } 
}
?>