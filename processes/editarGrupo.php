<?php
// Variables de formulario
$nombreGrupo = $_POST['nombre'];
$descGrupo = $_POST['desc'];
$grupoId = $_POST['gru_id'];

// Verifica si se ha subido un archivo
if (isset($_FILES['imagen_grupo']) && $_FILES['imagen_grupo']['error'] == 0) {
    $imagen = $_FILES['imagen_grupo'];

    // Ruta donde se almacenará la imagen
    $dirGrupo = '../grupos/' . $grupoId;

    // Crear directorio si no existe
    if (!file_exists($dirGrupo)) {
        mkdir($dirGrupo, 0755, true);
        // mkdir($dirGrupo . '/imagenes', 0755, true);
    }

    // Nombre fijo para el archivo
    $nombreImagen = 'imagen_grupo.png';

    // Ruta final del archivo con el nuevo nombre
    $rutaImagen = $dirGrupo . '/' . $nombreImagen;

    // Mover la imagen subida a la carpeta destino con el nuevo nombre
    if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
        echo "Imagen subida y guardada con éxito.";
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "No se ha subido ninguna imagen.";
}

// Aquí puedes continuar procesando la actualización de los datos del grupo
// por ejemplo, actualizar la base de datos con el nuevo nombre y descripción del grupo

// Redirigir o mostrar un mensaje

session_start();
include('../class/base.php');
include('../class/grupo.php');
if(isset($_POST['Cambiar'])){
   $nombre = $_POST['nombre'];
   $desc = $_POST['desc'];
   $id = $_POST['gru_id'];

   $grupo = new Grupo();
   //cuando recien lo cambia no lo pone el la pantalla
   $grupo = $grupo->editarGrupo($id, $nombre, $desc, $foto);
   
   header("Location: ../grupo.php?gru_id=" . $_POST['gru_id']);

}

header("Location: ../grupo.php?gru_id=" . $grupoId);
?>

