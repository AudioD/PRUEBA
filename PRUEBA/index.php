<?php //Comprobar si tiene la sesion iniciada, en caso de si devolver a pacientes
    session_start();
    if (isset($_SESSION['id_usuario'])) {
        header('Location: views/pacientes.php');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/estilos.css">
    <title>Bienvenido al Hospital</title>
</head>
<body class="fondo-medicos">
    <?php if (isset($_GET['mensaje'])): ?>
      <p> <?= $_GET['mensaje'] ?></p>
    <?php endif; ?>

    <form action="code/login.php" method="POST">
        <div class="contenedor-login">
            <h1 class="textcenter mb3" style="font-family: OpenSansRegular">Iniciar Sesión</h1>
            <label for="documento">Ingrese su documento</label>
            <input type="text" id="documento" name="documento" placeholder="Ejm: 4568439483" required>
            <label for="documento">Ingrese la contraseña</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn btn-blue w100">Ingresar</button>
        </div>    
    </form>
</body>
</html>