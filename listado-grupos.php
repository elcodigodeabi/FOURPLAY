<?php
include("class/base.php");
include("class/grupo.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>4Play - Listado de grupos</title>
</head>
<body>
    <!--buscador de grupos-->
    <form action="" method="post">
        <input type="text" name="nombre_grupo">
        <input type="submit" value="Buscar" name="Buscar">
    </form>
    <!--listado de grupos-->
    <?php
    if (isset($_POST['Buscar'])) {
        $nombre_grupo = $_POST['nombre_grupo'];
        $grupo = new Grupo();        
        // Obtener los grupos que coincidan con el nombre
        $resultado_grupos = $grupo->listarGruposPorNombre($nombre_grupo);
        ?>
        <table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre del Grupo</th>
                <th>Cantidad de Miembros</th>
            </tr>
        <?php
        // Iterar sobre los grupos
        while ($row_grupo = mysqli_fetch_assoc($resultado_grupos)) {
            ?>
            <tr>
                <td><?php echo $row_grupo['gru_id']; ?></td>
                <td><?php echo $row_grupo['gru_nombre']; ?></td>

                <?php
                // Obtener la cantidad de miembros del grupo actual
                $resultado_miembros = $grupo->listarMiembros($row_grupo['gru_id']);
                $cantidadMiembros = 0;

                // Contar el número de miembros
                while ($row_miembro = mysqli_fetch_assoc($resultado_miembros)) {
                    $cantidadMiembros += 1;
                }
                ?>

                <td><?php echo $cantidadMiembros; ?></td>
            </tr>
            <?php
        }
        ?>       
        </table>
        <?php
    }
    ?>
</body>
</html>