<?php
    switch ($_POST["funcion"]) {
        case 'autollenado':
            $genero = consulta_sql('SELECT * FROM genero',false);
            $departamentos = consulta_sql('SELECT * FROM departamentos',false);
            $documentos = consulta_sql('SELECT * FROM tipos_documento',false);
            echo json_encode(array("genero" => $genero,"departamentos" => $departamentos,"documentos" => $documentos));
            break;
        case 'datos_paciente':
            echo consulta_sql('SELECT * FROM paciente WHERE id = ' . $_POST["id_paciente"]);
            break;
        case 'buscar_municipios':
            echo consulta_sql('SELECT * FROM municipios WHERE departamento_id = ' . $_POST["id_departamento"]);
            break;
        case 'listar':
            echo consulta_sql('
                SELECT 
                    P.imagen,P.id,P.numero_documento AS documento,P.nombre1,P.nombre2,P.apellido1,P.apellido2,
                    TD.nombre AS tipo_documento,
                    G.nombre AS genero,
                    D.nombre AS departamento,
                    M.nombre AS municipio
                FROM paciente P
                INNER JOIN tipos_documento TD ON TD.id = tipo_documento_id
                INNER JOIN genero G ON G.id = genero_id
                INNER JOIN departamentos D ON D.id = departamento_id
                INNER JOIN municipios M ON M.id = municipio_id
            ');
            break;
        case 'crear':
            //Validaciones
            if(!ctype_digit(trim($_POST["numero-documento"]))){echo "El campo numero de documento debe ser entero";return false;}
            if(is_null($_POST["primer-nombre"]) || $_POST["primer-nombre"] == ""){echo "El primer nombre es requerido";return false;}
            if(is_null($_POST["primer-apellido"]) || $_POST["primer-nombre"] == ""){echo "El primer apellido es requerido";return false;}
            $nombre_img = "";
            if($_FILES['campo-imagen-paciente']['type'] == "image/jpg" || $_FILES['campo-imagen-paciente']['type'] == "image/jpeg" || $_FILES['campo-imagen-paciente']['type'] == "image/png" || $_FILES['campo-imagen-paciente']['type'] == "image/gif"){
                $nombre_img = date("YmdHis") . $_FILES['campo-imagen-paciente']['name'];
                $img_temp = $_FILES['campo-imagen-paciente']['tmp_name'];
                $ruta = "../portraits" . '/' . $nombre_img;
                move_uploaded_file($img_temp,$ruta);
            }
            echo consulta_sql('
                INSERT INTO paciente 
                (tipo_documento_id,numero_documento,nombre1,nombre2,apellido1,apellido2,genero_id,departamento_id,municipio_id,imagen)
                VALUES
                (' . $_POST["tipo-documento"] . ',' . trim($_POST["numero-documento"]) . ',"' . trim($_POST["primer-nombre"]) . 
                '","' . trim($_POST["segundo-nombre"]) . '","' . trim($_POST["primer-apellido"]) . '","' . trim($_POST["segundo-apellido"]) . '",' . $_POST["genero"] . 
                ',' . $_POST["departamento"] . ',' . $_POST["municipios"] . ',"' . $nombre_img . '")
            ');
            break;
        case 'actualizar':
            //Validaciones
            if(!ctype_digit(trim($_POST["numero-documento"]))){echo "El campo numero de documento debe ser entero";return false;}
            if(is_null($_POST["primer-nombre"]) || $_POST["primer-nombre"] == ""){echo "El primer nombre es requerido";return false;}
            if(is_null($_POST["primer-apellido"]) || $_POST["primer-nombre"] == ""){echo "El primer apellido es requerido";return false;}
            $nombre_img = "";
            $csl_img = consulta_sql('SELECT imagen FROM paciente WHERE id = ' . $_POST["id_paciente"],false);
            if($_FILES['campo-imagen-paciente']['name'] == ""){
                $nombre_img = $csl_img[0]["imagen"];
            }elseif($_FILES['campo-imagen-paciente']['type'] == "image/jpg" || $_FILES['campo-imagen-paciente']['type'] == "image/jpeg" || $_FILES['campo-imagen-paciente']['type'] == "image/png" || $_FILES['campo-imagen-paciente']['type'] == "image/gif"){
                array_map( "unlink", glob( '../portraits/' . $csl_img[0]["imagen"] ) );
                $nombre_img = date("YmdHis") . $_FILES['campo-imagen-paciente']['name'];
                $img_temp = $_FILES['campo-imagen-paciente']['tmp_name'];
                $ruta = "../portraits" . '/' . $nombre_img;
                move_uploaded_file($img_temp,$ruta);
            }
            echo consulta_sql('
                UPDATE paciente SET
                tipo_documento_id =' . $_POST["tipo-documento"] . ',numero_documento = ' . trim($_POST["numero-documento"]) . ',nombre1 = "' . trim($_POST["primer-nombre"]) . 
                '",nombre2 = "' . trim($_POST["segundo-nombre"]) . '",apellido1 = "' . trim($_POST["primer-apellido"]) . '",apellido2 = "' . trim($_POST["segundo-apellido"]) . '",genero_id = ' . $_POST["genero"] . 
                ',departamento_id = ' . $_POST["departamento"] . ',municipio_id = ' . $_POST["municipios"] . ',imagen = "' . $nombre_img . '"
                WHERE id = ' . $_POST["id_paciente"]
            );
            break;
            break;
        case 'eliminar':
            echo consulta_sql('DELETE FROM paciente WHERE id = "' . $_POST["id_paciente"] . '"');
            break;
        default:
            die("Funcion no encontrada");
            break;
    }

    function consulta_sql($query,$encode = true){//Si esta encode habilitado retorna el valor encodeado
        require 'database.php';
        $sql = $con->prepare($query);
        $sql->execute();
        $respuesta = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return ($encode ? json_encode($respuesta) : $respuesta);
    }
?>