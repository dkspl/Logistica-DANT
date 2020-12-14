{{> header}}
<div class="w3-container">
    <h1>Iniciar service</h1>
</div>
<div class="w3-container w3-margin">
    <form method="post" action="/Mecanico/setService">
        <input type="hidden" name="dni" value="{{user}}">
        <label class="w3-container">
            <span>Vehiculo (en rojo, necesitan service): </span>
            <select name="vehiculo">
                <option value="" disabled selected>Elija el vehículo</option>
                {{#vehiculos}}
                <option value="{{codVehiculo}}">{{patente}} - {{marca}} {{modelo}}</option>
                {{#necesitaService}}
                <script>
                    $(document).ready(function(){
                        $("[value={{codVehiculo}}]").css("background-color", "#f5b0a1");
                    });
                </script>
                {{/necesitaService}}
                {{/vehiculos}}
            </select>
        </label>
        <label class="w3-container">
            <span>Procedencia del service: </span>
            <label class="w3-margin">
                <input type="radio" name="intext" value="true"><span>Interno</span>
            </label>
            <label class="w3-margin">
                <input type="radio" name="intext" value="false"><span>Externo</span>
            </label>
        </label>
        <div class="w3-center">
            <a class="w3-button w3-section w3-padding w3-border w3-border-orange w3-white" href="/">Volver a Inicio</a>
            <button class="w3-button w3-orange w3-section w3-padding" type="submit">Aceptar</button>
        </div>
    </form>
</div>

{{> footer}}