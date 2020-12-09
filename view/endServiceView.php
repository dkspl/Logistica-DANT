{{> header}}

<div class="w3-container w3-margin">
    <form class="w3-container w3-card-4 w3-padding-16 w3-white" action="/Mecanico/endService" method="post">
        {{#service}}
        <input type="hidden" value="{{codigo}}" name="codigo">
        <input type="hidden" value="{{vehiculo}}" name="vehiculo">
        <div class="w3-section">
            <span>Código:</span>
            <span class="w3-input">{{codigo}}</span>
        </div>
        <div class="w3-section">
            <span>Fecha de inicio:</span>
            <span class="w3-input">{{fechaInicio}}</span>
        </div>
        <div class="w3-section">
            <span>Patente del vehículo:</span>
            <span class="w3-input">{{vehiculo}}</span>
        </div>
        <div class="w3-section">
            <span>DNI de mecánico:</span>
            <span class="w3-input">{{mecanico}}</span>
        </div>
        <div class="w3-section">
            <label>Costo:
                <input class="w3-input" type="number" name="costo" required>
            </label>
        </div>
        <div class="w3-section">
            <label>Observaciones: </label>
            <textarea class="w3-input" style="resize:none" name="observaciones"></textarea>
        </div>
        <div class="w3-center">
            <a class="w3-button w3-section w3-padding w3-border w3-border-green w3-white" href="/Mecanico/myServices">Volver</a>
            <button class="w3-button w3-green w3-section w3-padding" type="submit">Aceptar</button>
        </div>
        {{/service}}
    </form>
</div>

{{> footer}}
