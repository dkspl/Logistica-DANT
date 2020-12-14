{{> header}}
<script src="/public/js/ubicaciones.js"></script>
<div class="w3-container">
    <h1 id="detalle">Detalles</h1>
</div>
{{#travel}}
<div class="w3-container w3-margin" id="info">
    <div class="w3-container w3-card-4 w3-padding-16 w3-white">

        <div class="w3-section">
            <span>Número de viaje:</span>
            <span class="w3-input">{{codViaje}}</span>
        </div>
        <div class="w3-section">
            <span>DNI del Chofer:</span>
            <span class="w3-input">{{chofer}}</span>
        </div>
        <div class="w3-section">
            <span>Origen:</span>
            <span class="w3-input">{{origen}}</span>
        </div>
        <div class="w3-section">
            <span>Destino:</span>
            <span class="w3-input">{{destino}}</span>
        </div>
        <div class="w3-section">
            <span>Fecha de carga:</span>
            <span class="w3-input">{{fcarga}}</span>
        </div>
        <!--<div class="w3-section">
            <span>Última actualización:</span>
            <span class="w3-input"></span>
        </div>-->
        <div class="w3-container w3-margin-16 w3-center">
            <a href="/" class="w3-button w3-border w3-border-green w3-text-green w3-white w3-padding">Volver a Inicio</a>
            <a onclick="changeViewById('divForm', 'block');changeViewById('divComb', 'none');
            changeAction('/chofer/actualizarViaje','formChofer');" href="#formChofer" class="w3-button w3-blue w3-padding">Actualizar</a>
            <a onclick="changeViewById('divComb', 'block');changeViewById('divForm','none');"
               href="#formComb" class="w3-button w3-orange w3-text-white w3-padding">Nueva carga de combustible</a>
            <a onclick="changeViewById('divForm', 'block');changeAction('/chofer/finalizarViaje','formChofer');
            changeViewById('divComb', 'none');" href="#formChofer" class="w3-button w3-red w3-padding">Finalizar</a>

        </div>
        {{/travel}}
    </div>
</div>
<div class="w3-container w3-margin" id="divForm" style="display:none">
    <form class="w3-container w3-card-4 w3-padding-16 w3-white" id="formChofer" method="post">
        <div class="w3-container w3-red">
            <h1 class="w3-container">Posición y gastos</h1>
        </div>
        {{#travel}}
        <div class="w3-container">
            <br>
            <input type="hidden" value="{{codViaje}}" name="codViaje">
            <input type="hidden" value="" name="latitud" id="lat">
            <input type="hidden" value="" name="longitud" id="long">
            <label class="w3-section">
                <span>Consumo de combustible (en litros):</span>
                <input type="number" class="w3-input" name="consumo">
            </label>
            <br>
            <label class="w3-section">
                <span>Gastos en viáticos (en pesos argentinos):</span>
                <input type="number" class="w3-input" name="viaticos">
            </label>
            <br>
            <label class="w3-section">
                <span>Gastos en peajes (en pesos argentinos):</span>
                <input type="number" class="w3-input" name="peajes">
            </label>
            <br>
            <label class="w3-section">
                <span>Gastos en pesajes (en pesos argentinos):</span>
                <input type="number" class="w3-input" name="pesajes">
            </label>
            <br>
            <label class="w3-section">
                <span>Gastos extras (en pesos argentinos):</span>
                <input type="number" class="w3-input" name="extras">
            </label>
        </div>
        <br>
        <div class="w3-container w3-margin-16 w3-center">
            <a onclick="changeViewById('divForm', 'none');" href="#info" class="w3-button w3-border w3-border-red
                w3-text-red w3-white">Cancelar</a>
            <button class="w3-button w3-red" type="submit">Aceptar</button>
        </div>
        {{/travel}}
    </form>
</div>
<div class="w3-container w3-margin" id="divComb" style="display:none">
    <form class="w3-container w3-card-4 w3-padding-16 w3-white" id="formComb" method="post" action="/Chofer/cargaCombustible">
        <div class="w3-container w3-orange w3-text-white">
            <h1 class="w3-container">Nueva carga de combustible</h1>
        </div>
        {{#travel}}
        <div class="w3-container">
            <br>
            <input type="hidden" value="{{codViaje}}" name="codViaje">
            <input type="hidden" value="" name="latitud" id="latComb">
            <input type="hidden" value="" name="longitud" id="longComb">
            <label class="w3-section">
                <span>Cantidad de combustible cargado:</span>
                <input type="number" class="w3-input" name="cantidad">
            </label>
            <br>
            <label class="w3-section">
                <span>Importe:</span>
                <input type="number" class="w3-input" name="importe">
            </label>
            <br>
            <label class="w3-section">
                <span>Combustible consumido desde última actualización (en litros):</span>
                <input type="number" class="w3-input" name="consumo">
            </label>
        </div>
        <br>
        <div class="w3-container w3-margin-16 w3-center">
            <a onclick="changeViewById('divComb', 'none');" href="#info" class="w3-button w3-border w3-border-red
                w3-text-red w3-white">Cancelar</a>
            <button class="w3-button w3-red" type="submit">Aceptar</button>
        </div>
        {{/travel}}
    </form>
</div>
<script>
    window.addEventListener("load", getLocation('lat','long'));
    window.addEventListener("load", getLocation('latComb','longComb'));
</script>
{{> footer}}