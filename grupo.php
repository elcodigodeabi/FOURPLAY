<?php
session_start();
require 'class/base.php';
require 'class/usuario.php';
require 'class/mensaje.php';
require 'class/grupo.php';

$grupoId = isset($_GET['gru_id']) ? (int)$_GET['gru_id'] : 0;
$usuarioId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

if ($grupoId === 0 || $usuarioId === 0) {
    die("Grupo o usuario no válido.");
}

$grupo = new Grupo();
$miembro = $grupo->esMiembro($usuarioId, $grupoId);

if (!$miembro) {
    die("No eres miembro de este grupo.");
}

// Obtener los mensajes del grupo
$mensaje = new Mensaje();
$mensajes = $mensaje->obtenerMensajesPorGrupo($grupoId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>4play - Grupo</title>
    <link rel="icon" href="public/favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="public/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="public/grupo.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container-form-editgrupo">
        <form class="edit_grupo" action="processes/editarGrupo.php" method="post" enctype="multipart/form-data">
            <span id="cerrar-form-editgrupo">X</span>
            <?php
            $row = $grupo->getInfo($grupoId);
            $row = mysqli_fetch_assoc($row);
            ?>
            <h4><?php echo $row['gru_nombre'];?></h4>
            <img src="" alt=""><input type="file" name="imagen_grupo">

            <label for="">Nombre del grupo</label>
            <input type="text" value="<?php echo $row['gru_nombre'];?>" name="nombre">

            <label for="">Descripción del grupo</label>
            <input type="text" value="<?php echo $row['gru_desc'];?>" name="desc">

            <input type="hidden" name="gru_id" value="<?php echo $_GET['gru_id']; ?>">

            <input type="submit" value="Cambiar" name="Cambiar">
        </form>
    </div>
    <!-- Cargar nuevo grupo -->
    <div class="container-form-addgrupo">
        <form action="processes/crearGrupo.php" method="POST" enctype="multipart/form-data">
            <span id="cerrar-form-grupo">X</span>
            <input type="file" name="imagen_grupo" required>
            <input type="text" placeholder="Nombre del grupo" name="nombre" required>
            <input type="text" placeholder="Descripcion del grupo" name="desc" required>
            <input type="submit" value="Crear Grupo" name="creargrupo">
        </form>
    </div>
    <div class="display">
        <?php include 'templates/header.php'; ?>
        <?php include 'templates/nav.php'; ?>
        <main class="contenedor-grupo">
            <div class="grupo">
                <div class="grupo__head">
                    <?php
                    $row = $grupo->getInfo($grupoId);
                    $row = mysqli_fetch_assoc($row);
                    ?>
                    <div class="edit-grupo" id="editar-grupo">
                        <img class="edit-grupo__imagen" src="grupos/<?php echo $row['gru_id']; ?>/imagen_grupo.png?v=<?php echo time(); ?>">
                        <span class="edit-grupo__text">
                            <b><?php echo $row['gru_nombre'] ;?></b><br>
                            <span><?php echo mb_strimwidth($row['gru_desc'], 0, 50, '...'); ?></span>
                        </span>
                    </div>
                    <div id="herramientasGrupo" class="herramientas-grupo">
                        <div class="herramientas-grupo__item">
                            <?php include 'public/grupo-adherir-usuario.php'; ?>
                            <span class="herramientas-grupo__item__nombre">Nuevo miembro</span>
                        </div>
                    </div>
                    <form style="display: none;" id="formAdherirUsuario" action="processes/agregarMiembro.php" method="post" class="adherir-usuario">
                        <button class="adherir-usuario__boton" type="submit">+</button>
                        <input class="adherir-usuario__grupo" type="hidden" name="grupoId" value="<?php echo $grupoId; ?>">
                        <input class="adherir-usuario__alias" placeholder="Escribe aqui el alias del usuario..." type="text" name="alias">
                    </form>
                </div>
                <div class="grupo__chat" id="chat">
                    <?php while ($row = mysqli_fetch_assoc($mensajes)): ?>
                        <?php if ($_SESSION['id'] == $row['usu_id']) { ?>
                            <div class="mensaje mi-mensaje">
                                <div class="mensaje__head mi-head">
                                    <strong>Yo</strong><small><?php echo $row['men_hora']; ?></small>
                                    <img class="mensaje__head__imagen" src="profile/<?php echo $row['usu_id']; ?>/foto_perfil/perfil.png?v=<?php echo time(); ?>">
                                </div>
                                <p class="mensaje__text mi-text"><?php echo htmlspecialchars($row['men_text']); ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="mensaje">
                                <div class="mensaje__head">
                                    <img class="mensaje__head__imagen" src="profile/<?php echo $row['usu_id']; ?>/foto_perfil/perfil.png?v=<?php echo time(); ?>">
                                    <strong><?php echo $row['usu_alias']; ?></strong><small><?php echo $row['men_hora']; ?></small>
                                </div>
                                <p class="mensaje__text"><?php echo htmlspecialchars($row['men_text']); ?></p>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>
                </div>
                <form class="grupo__campo-de-texto" id="mensajeForm">
                    <input type="hidden" name="grupoId" id="grupoId" value="<?php echo $grupoId; ?>">
                    <textarea placeholder="Escribe tu mensaje..." class="grupo__campo-de-texto__texto" id="mensaje" required></textarea>
                    <button class="grupo__campo-de-texto__boton" type="submit">Enviar</button>
                </form>
            </div>
        </main>
        <?php include 'templates/aside.php'; ?>
    </div>    

<script>
    const grupoId = document.getElementById('grupoId').value;
    const usuarioId = <?php echo $usuarioId; ?>;
    const alias = "<?php echo $_SESSION['alias']; ?>";

    // Conectar al servidor WebSocket
    const socket = new WebSocket('ws://34.72.201.134:8080');

    // Configurar eventos de WebSocket
    socket.onopen = function() {
        console.log("Conexión establecida");
        socket.send("Prueba de mensaje");
    };

    socket.onmessage = function(event) {
        console.log("Mensaje recibido del servidor:", event.data);
        const msg = JSON.parse(event.data);

        if (msg.grupoId == grupoId) {
            const chatDiv = document.getElementById('chat');
            const mensajeDiv = document.createElement('div');
            mensajeDiv.classList.add('mensaje');
            
            if (msg.usuarioId == usuarioId) {
                mensajeDiv.innerHTML = 
                    <div class="mensaje mi-mensaje">
                        <div class="mensaje__head mi-head">
                            <strong>Yo</strong><small>${msg.hora}</small>
                            <img class="mensaje__head__imagen" src="profile/${msg.usuarioId}/foto_perfil/perfil.png?v=${Date.now()}">
                        </div>
                        <p class="mensaje__text mi-text">${msg.texto}</p>
                    </div>;
            } else {
                mensajeDiv.innerHTML = 
                    <div class="mensaje">
                        <div class="mensaje__head">
                            <img class="mensaje__head__imagen" src="profile/${msg.usuarioId}/foto_perfil/perfil.png?v=${Date.now()}">
                            <strong>${msg.alias}</strong><small>${msg.hora}</small>
                        </div>
                        <p class="mensaje__text">${msg.texto}</p>
                    </div>;
            }
            chatDiv.appendChild(mensajeDiv);
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }
    };

    socket.onerror = function(error) {
        console.error("Error en WebSocket:", error);
    };

    socket.onclose = function() {
        console.log("Conexión cerrada");
    };

    document.getElementById('mensajeForm').addEventListener('submit', (event) => {
        event.preventDefault();
        const mensaje = document.getElementById('mensaje').value;
        const fecha = new Date().toISOString().split('T')[0];
        const hora = new Date().toTimeString().split(' ')[0];

        socket.send(JSON.stringify({
            grupoId: grupoId,
            usuarioId: usuarioId,
            alias: alias,
            texto: mensaje,
            fecha: fecha,
            hora: hora
        }));

        document.getElementById('mensaje').value = '';
    });
</script>
</body>
</html>
