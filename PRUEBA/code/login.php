<?php 
    session_start();
    try {
        require 'database.php';
        
        if (!empty($_POST['documento']) && !empty($_POST['password'])) {
            $sql = $con->prepare('SELECT id, documento, password FROM usuarios WHERE documento = :documento');
            $sql->bindParam(':documento', $_POST['documento']);
            $sql->execute();
            $resultado = $sql->fetch(PDO::FETCH_ASSOC);

            if (count($resultado) > 0 && $_POST['password'] === $resultado['password']) {
                $_SESSION['id_usuario'] = $resultado['id'];
                header("Location: views/pacientes.php");
            } else {
                session_destroy(); 
                $mensaje = "Tu documento o contraseña no coinciden, verifica y vuelve a intentarlo";
            }
        }else{
            session_destroy(); 
            $mensaje = "Faltan credenciales por llenar";
        }
        header("Location: ../index.php?mensaje=" . urlencode($mensaje));
    } catch (\Throwable $th) {
        session_destroy(); 
        die("<h3>Se produjo un error interno.<h3>");
    }
?>
