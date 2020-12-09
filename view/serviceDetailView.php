{{> header}}

<div class="w3-container w3-margin">
    <div class="w3-container w3-card-4 w3-padding-16 w3-white" action="/Mecanico/endService" method="post">
        {{#service}}
        <input type="hidden" value="{{codigo}}" name="codigo">
        <div class="w3-section">
            <span>Código:</span>
            <span class="w3-input">{{codigo}}</span>
        </div>
        <div class="w3-section">
            <span>Fecha de inicio:</span>
            <span class="w3-input">{{fechaInicio}}</span>
        </div>
        <div class="w3-section">
            <span>Fecha de finalización:</span>
            <span class="w3-input">{{fechaFin}} {{^fechaFin}} - {{/fechaFin}}</span>
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
            <span>Costo:</span>
            <span class="w3-input">{{costo}} {{^costo}} - {{/costo}}</span>
        </div>
        <div class="w3-section">
            <span>Observaciones:</span>
            <span class="w3-input">{{observaciones}} {{^observaciones}} Sin información {{/observaciones}}</span>
        </div>
        <div class="w3-center">
            <a class="w3-button w3-section w3-padding w3-border w3-border-green w3-white" href="/Mecanico/myServices">Volver</a>
            {{^fechaFin}}
            <a class="w3-button w3-section w3-padding w3-orange" href="/Mecanico/endingService/codigo={{codigo}}">Finalizar</a>
            {{/fechaFin}}
        </div>
        {{/service}}
    </div>
</div>
{{> footer}}
