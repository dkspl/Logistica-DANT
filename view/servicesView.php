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
                <label class="w3-container">
                    <input type="radio" class="w3-radio" name="estado" value="finalizado">
                    <span>Finalizado</span>
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
                <div class="w3-center">
                    <button onclick="hideModalById('confirm')"
                            type="button" class="w3-button w3-section w3-padding w3-blue">Cancelar</button>
                    <button class="w3-button w3-red w3-section w3-padding" type="submit">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<h2>Lista de services</h2>
<div class="w3-responsive">
    <table class="w3-table w3-hoverable w3-striped">
        <tr class="w3-orange">
            <th>Inicio</th>
            <th>Fin</th>
            <th>Interno/Externo</th>
            <th>Acciones</th>
        </tr>
        {{#services}}
        <tr>
            <td>{{fechaInicio}}</td>
            <td>{{fechaFin}}
                {{^fechaFin}} En curso {{/fechaFin}}</td>
            <td>
            {{#intext}}
            Interno
            {{/intext}}
            {{^intext}}
            Externo
            {{/intext}}
            </td>
            <td>
                <a class="w3-button w3-orange" href="/Mecanico/service/codigo={{codigo}}">Detalle</a>
                {{^fechaFin}}
                <a class="w3-button w3-blue" href="/Mecanico/endingService/codigo={{codigo}}">Finalizar</a>
                <!--<button onclick="showModalById('confirm');addIdTo('{{codViaje}}', 'codigo')"
                        class='w3-button w3-red'>Cancelar</button>-->
                {{/fechaFin}}
            </td>
        </tr>
        {{/services}}
    </table>
</div>
{{> footer}}
