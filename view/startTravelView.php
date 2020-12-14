{{> header}}
<div class="w3-container">
    <h1> Carga de viaje</h1>
</div>
<div class="w3-container w3-margin">
    <form method="post" action="/Supervisor/setTravel" class="w3-container w3-card-4 w3-padding-16 w3-white">
        <div>
            <button type="button" onclick="myFunction('datosViaje')" class="w3-button w3-red w3-block w3-left-align w3-border">
                Datos del viaje</button>
            <div id="datosViaje" class="w3-container w3-hide w3-show" style="height: 100%;width=100%">
                <label class="w3-container">Origen:
                    <input type="text" class="w3-input w3-border w3-margin-bottom"
                           placeholder="Presione aquí para buscar el lugar de origen"
                           name="textOrigen" id="textOr" readonly="readonly"
                           onclick="changeViewById('mapOrigen', 'block');changeViewById('buttonOr','block');
                           showMapFrom('mapOrigen', 'latOr', 'longOr', 'textOr')"/>
                    <div class="mapWrapper">
                        <div id="mapOrigen" class="w3-container" style="display: none;height: 300px">
                        </div>
                    </div>
                    <button type="button" id="buttonOr" class="w3-button w3-red w3-section w3-padding"
                            onclick="changeViewById('mapOrigen', 'none');changeViewById('buttonOr', 'none');" style="display:none">Aceptar</button>
                    <input type="hidden"  name="latOrigen" id="latOr" value="0"/>
                    <input type="hidden"  name="longOrigen" id="longOr" value="0"/>
                </label>
                <label class="w3-container">Destino:
                    <input type="text" class="w3-input w3-border w3-margin-bottom"
                           name="textDestino" id="textDes" readonly="readonly"
                           placeholder="Presione aquí para buscar el lugar de destino"
                           onclick="changeViewById('mapDestino', 'block');changeViewById('buttonDes','block');
                           showMapFrom('mapDestino', 'latDes', 'longDes', 'textDes')"/>
                    <div class="mapWrapper">
                        <div id="mapDestino" class="w3-container" style="display: none;height: 300px">
                        </div>
                    </div>
                    <button type="button" id="buttonDes" class="w3-button w3-red w3-section w3-padding"
                            onclick="changeViewById('mapDestino', 'none');changeViewById('buttonDes', 'none');" style="display:none">Aceptar</button>
                    <input type="hidden"  name="latDestino" id="latDes" value="0"/>
                    <input type="hidden"  name="longDestino" id="longDes" value="0"/>
                </label>

                <label class="w3-container">
                    <span>ETA: </span>
                    <input class="w3-input w3-border" type="datetime-local" name="eta">
                </label>
                <label class="w3-container">
                    <span class="w3-block">ETD: </span>
                    <input class="w3-input w3-border" type="datetime-local" name="etd">
                </label>
                <label class="w3-container">
                    <span class="w3-block">Chofer: </span>
                    <select class="w3-select w3-border" name="chofer">
                        <option value="" disabled selected>Elija el chofer</option>
                        {{#choferes}}
                        <option value="{{dni}}">{{dni}} - {{apellido}}, {{nombre}}</option>
                        {{/choferes}}
                    </select>
                </label>
                <label class="w3-container">
                    <span class="w3-block">Tractor: </span>
                    <select class="w3-select w3-border" name="tractor">
                        <option value="" disabled selected>Elija el tractor</option>
                        {{#tractores}}
                        <option value="{{codVehiculo}}">{{patente}} - {{marca}} {{modelo}}</option>
                        {{/tractores}}
                    </select>
                </label>
            </div>
        </div>
        <div>
            <button type="button" onclick="myFunction('datosCliente')" class="w3-button w3-red w3-block w3-left-align w3-border">
                Datos del cliente</button>
            <div id="datosCliente" class="w3-container w3-hide">
                    <label class="w3-container">
                        <span class="w3-block">CUIT: </span>
                        <input class="w3-input w3-border" type="text" name="cuit">
                    </label>
                    <label class="w3-container">
                        <span class="w3-block">Denominación: </span>
                        <input class="w3-input w3-border" type="text" name="denominacion">
                    </label>
                    <label class="w3-container">
                        <span class="w3-block">Dirección: </span>
                        <input class="w3-input w3-border" type="text" name="direccion">
                    </label>
                    <label class="w3-container">
                        <span class="w3-block">Telefono: </span>
                        <input class="w3-input w3-border" type="number" name="telefono">
                    </label>
                    <label class="w3-container">
                        <span class="w3-block">E-mail: </span>
                        <input class="w3-input w3-border" type="email" name="email">
                    </label>
                    <label class="w3-container">
                        <span class="w3-block">Contacto 1: </span>
                        <input class="w3-input w3-border" type="number" name="contacto1">
                    </label>
                    <label class="w3-container">
                        <span class="w3-block">Contacto 2: </span>
                        <input class="w3-input w3-border" type="number" name="contacto2">
                    </label>
            </div>
        </div>
        <div>
            <button type="button" onclick="myFunction('datosCarga')" class="w3-button w3-red w3-block w3-left-align w3-border">
                Datos de la carga</button>
            <div id="datosCarga" class="w3-container w3-hide">
                <label class="w3-container">
                    <span class="w3-block">Peso neto: </span>
                    <input class="w3-input w3-border" type="number" name="pesoNeto">
                </label>
                <div id="hazardRadio">
                    <label class="w3-container">
                        <span class="w3-block">Hazard: </span>
                        <label class="w3-margin">
                            <input class="w3-radio" type="radio" name="hazard" value="1"><span>Sí</span>
                        </label>
                        <label class="w3-margin">
                            <input class="w3-radio" type="radio" name="hazard" value="0"><span>No</span>
                        </label>
                    </label>
                </div>
                <div id="reeferRadio">
                    <label class="w3-container">
                        <span class="w3-block">Reefer: </span>
                        <label class="w3-margin">
                            <input class="w3-radio" type="radio" name="reefer" value="1"><span>Sí</span>
                        </label>
                        <label class="w3-margin">
                            <input class="w3-radio" type="radio" name="reefer" value="0"><span>No</span>
                        </label>
                    </label>
                </div>
                <label class="w3-container">
                    <span>Arrastrado: </span>
                    <select class="w3-select w3-border"name="arrastrado">
                        <option value="" disabled selected>Elija el arrastrado</option>
                        {{#arrastrados}}
                        <option value="{{codVehiculo}}">{{patente}} - {{marca}} {{modelo}}</option>
                        {{/arrastrados}}
                    </select>
                </label>
            </div>
        </div>

        <div class="w3-center">
            <a class="w3-button w3-section w3-padding w3-green" href="/">Volver a Inicio</a>
            <button class="w3-button w3-green w3-section w3-padding" type="submit" id="enviar">Aceptar</button>
        </div>
    </form>
</div>
<script src="/public/js/ubicaciones.js"></script>
<script>

    $("input[name='reefer']" ).change(function() {
        if ($(this).val() == 1) {
            $( "#reeferRadio" ).append( "<label class='w3-container' id='temp' >\n" +
                "                <span class='w3-block'>Temperatura: </span>\n" +
                "                <input class='w3-input w3-border' type='number' name='temperatura'>\n" +
                "            </label>" );
        }
        else {
            $("label").remove("#temp");
        }
    });

    $(document).on("click", "input[name='hazard']", function(){
        $("label").remove("#imoC");
        $("label").remove("#imoS");
        if ($(this).val() == 1) {
            $( "#hazardRadio" ).append("<label id='imoC' class='w3-container'>\n" +
                "<span class='w3-block'>IMO Class: </span>\n" +
                "<select class='w3-select w3-border' name='imoClass' id='imoClass'>\n" +
                "<option value='' disabled selected>Elija la clase IMO</option>\n" +
                "<option value='1'>Clase 1. Explosivos</option>\n" +
                "<option value='2'>Clase 2. Gases</option>\n" +
                "<option value='3'>Clase 3. Líquidos inflamables</option>\n" +
                "<option value='4'>Clase 4. Sólidos inflamables</option>\n" +
                "<option value='5'>Clase 5. Comburentes y peróxidos orgánicos</option>\n" +
                "<option value='6'>Clase 6. Tóxicos</option>\n" +
                "<option value='7'>Clase 7. Material radioactivo</option>\n" +
                "<option value='8'>Clase 8. Corrosivos</option>\n" +
                "<option value='9'>Clase 9. Objetos peligrosos diversos</option>\n" +
                "</select>\n" +
                "</label>");
            $("#imoClass").append("<span>algo</span>");
        }
        else {
            $("label").remove("#imoC");
            $("label").remove("#imoS");
        }
    });
    $(document).on("change","#imoClass", function(){
        if($("#imoClass").val() != ''){
            getImoSubclass($("#imoClass").val());
        }
    });

    function myFunction(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace("w3-show", "");
        }
    }
</script>
{{> footer}}

