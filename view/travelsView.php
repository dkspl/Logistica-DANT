{{> header}}
<div id="stat" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="hideModalById('stat')"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <form class="w3-container" action="/supervisor/updateTravel" method="post">
            <div>
                <input type="hidden" id="codViaje" name="codViaje">
                <p><strong>Modificar estado de viaje</strong></p>
                <label class="w3-container">
                    <input type="radio" class="w3-radio" name="estado" value="en carga">
                    <span>En carga</span>
                </label>
                <label class="w3-container">
                    <input type="radio" class="w3-radio" name="estado" value="en curso">
                    <span>En curso</span>
                </label>
                <button class="w3-button w3-block w3-orange w3-section w3-padding" type="submit">Aceptar</button>
            </div>
        </form>
    </div>
</div>
<div id="confirm" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="hideModalById('confirm')"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <form class="w3-container" action="/Supervisor/cancelTravel" method="post">
            <div>
                <p><strong>¿Está seguro de que desea cancelar el viaje?</strong></p>
                <input type="hidden" id="codigo" name="codViaje">
                <input type="hidden" id="tractor" name="tractor">
                <input type="hidden" id="chofer" name="chofer">
                <input type="hidden" id="arrastrado" name="arrastrado">
                <div class="w3-center">
                    <button onclick="hideModalById('confirm')"
                            type="button" class="w3-button w3-section w3-padding w3-blue">Cancelar</button>
                    <button class="w3-button w3-red w3-section w3-padding" type="submit">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{#viaje}}
<div id="proforma" class="w3-modal" style="display: block;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="document.getElementById('proforma').style.display='none'"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <div class="w3-container w3-padding-16">
            <p><strong>El viaje se ha creado correctamente. Presione el botón para ver y descargar la proforma.</strong></p>
            <div class="w3-center">
                <a class="w3-button w3-orange w3-text-white" target="_blank" href="/Supervisor/travel/id={{viaje}}">Ver proforma</a>
            </div>
        </div>
    </div>
</div>
{{/viaje}}
<h2>Lista de viajes</h2>
<div class="w3-responsive">
    <table class="w3-table w3-hoverable w3-striped">
        <tr class="w3-orange">
            <th>Codigo</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Estado</th>
            <th>Fecha de llegada</th>
            {{#permissions}}
            <th>Acciones</th>
            {{/permissions}}
        </tr>
        {{#viajes}}
        <tr>
            <td>{{codViaje}}</td>
            <td style="max-width:200px">{{origen}}</td>
            <td style="max-width:200px">{{destino}}</td>
            <td>{{estado}}</td>
            {{#fllegada}}
            <td>{{fllegada}}</td>
            {{/fllegada}}
            {{^fllegada}}
            <td>-</td>
            {{/fllegada}}
            <td>
                {{#permissions}}
                <a class="w3-bar-item w3-button w3-orange w3-text-white" target="_blank" href="/Supervisor/travel/id={{codViaje}}">Ver proforma</a>
                {{#fllegada}}
                <a class="w3-bar-item w3-button w3-yellow" target="_blank" href="/Supervisor/factura/id={{codViaje}}">Ver factura</a>
                {{/fllegada}}
                {{#isModifiable}}
                <button onclick="showModalById('stat');addIdTo('{{codViaje}}', 'codViaje')"
                        class='w3-bar-item w3-button w3-blue'>Actualizar</button>
                <button onclick="showModalById('confirm');addIdTo('{{codViaje}}', 'codigo')
                    ;addIdTo('{{tractor}}', 'tractor');addIdTo('{{chofer}}', 'chofer');
                    addIdTo('{{arrastrado}}', 'arrastrado');"
                        class='w3-bar-item w3-button w3-red'>Cancelar</button>
                {{/isModifiable}}
                {{/permissions}}
            </td>
        </tr>
        {{/viajes}}
    </table>
</div>
{{> footer}}