<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
class Usuario {
    // Constructor de la clase Usuario
    public function __construct() {
        $this->base = new BaseLocal();
    }

    // Método para insertar un nuevo usuario en la base de datos. realizar cambios segun la nueva clase baselocal
    public function insertarUsuario($alias, $correo, $nombre, $apellido, $pass) {
        $sql = "INSERT INTO usuarios (usu_alias, usu_pass, usu_nombre, usu_apellido, usu_correo, usu_estado, rol_id) VALUES (?, ?, ?, ?, ?, true, 1)";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("sssss", $alias, $pass, $nombre, $apellido, $correo);
        return $this->base->ejecutarConsulta($stmt);
    }
       
    //chequear usuario existente login
    public function verificar($dataUser, $pass){
        $sql = "SELECT * FROM usuarios WHERE (usu_alias = ? OR usu_correo = ?) AND usu_pass = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("sss", $dataUser, $dataUser, $pass);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }

    //verificar usuario registro
    public function verificarUsuarioRegistro($alias, $correo){
        $sql = "SELECT * FROM usuarios WHERE usu_alias = ? OR usu_correo = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $alias, $correo);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }
    //iniciar variables de sesion
    public function iniciarSesion($id, $nombre, $apellido, $alias){
        session_start();
        $_SESSION['id'] = $id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
        $_SESSION['alias'] = $alias;
    }
    //obtener id por correo
    public function obtenerIdPorCorreo($correo){
        $sql = "SELECT usu_id FROM usuarios WHERE usu_correo = ?;";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("s",$correo);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }
    //obtener id por alias
    public function obtenerIdPorAlias($alias){
        $sql = "SELECT usu_id FROM usuarios WHERE usu_alias = ?;";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("s",$alias);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }
    //obtener datos por id
    public function obtenerDatos($id){
        $sql = "SELECT * FROM usuarios WHERE usu_id = ?;";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("i",$id);
        $this->base->ejecutarConsulta($stmt);
        return $this->base->obtenerResultado($stmt);
    }
    // Método para actualizar los campos del perfil
    public function actualizarInfoPersonal($id, $nombre, $apellido) {
        $sql = "UPDATE usuarios SET usu_nombre = ?, usu_apellido = ? WHERE usu_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ssi", $nombre, $apellido, $id);
        $resultado = $this->base->ejecutarConsulta($stmt);
        return $resultado;
    }
    public function actualizarCorreo($id, $correo) {
        $sql = "UPDATE usuarios SET usu_correo = ? WHERE usu_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("si", $correo, $id);
        $resultado = $this->base->ejecutarConsulta($stmt);
        return $resultado;
    }
    public function actualizarAlias($id, $alias) {
        $sql = "UPDATE usuarios SET usu_alias = ? WHERE usu_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("si", $alias, $id);
        $resultado = $this->base->ejecutarConsulta($stmt);
        return $resultado;
    }
    //Metodos de recuperacion de contraseña:
    public function gestionarRecuperacionConCorreo($correo) {
        $token = bin2hex(random_bytes(15));
        $sql = "UPDATE usuarios SET usu_recuperacion = ? WHERE usu_correo = ?";
        
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ss", $token, $correo);
        $this->base->ejecutarConsulta($stmt);
        
        $stmt->close();

        $enlace = "http://localhost/fourplay/recuperacion.php?token=$token";
        $asunto = "Recuperacion de clave";
        $cuerpo = "Haz clic en el siguiente enlace para cambiar tu contraseña: <a href='$enlace'>$enlace</a>";
        
        return $this->enviarCorreo($correo, $asunto, $cuerpo);
    }
    //metodo para mandar el email
    private function enviarCorreo($destinatario, $asunto, $cuerpo) {
        $mail = new PHPMailer(true);
        try {
            //configuracion del mensaje a enviar
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fourplayhelpdesk@gmail.com';
            $mail->Password = 'agqp wbbr mfbz hify';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('fourplayhelpdesk@gmail.com', 'FourPlay');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            //mensaje
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;

            $mail->send();
            return "Correo enviado exitosamente.";
        } catch (Exception $e) {
            return "Error al enviar el correo";
        }
    }
    public function cambiarClaveConToken($token, $nuevaContraseña) {
        $sql = "SELECT usu_correo FROM usuarios WHERE usu_recuperacion = ?";
    
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("s", $token);
        $this->base->ejecutarConsulta($stmt);
        $result = $this->base->obtenerResultado($stmt);
        
        if ($result) {  // Verifica si la consulta SELECT fue exitosa
            $registro = mysqli_fetch_assoc($result);
            if ($registro) {
                $correo = $registro['usu_correo'];

                $sql = "UPDATE usuarios SET usu_pass = ?, usu_recuperacion = NULL WHERE usu_correo = ?";
                $stmt = $this->base->prepararConsulta($sql);
                $stmt->bind_param("ss", $nuevaContraseña, $correo);
                $ejecucion = $this->base->ejecutarConsulta($stmt);
                
                return $ejecucion;  // Devuelve true si la actualización fue exitosa
            } else {
                return false;  // No se encontró un registro con el token dado
            }
        } else {
            return false;  // Fallo en la consulta SELECT
        }
    }
}
?>