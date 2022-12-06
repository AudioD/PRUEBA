<?php
    session_start();
    if (!isset($_SESSION['id_usuario'])) {
        header('Location: ../index.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function cerrarMensaje(){
            $(".container-mensaje").remove();
        }
    </script>
    <link rel="stylesheet" href="../assets/estilos.css">
    <title id="titulo-pagina"></title>
</head>
