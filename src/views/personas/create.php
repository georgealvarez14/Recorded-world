<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Datos</title>
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
            overflow-y: auto; /*para que la forma tenga scroll cuando pase el máximo del tamaño*/
        }

        form {
            background-color: white;
            border: 1px solid #CCC;
            padding: 20px;
            max-width: 500px; /*máximo del tamaño de la forma*/
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
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
            background-color:rgb(8, 94, 84);
        }


        
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #CCC;
            border-radius: 5px;
            box-sizing: border-box;
        }

    </style>
</head>
<body>
<header>
    <div class="nav-buttons">
        <a href="javascript:history.back()">Regresar</a>
        <a href="dashboard-2025.php">Inicio</a>
    </div>
    <h1>Registrar Persona</h1>
</header>

<main>
    <form action="Insertar_DB_2025.php" method="POST" enctype="multipart/form-data">
        <h2>Información Personal</h2>

        <label for="id_user">ID de Usuario (requerido):</label><br>
            <input type="number" 
               id="id_user" 
               name="id_user" 
               required 
               min="1" 
               placeholder="Ej: 1001"><br><br>
        
        <label for="foto_persona">Foto de Perfil:</label><br>
        <input type="file" id="foto_persona" name="foto_persona" accept="image/*"><br><br>
        
        <label for="nom_user">Nombre Completo (requerido):</label><br>
        <input type="text" id="nom_user" name="nom_user" required maxlength="150"><br><br>
        
        <label for="ciudad">Ciudad (requerido):</label><br>
        <select id="ciudad" name="ciudad" required>
            <option value="">Seleccionar ciudad...</option>
            <option value="BA">Barranquilla</option>
            <option value="BO">Bogotá</option>
            <option value="CA">Cali</option>
            <option value="CU">Cúcuta</option>
            <option value="ME">Medellín</option>
        </select><br><br>
        
        <label for="telef_user">Teléfono:</label><br>
        <input type="number" id="telef_user" name="telef_user"><br><br>
        
        <label for="correo_user">Correo Electrónico:</label><br>
        <input type="email" id="correo_user" name="correo_user" maxlength="30"><br><br>
        
        <label for="contrasena_user">Contraseña:</label><br>
        <input type="password" id="contrasena_user" name="contrasena_user" maxlength="10"><br><br>
        
        <label for="tipo_persona">Tipo de Persona (requerido):</label><br>
        <select id="tipo_persona" name="tipo_persona" required>
            <option value="">Seleccionar tipo...</option>
            <option value="EST">Estudiante</option>
            <option value="DOC">Docente</option>
            <option value="ADM">Administrativo</option>
            <option value="ACU">Acudiente</option>
        </select><br><br>

        <h2>Información Académica</h2>
        
        <label for="cod_grado">Grado:</label><br>
        <select id="cod_grado" name="cod_grado">
            <option value="">Seleccionar grado...</option>
            <option value="61">Sexto</option>
            <option value="71">Séptimo</option>
            <option value="81">Octavo</option>
            <option value="91">Noveno</option>
            <option value="101">Décimo</option>
            <option value="111">Undécimo</option>
        </select><br><br>
        
        <label for="grupo">Grupo:</label><br>
        <input type="number" id="grupo" name="grupo"><br><br>

        <h2>Información del Acudiente</h2>
        
        <label for="cc_acudiente">Cédula del Acudiente:</label><br>
        <input type="number" id="cc_acudiente" name="cc_acudiente"><br><br>
        
        <label for="nom_acudiente">Nombre del Acudiente:</label><br>
        <input type="text" id="nom_acudiente" name="nom_acudiente" maxlength="50"><br><br>
        
        <label for="telef_acudiente">Teléfono Acudiente:</label><br>
        <input type="number" id="telef_acudiente" name="telef_acudiente"><br><br>
        
        <label for="telef_acudiente_dos">Teléfono Acudiente 2:</label><br>
        <input type="number" id="telef_acudiente_dos" name="telef_acudiente_dos"><br><br>
        
        <label for="correo_acudiente">Correo del Acudiente:</label><br>
        <input type="email" id="correo_acudiente" name="correo_acudiente" maxlength="50"><br><br>
        
        <input type="submit" value="Guardar Persona">
    </form>
</main>

<footer>
    <p>© 2025 Catálogo - Todos los derechos reservados</p>
</footer>
</body>
</html>