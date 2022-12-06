<?php include("headers.php"); ?>
<body>
    <?php include("cabecera.html"); ?>

    <?php if (isset($_GET['mensaje'])): ?>
    <div class="w100 textcenter d-flex justify-content-center mb5 container-mensaje <?php echo $_GET['msgcolor']; ?>"><span class="mensaje"> <?= $_GET['mensaje'] ?><button     class="ml2" onclick="cerrarMensaje()">Cerrar Mensaje</button></span></div>
    <?php endif; ?>

    <div class="contenido">
        
        <table id="tabla-pacientes">
            <a href="agregar_paciente.php" class="btn btn-green mb2">Añadir Paciente</a>
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Tipo de documento</th>
                    <th>Documento</th>
                    <th>Primer Nombre</th>
                    <th>Segundo Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Genero</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    
            </tbody>
        </table>

    </div>
    
    <script>
        $("#titulo-pagina").html("Pacientes");
        $("#titulo-nav").html("Listado de pacientes");
        listarPacientes();

        function listarPacientes(){
            $.ajax({
                method: "POST",
                url: "../code/pacientes.php",
                data: { funcion: 'listar' }
            }).done(function( respuesta ) {
                const datos_pacientes = JSON.parse(respuesta);
                datos_pacientes.forEach(el => {
                    $("#tabla-pacientes").append("<tr>"+
                        "<td><img src='../portraits/"+el.imagen+"' width='50' height='50'></td>"+
                        "<td>"+el.id+"</td>"+
                        "<td>"+el.tipo_documento+"</td>"+
                        "<td>"+el.documento+"</td>"+
                        "<td>"+el.nombre1+"</td>"+
                        "<td>"+(el.nombre2 ? el.nombre2 : "")+"</td>"+
                        "<td>"+el.apellido1+"</td>"+
                        "<td>"+(el.apellido2 ? el.apellido2 : "")+"</td>"+
                        "<td>"+el.genero+"</td>"+
                        "<td>"+el.departamento+"</td>"+
                        "<td>"+el.municipio+"</td>"+
                        "<td class='d-flex'><a href='editar_paciente.php?id_paciente="+el.id+"' class='btn btn-blue' title='Editar'><i class='fa fa-pencil'></i></a><a class='btn btn-red btn-eliminar' id_paciente='"+el.id+"' title='Eliminar'><i class='fa fa-ban'></i></a></td>"+
                    "</tr>");
                });
            }).fail(function (error){
                alert("Se produjo un error al consultar la tabla");
            });
        }

        $("#tabla-pacientes").on('click','.btn-eliminar',function(){
            if(confirm('¿Está seguro que desea eliminar este paciente? Está acción no es reversible')){
                $.ajax({
                    method: "POST",
                    url: "../code/pacientes.php",
                    data: { funcion: 'eliminar', id_paciente: this.attributes.id_paciente.value}
                }).done(function( respuesta ) {
                    window.location.href = "pacientes.php?mensaje=" + encodeURIComponent("Paciente eliminado correctamente").replace('%20','+');
                }).fail(function (error){
                    alert("Se produjo un error al consultar la tabla");
                });
            }
        });

    </script>
</body>
</html>