<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Materia</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #FAFAFA;
        }

        header {
            background-color: #0A4A74;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            margin: 0 auto;
        }

        .nav-buttons {
            position: absolute;
            left: 20px;
        }

        .nav-buttons a {
            text-decoration: none;
            background-color: white;
            color: #0A4A74;
            padding: 6px 12px;
            border-radius: 5px;
            margin-right: 10px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .nav-buttons a:hover {
            background-color: #d0e4f7;
        }

        footer {
            background-color: #0A4A74;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        main {
            padding: 100px 20px 60px 20px;
            height: 100%;
            overflow-y: auto;
        }

        form {
            background-color: white;
            border: 1px solid #CCC;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #CCC;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #0A4A74;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #085e54;
        }

        .contenedor-img {
            width: fit-content;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            text-align: center;
        }
        
        .alerta-exito {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        
        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            font-size: 12px;
            overflow: auto;
        }
    </style>
</head>
<body>

<header>
    <div class="nav-buttons">
        <a href="../dashboard.php">Inicio</a>
        <a href="materias.php">Regresar</a>
    </div>
    <h1>Editar Materia</h1>
</header>

<main>
    <?php
    // Mostrar mensaje de actualización exitosa si existe
    if (isset($_GET['actualizado']) && $_GET['actualizado'] == 1) {
        echo '<div class="alerta-exito">Los datos se han actualizado correctamente.</div>';
    }
    ?>
    
    <form action="./Editar_DB_2025.php" method="POST" enctype="multipart/form-data">
        <?php
        $id = $_GET['id'] ?? 0;
        
        try {
            $conexion = new PDO('mysql:host=localhost;dbname=registro;', 'root', '');
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para obtener datos de la materia
            $stmt = $conexion->prepare("
                SELECT 
                    m.cod_categoria,
                    m.descripcion
                FROM materias m
                WHERE m.cod_categoria = ?");
                
            $stmt->execute([$id]);
            $materia = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$materia) {
                die("Materia no encontrada");
            }

            // Campos del formulario
            ?>
            <input type="hidden" name="id_materia" value="<?php echo ($materia['cod_categoria']); ?>">
            
            <label>Descripción:</label>
            <input type="text" name="descripcion" value="<?php echo ($materia['descripcion']); ?>" required>
            <?php

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
        <input type="submit" value="Guardar Cambios">
    </form>
</main>

<footer>
    <p>© 2025 Catálogo - Todos los derechos reservados</p>
</footer>

</body>
</html>