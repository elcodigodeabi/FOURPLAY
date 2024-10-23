<?php
session_start();
include('../class/base.php');
include('../class/grupo.php');


// Solo procesa si se ha enviado el formulario
if (isset($_POST["creargrupo"])) {
    // Variables de formulario
    $nombreGrupo = $_POST['nombre'];
    $descGrupo = $_POST['desc']; // Asegúrate de usar "desc", no "descripcion"
	
    // Crear la instancia del grupo
    $grupo = new Grupo();

    // Crear el grupo en la base de datos
    $result = $grupo->crearGrupo($_SESSION['id'], $nombreGrupo, $descGrupo);

    // Verificar si el grupo fue creado exitosamente
    if ($result) {
        // Obtener el ID del grupo recién creado
        $grupoId = $grupo->obtenerIdPorNonbreYDesc($nombreGrupo, $descGrupo); // Deberías tener un método para obtener el último ID insertado
		$row = mysqli_fetch_assoc($grupoId);
		$grupoId = $row['gru_id'];

        // Verifica si se ha subido un archivo
        if (isset($_FILES['imagen_grupo']) && $_FILES['imagen_grupo']['error'] == 0) {
            $imagen = $_FILES['imagen_grupo'];

            // Ruta donde se almacenará la imagen
            $dirGrupo = '../grupos/' . $grupoId;
			// echo $dirGrupo;

            // Crear directorio si no existe
            if (!file_exists($dirGrupo)) {
                mkdir($dirGrupo, 0755, true);
            }

            // Nombre fijo para el archivo
            $nombreImagen = 'imagen_grupo.png';

            // Ruta final del archivo con el nuevo nombre
            $rutaImagen = $dirGrupo . '/' . $nombreImagen;

            // Mover la imagen subida a la carpeta destino con el nuevo nombre
            if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                // echo "Imagen subida y guardada con éxito.";
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "No se ha subido ninguna imagen.";
        }

        // Redirigir al menú u otra página
        header("Location: ../menu.php");
    } else {
        echo "Error al crear el grupo.";
    }
}
?>