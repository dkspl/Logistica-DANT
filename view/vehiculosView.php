{{> header }}
<div id="confirm" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="document.getElementById('confirm').style.display='none'"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <form class="w3-container" action="/Administrador/eliminarVehiculo" method="post">
            <div>
                <p><strong>¿Está seguro de que desea eliminar el vehículo? Esta acción es irreversible.</strong></p>
                <input type="hidden" id="patdel" name="patente">
                <div class="w3-center">
                    <button onclick="document.getElementById('confirm').style.display='none'"
                            type="button" class="w3-button w3-section w3-padding w3-blue">Cancelar</button>
                    <button class="w3-button w3-red w3-section w3-padding" type="submit">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<h2>Lista de vehículos</h2>
<div class="w3-responsive">
    <table class="w3-table w3-hoverable w3-striped">
        <tr class="w3-blue">
            <th>Patente</th>
            <th>Chasis</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Fecha service</th>
            {{#permissions}}
            <th>Acciones</th>
            {{/permissions}}
        </tr>
        {{#vehiculos}}
        <tr>
            <td>{{patente}}</td>
            <td>{{nroChasis}}</td>
            <td>{{marca}}</td>
            <td>{{modelo}}</td>
            <td>{{tipo}}</td>
            <td>
                {{#estado}}
                Disponible
                {{/estado}}
                {{^estado}}
                No disponible
                {{/estado}}
            </td>
            <td>{{fechaService}}</td>
            <td>
                {{#permissions}}
                <a class="w3-button w3-blue" href="/Usuario/vehicle/id={{patente}}">Detalle</a>
                {{/permissions}}
                {{#admin}}
                <a class="w3-button w3-green" href="/Administrador/modificarVehiculo/id={{patente}}">Modificar</a>
                <button onclick="document.getElementById('confirm').style.display='block';addIDtoVehicle('{{patente}}', 'patdel')"
                        class='w3-button w3-red'>Eliminar</button>
                {{/admin}}
            </td>
        </tr>
        {{/vehiculos}}
    </table>
    <div class="w3-margin-16 w3-center">
        <a href="/" class="w3-button w3-border w3-border-green w3-white">Volver a Inicio</a>
        {{#admin}}
        <a href="/Administrador/addFormVehicle" class="w3-button w3-red">Agregar vehículo</a>
        {{/admin}}
    </div>
</div>
<script>
    function addIDtoVehicle(patente, id) {
        document.getElementById(id).value=patente;
    };
</script>
{{> footer }}
