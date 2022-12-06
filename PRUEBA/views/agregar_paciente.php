<?php include("headers.php"); ?>
<body>
    <?php include("cabecera.html"); ?>

    <div class="contenido">

        <?php if (isset($_GET['mensaje'])): ?>
        <div class="w100 textcenter d-flex justify-content-center mb5 container-mensaje <?php echo $_GET['msgcolor']; ?>"><span class="mensaje"> <?= $_GET['mensaje'] ?><button     class="ml2" onclick="cerrarMensaje()">Cerrar Mensaje</button></span></div>
        <?php endif; ?>

        <div class="contenedor-registro-paciente mt5">
            <div class="spinner"></div>

            <fieldset id="listacampos" disabled>
                <form id="formulario-agregar-paciente">
                    <div class="bloque-contenido justify-content-center">
                        <img height="100" widht="150" class="imagen-paciente" id="imagen-paciente">
                        <label for="campo-imagen-paciente" class="lbl-imagen-paciente"><i class="fa fa-plus-square" aria-hidden="true"></i></label>
                        <input type="file" name="campo-imagen-paciente" id="campo-imagen-paciente" class="d-none" style="display: none;" accept="image/*">
                    </div>

                    <div class="bloque-contenido justify-content-center">
                        <div class="bloque-columna">
                            <label for="tipo-documento">Tipo de documento</label>
                            <select id="tipo-documento" name="tipo-documento"></select>
                        </div>
                        <div class="bloque-columna">
                            <label for="numero-documento">Numero de documento</label>
                            <input type="number" id="numero-documento" name="numero-documento" required>
                        </div>
                    </div>

                    <div class="bloque-contenido">
                        <div class="bloque-columna">
                            <label for="primer-nombre">Primer Nombre</label>
                            <input type="text" id="primer-nombre" name="primer-nombre" required>
                        </div>
                        <div class="bloque-columna">
                            <label for="segundo-nombre">Segundo Nombre</label>
                            <input type="text" id="segundo-nombre" name="segundo-nombre">
                        </div>
                    </div>

                    <div class="bloque-contenido">
                        <div class="bloque-columna">
                            <label for="primer-apellido">Primer Apellido</label>
                            <input type="text" id="primer-apellido" name="primer-apellido" required>
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
                        <a href="pacientes.php" class="btn btn-red btn-cancelar" disabled>Cancelar</a>
                        <a type="submit" class="btn btn-green btn-aceptar" disabled>Guardar</a>
                    </div>
                    <input type="hidden" name="funcion" value="crear">
                </form>
            </fieldset>

        </div>
    </div>
    
    <script>
        $("#titulo-pagina").html("Crear Pacientes");
        $("#titulo-nav").html("Creación de Nuevo Paciente");
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
                buscarMunicipios();
                $(".spinner").hide();
                $("#listacampos").attr("disabled",false);
                $(".btn-cancelar").attr("disabled",false);
                $(".btn-aceptar").attr("disabled",false);
            }).fail(function (error){
                alert("Se produjo al llenar los datos");
            });
        }

        function buscarMunicipios(){
            $.ajax({
                method: "POST",
                url: "../code/pacientes.php",
                data: { funcion: 'buscar_municipios', id_departamento: $("#departamento").val()}
            }).done(function( respuesta ) {
                const municipios = JSON.parse(respuesta);
                $('#municipios').empty();
                municipios.forEach(el => {$("#municipios").append("<option value='"+el.id+"'>"+el.nombre+"</option>");});
            }).fail(function (error){
                alert("Se produjo al llenar los datos");
            });
        }

        $(".btn-aceptar").click(function(e){
            e.preventDefault();

            let frm = document.getElementById('formulario-agregar-paciente');
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
                    window.location.href = "agregar_paciente.php?mensaje=" + encodeURIComponent(respuesta).replace('%20','+') + "&msgcolor=msgerror";
                }else{
                    window.location.href = "pacientes.php?mensaje=" + encodeURIComponent("Se creo el paciente correctamente").replace('%20','+') + "&msgcolor=msgsuccess";
                }
            }).fail(function (error){
                alert("Se produjo al llenar los datos");
            });
        });
    </script>
</body>
</html>