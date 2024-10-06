<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once 'base.php';
require_once 'mensaje.php';

class Chat implements MessageComponentInterface {
    protected $clients;
    private $base;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->base = new BaseLocal(); // Asume que tienes esta clase para manejar la base de datos
    }

    public function onOpen(ConnectionInterface $conn) {
        // Se ejecuta cuando un nuevo cliente se conecta
        $this->clients->attach($conn);
        echo "Nueva conexión ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if (isset($data['grupoId']) && isset($data['usuarioId']) && isset($data['alias']) && isset($data['texto']) && isset($data['fecha']) && isset($data['hora'])) {
            $grupoId = $data['grupoId'];
            $usuarioId = $data['usuarioId'];
            $alias = $data['alias'];
            $texto = $data['texto'];
            $fecha = $data['fecha'];
            $hora = $data['hora'];

            if ($this->esMiembroDelGrupo($usuarioId, $grupoId)) {
                // Insertar el mensaje en la base de datos con la fecha y hora proporcionadas
                $mensaje = new Mensaje(); // Asegúrate de que esta clase está disponible
                $mensaje->insertarMensaje($grupoId, $usuarioId, $texto, $fecha, $hora);

                // Crear un array con los detalles completos del mensaje
                $mensajeConDetalles = [
                    'grupoId' => $grupoId,
                    'usuarioId' => $usuarioId,
                    'alias' => $alias,
                    'texto' => $texto,
                    'fecha' => $fecha,
                    'hora' => $hora
                ];

                // Codificar los detalles a JSON
                $mensajeJson = json_encode($mensajeConDetalles);

                // Enviar el mensaje a todos los clientes conectados
                foreach ($this->clients as $client) {
                    //if ($from !== $client) {
                        $client->send($mensajeJson);
                    //}
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Se ejecuta cuando un cliente se desconecta
        $this->clients->detach($conn);
        echo "Desconexión ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function esMiembroDelGrupo($usuarioId, $grupoId) {
        // Verificar en la base de datos si el usuario es miembro del grupo
        $sql = "SELECT * FROM miembros WHERE usu_id = ? AND gru_id = ?";
        $stmt = $this->base->prepararConsulta($sql);
        $stmt->bind_param("ii", $usuarioId, $grupoId);
        $this->base->ejecutarConsulta($stmt);
        $resultado = $this->base->obtenerResultado($stmt);

        return mysqli_num_rows($resultado) > 0;
    }
}
?>