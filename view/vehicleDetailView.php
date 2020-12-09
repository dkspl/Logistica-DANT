{{> header}}
{{#vehiculo}}
<script src="/public/js/ubicaciones.js"></script>
<div class="w3-container w3-margin">
    <h1>Vehículo: {{patente}}</h1>
</div>
<div class="w3-container w3-margin">
    <div class="w3-container w3-card-4 w3-padding-16 w3-white">
        <div>
            <label class="w3-container">Patente:
                <span class="w3-input w3-border">{{patente}}</span>
            </label>
            <label class="w3-container">Estado:
                <span class="w3-input w3-border">
                    {{#estado}}
                    Disponible
                    {{/estado}}
                    {{^estado}}
                    No disponible
                    {{/estado}}
                </span>
            </label>
            <label class="w3-container">Número de chasis:
                <span class="w3-input w3-border">{{nroChasis}}</span>
            </label>
            <label class="w3-container">Marca:
                <span class="w3-input w3-border">{{marca}}</span>
            </label>
            <label class="w3-container">Modelo:
                <span class="w3-input w3-border">{{modelo}}</span>
            </label>
            <label class="w3-container">Kilómetros totales:
                <span class="w3-input w3-border">{{kmTotales}}</span>
            </label>
            <label class="w3-container">Año de fabricación:
                <span class="w3-input w3-border">{{anoFabricacion}}</span>
            </label>
            <label class="w3-container">Fecha de próximo service:
                <span class="w3-input w3-border">{{fechaService}}</span>
            </label>
            {{#tipoVehiculo}}
                {{#tractorArrastrado}}
            <label class="w3-container">Número de motor:
                <span class="w3-input w3-border">{{nroMotor}}</span>
            </label>
            <label class="w3-container">Consumo estimado (en litros cada 100 km):
                <span class="w3-input w3-border">{{consumo}}</span>
            </label>
                {{/tractorArrastrado}}
                {{^tractorArrastrado}}
            <label class="w3-container">Tipo de carga:
                <span class="w3-input w3-border">{{tipoCarga}}</span>
            </label>
                {{/tractorArrastrado}}
            {{/tipoVehiculo}}
            {{#ubicacion}}
            <label class="w3-container">Última ubicación:
                <div class="mapWrapper w3-border">
                    <div id="mapLast" class="w3-container" style="display: block;height: 300px">
                    </div>
                    <script>
                        showMapWithMarkerFrom('mapLast', '{{patente}}','{{latitud}}','{{longitud}}','{{fecha}}');
                    </script>
                </div>
            </label>
            {{/ubicacion}}
        </div>
    </div>
</div>
{{/vehiculo}}


{{> footer}}