{{> header }}
<div id="asignar" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
            <span onclick="hideModalById('asignar')"
                  class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
            <form class="w3-container" action="/administrador/editRol" method="post">
                <div class="w3-center">
                    <input type="hidden" id="dni" name="dni">
                    <p><strong>¿Está seguro de que desea otorgarle rol a este usuario?</strong></p>
                    <button onclick="hideModalById('asignar')"
                            type="button" class="w3-button w3-section w3-padding w3-blue">Cancelar</button>
                    <button class="w3-button w3-red w3-section w3-padding" type="submit">Aceptar</button>
                </div>
            </form>
    </div>
</div>
<div id="confirm" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="hideModalById('confirm')"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <form class="w3-container" action="/administrador/deleteUser" method="post">
            <div>
                <p><strong>¿Está seguro de que desea eliminar el usuario? Esta acción es irreversible.</strong></p>
                <input type="hidden" id="deldni" name="dni">
                <div class="w3-center">
                    <button onclick="hideModalById('confirm')"
                            type="button" class="w3-button w3-section w3-padding w3-blue">Cancelar</button>
                    <button class="w3-button w3-red w3-section w3-padding" type="submit">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<h2>Lista de usuarios</h2>
<div class="w3-responsive">
    <table class="w3-table w3-hoverable w3-striped">
        <tr class="w3-orange">
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>E-mail</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        {{#usuarios}}
        <tr>
            <td>{{dni}}</td>
            <td>{{apellido}}</td>
            <td>{{nombre}}</td>
            <td>{{email}}</td>
            <td>
                {{rol}}
            </td>
            <td>
                <a class="w3-button w3-blue" href="/Administrador/editarEmpleado/id={{dni}}">Modificar</a>
                <button onclick="showModalById('confirm');addIdTo('{{dni}}', 'deldni')" class='w3-button w3-red'>Eliminar</button>
                {{#validarRol}}
                <button onclick="showModalById('asignar');addIdTo('{{dni}}', 'dni')" class='w3-button w3-green'>Asignar</button>
                {{/validarRol}}
            </td>
        </tr>
        {{/usuarios}}
    </table>
</div>
<div class="w3-margin-16 w3-center">
    <a href="/" class="w3-bar-item w3-button w3-padding-large w3-green">Volver a Inicio</a>
</div>
{{> footer }}
