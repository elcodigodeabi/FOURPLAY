<?php
if (isset($_POST['grupoId']) && isset($_POST['alias'])) {
    $grupoId = $_POST['grupoId'];
    $alias = $_POST['alias'];

    // Incluir las clases
    include '../class/base.php';
    include '../class/grupo.php';
    include '../class/usuario.php';
    
    // Inicializar la clase
    $grupo = new Grupo();
    
    // Conseguir ID del usuario
    $result = (new Usuario())->obtenerIdPorAlias($alias);
    $result = mysqli_fetch_assoc($result);
    
    if (!$result) {
        $mensaje = "No se encontró un usuario con ese alias. Por favor, verifica el nombre ingresado e inténtalo de nuevo.";
        header('Location: ../grupo.php?gru_id=' . $grupoId . '&mensaje=' . urlencode($mensaje));
        exit();
    }
    
    $usuarioId = $result['usu_id'];
    
    // Verificar si el usuario ya es miembro del grupo
    $result = $grupo->existeMiembro($usuarioId, $grupoId);
    $result = mysqli_fetch_assoc($result);
    if ($result) {
        $mensaje = "Este usuario ya es miembro del grupo. No es necesario agregarlo nuevamente.";
        header('Location: ../grupo.php?gru_id=' . $grupoId . '&mensaje=' . urlencode($mensaje));
        exit();
    }
    
    // Agregar al usuario al grupo
    $result = $grupo->unirseGrupo($usuarioId, $grupoId);
    if ($result) {
        $mensaje = "El usuario se ha unido exitosamente al grupo.";
    } else {
        $mensaje = "Ocurrió un error al intentar agregar el usuario al grupo. Por favor, inténtalo de nuevo.";
    }
    
    // Redirigir con el mensaje correspondiente
    header('Location: ../grupo.php?gru_id=' . $grupoId . '&mensaje=' . urlencode($mensaje));
    exit();
} else {
    $mensaje = "Hubo un problema con los datos enviados. Por favor, inténtalo de nuevo.";
    header('Location: ../grupo.php?mensaje=' . urlencode($mensaje));
    exit();
}
?>