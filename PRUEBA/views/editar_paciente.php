<?php include("headers.php"); ?>
<body>
    <?php include("cabecera.html"); ?>

    <?php if (!isset($_GET['id_paciente'])): ?>
      <div class="w100 textcenter d-flex justify-content-center mb5 container-mensaje"><span class="mensaje">IDENTIFICADOR NO ENCONTRADO <a href="pacientes.php" class="btn btn-red">VOLVER</a></span></div>
    <?php else: ?>
        <?php if (isset($_GET['mensaje'])): ?>
        <div class="w100 textcenter d-flex justify-content-center mb5 container-mensaje <?php echo $_GET['msgcolor']; ?>"><span class="mensaje"> <?= $_GET['mensaje'] ?><button     class="ml2" onclick="cerrarMensaje()">Cerrar Mensaje</button></span></div>
        <?php endif; ?>
        <div class="contenido">
            <div class="contenedor-editar-paciente mt5">

                <div class="spinner"></div>

                <fieldset id="listacampos" disabled>
                    <form id="formulario-actualizar-paciente">

                        <div class="bloque-contenido justify-content-center">
                            <img height="100" widht="150" class="imagen-paciente" id="imagen-paciente">
                            <label for="campo-imagen-paciente" class="lbl-imagen-paciente"><i class="fa fa-plus-square" aria-hidden="true"></i></label>
                            <input type="file" name="campo-imagen-paciente" id="campo-imagen-paciente" class="d-none" style="display: none;" accept="image/*">
                        </div>

                        <div class="bloque-contenido">
                            <div class="bloque-columna">
                                <label for="tipo-documento">Tipo de documento</label>
                                <select id="tipo-documento" name="tipo-documento"></select>
                            </div>
                            <div class="bloque-columna">
                                <label for="numero-documento">Numero de documento</label>
                                <input type="text" id="numero-documento" name="numero-documento">
                            </div>
                        </div>

                        <div class="bloque-contenido">
                            <div class="bloque-columna">
                                <label for="primer-nombre">Primer Nombre</label>
                                <input type="text" id="primer-nombre" name="primer-nombre">
                            </div>
                            <div class="bloque-columna">
                                <label for="segundo-nombre">Segundo Nombre</label>
                                <input type="text" id="segundo-nombre" name="segundo-nombre">
                            </div>
                        </div>

                        <div class="bloque-contenido">
                            <div class="bloque-columna">
                                <label for="primer-apellido">Primer Apellido</label>
                                <input type="text" id="primer-apellido" name="primer-apellido">
                            </div>
                            <div class="bloque-columna">
                                <label for="segundo-apellido">Segundo Apellido</label>
                                <input type="text" id="segundo-apellido" name="segundo-apellido">
                            </div>
                        </div>

                        <div class="bloque-contenido">
                            <div class="bloque-columna">
                                <label for="departamento">Departamento</label>
                                <select id="departamento" name="departamento" onchange="buscarMunicipios();"></select>
                            </div>
                            <div class="bloque-columna">
                                <label for="municipios">Municipio</label>
                                <select id="municipios" name="municipios"></select>
                            </div>
                            <div class="bloque-columna">
                                <label for="genero">Genero</label>
                                <select id="genero" name="genero"></select>
                            </div>
                        </div>

                        <div class="mt3 textcenter">
                            <a href="pacientes.php" class="btn btn-red btn-cancelar" disabled>Volver</a>
                            <a type="button" class="btn btn-blue btn-actualizar" disabled>Actualizar</a>
                        </div>

                        <input type="hidden" id="funcion" name="funcion" value="actualizar">
                        <input type="hidden" id="id_paciente" name="id_paciente" value="<?php echo $_GET['id_paciente'];?>">  
                    </form>
                </fieldset>
                
            </div>
        </div>
        
        <script>
            $("#titulo-pagina").html("Editar Paciente");
            $("#titulo-nav").html("Editar Paciente");
            autollenado();

            /* ** CARGAR IMAGEN ** */
            const $seleccionArchivos = document.querySelector("#campo-imagen-paciente"),
            $imagenPrevisualizacion = document.querySelector("#imagen-paciente");
            $seleccionArchivos.addEventListener("change", () => {
                const archivos = $seleccionArchivos.files;
                if (!archivos || !archivos.length) {
                    $imagenPrevisualizacion.src = "";
                    return;
                }
                const primerArchivo = archivos[0];
                const objectURL = URL.createObjectURL(primerArchivo);
                $imagenPrevisualizacion.src = objectURL;
            });

            function autollenado(){
                $.ajax({
                    method: "POST",
                    url: "../code/pacientes.php",
                    data: { funcion: 'autollenado' }
                }).done(function( respuesta ) {
                    const datos_autofill = JSON.parse(respuesta);
                    const generos = datos_autofill.genero;
                    const documentos = datos_autofill.documentos;
                    const departamentos = datos_autofill.departamentos;
                    documentos.forEach(el => {$("#tipo-documento").append("<option value='"+el.id+"'>"+el.nombre+"</option>");});
                    departamentos.forEach(el => {$("#departamento").append("<option value='"+el.id+"'>"+el.nombre+"</option>");});
                    generos.forEach(el => {$("#genero").append("<option value='"+el.id+"'>"+el.nombre+"</option>");});
                    buscarPaciente();
                }).fail(function (error){
                    alert("Se produjo al llenar los datos");
                });
            }

            function buscarPaciente(){
                let idpaciente = "<?php echo $_GET['id_paciente'];?>";
                $.ajax({
                    method: "POST",
                    url: "../code/pacientes.php",
                    data: { funcion: 'datos_paciente',id_paciente: idpaciente}
                }).done(function( respuesta ) {
                    const paciente = JSON.parse(respuesta)[0];
                    $("#imagen-paciente").attr('src','../portraits/' + paciente.imagen);
                    $("#tipo-documento").children('[value="'+paciente.tipo_documento_id+'"]').attr('selected', true);
                    $("#numero-documento").val(paciente.numero_documento);
                    $("#primer-nombre").val(paciente.nombre1);
                    $("#segundo-nombre").val(paciente.nombre2);
                    $("#primer-apellido").val(paciente.apellido1);
                    $("#segundo-apellido").val(paciente.apellido2);
                    $("#departamento").children('[value="'+paciente.departamento_id+'"]').attr('selected', true);
                    $("#genero").children('[value="'+paciente.genero_id+'"]').attr('selected', true);
                    buscarMunicipios(paciente.municipio_id);
                }).fail(function (error){
                    alert("Se produjo al llenar los datos");
                });
            }

            function buscarMunicipios(municipio = 1){
                $.ajax({
                    method: "POST",
                    url: "../code/pacientes.php",
                    data: { funcion: 'buscar_municipios', id_departamento: $("#departamento").val()}
                }).done(function( respuesta ) { //Por temas de carga se tiene que hacer el llenado de municios en esta sesion
                    const municipios = JSON.parse(respuesta);
                    $('#municipios').empty();
                    municipios.forEach(el => {$("#municipios").append("<option value='"+el.id+"'>"+el.nombre+"</option>");});
                    $("#municipios").children('[value="'+municipio+'"]').attr('selected', true);
                    $(".spinner").hide();
                    $("#listacampos").attr("disabled",false);
                    $(".btn-cancelar").attr("disabled",false);
                    $(".btn-actualizar").attr("disabled",false);
                }).fail(function (error){
                    alert("Se produjo al llenar los datos");
                });
            }

            $(".btn-actualizar").click(function(e){
                e.preventDefault();

                let frm = document.getElementById('formulario-actualizar-paciente');
                let frmData = new FormData(frm);

                $.ajax({
                    method: "POST",
                    url: "../code/pacientes.php",
                    data: frmData,
                    cache:false,
                    processData: false,
                    contentType: false
                }).done(function( respuesta ) {
                    if(respuesta != "[]"){
                        window.location.href = "editar_paciente.php?id_paciente=<?php echo $_GET['id_paciente'];?>&mensaje=" + encodeURIComponent(respuesta).replace('%20','+') + "&msgcolor=msgerror";
                    }else{
                        window.location.href = "editar_paciente.php?id_paciente=<?php echo $_GET['id_paciente'];?>&mensaje=" + encodeURIComponent("El paciente se actualizo correctamente").replace('%20','+') + "&msgcolor=msgsuccess";
                    }
                }).fail(function (error){
                    alert("Se produjo al actualizar los datos");
                });
            });

        </script>
    <?php endif; ?>
</body>
</html>