{{> header}}
{{#vehiculo}}
<div class="w3-container w3-margin">
    <h1>Modificar vehículo: {{patente}}</h1>
</div>
<div class="w3-container w3-margin">
    <form class="w3-container w3-card-4 w3-padding-16 w3-white" action="/Administrador/editVehicle" method="post">
        <div class="w3-container w3-blue">
            <h1 class="w3-container">Datos generales</h1>
        </div>
        <div class="w3-container">
            <br>
            <input type="hidden" name="codVehiculo" value="{{codVehiculo}}">
            <label class="w3-section">
                <span>Patente:</span>
                <span class="w3-input">{{patente}}</span>
            </label>

            <br>
            <label class="w3-section">
                <span>Número de chasis:</span>
                <input type="number" class="w3-input" name="nroChasis" value="{{nroChasis}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Marca:</span>
                <input type="text" class="w3-input" name="marca" value="{{marca}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Modelo:</span>
                <input type="text" class="w3-input" name="modelo" value="{{modelo}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Kilómetros totales:</span>
                <input type="number" class="w3-input" name="kmTotales" value="{{kmTotales}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Año de fabricación:</span>
                <input type="number" class="w3-input" name="anoFabricacion" value="{{anoFabricacion}}">
            </label>
        </div>
        <br>
        <div class="w3-container w3-margin-16 w3-center">
            <button class="w3-button w3-blue" type="submit">Aceptar</button>
        </div>

    </form>
</div>
<div class="w3-container w3-margin">
    <form class="w3-container w3-card-4 w3-padding-16 w3-white" action="/Administrador/editSpecificVehicle" method="post">
        <div class="w3-container w3-blue">
            <h1 class="w3-container">Datos específicos</h1>
        </div>
        <div class="w3-container">

            <input type="hidden" name="codVehiculo" value="{{codVehiculo}}">
            <input type="hidden" name="tipo" value="{{tipo}}">
                   {{/vehiculo}}
            <br>
            {{#tipoVehiculo}}
            {{#tractorArrastrado}}
            <label class="w3-section">
                <span>Número de motor:</span>
                <input type="number" class="w3-input" name="nroMotor" value="{{nroMotor}}">
            </label>

            <br>
            <label class="w3-section">
                <span>Consumo estimado (en litros cada 100 km):</span>
                <input type="number" class="w3-input" name="consumo" value="{{consumo}}">
            </label>
            {{/tractorArrastrado}}
            {{^tractorArrastrado}}
            <label class="w3-section">
                <span>Tipo de carga:</span>
                <input type="text" class="w3-input" name="tipoCarga" value="{{tipoCarga}}">
            </label>
            {{/tractorArrastrado}}
            {{/tipoVehiculo}}
        </div>
        <br>
        <div class="w3-container w3-margin-16 w3-center">
            <button class="w3-button w3-blue" type="submit">Aceptar</button>
        </div>
    </form>
</div>
<div class="w3-container w3-margin w3-center">
    <a href="/Administrador/vehiculos" class="w3-button w3-border w3-border-blue w3-text-blue w3-white">Volver</a>
</div>
{{> footer}}