{{> header }}
<div class="w3-container">
    <h1> Carga de vehículo</h1>
</div>
<div class="w3-container w3-margin">
    <form method="post" action="/Administrador/setVehicle" class="w3-container w3-card-4 w3-padding-16 w3-white">
        <div class="w3-container" id="formContainer">
            <label class="w3-container">
                <span class="w3-block">Patente: </span>
                <input class="w3-input w3-border" type="text" name="patente">
            </label>
            <label class="w3-container">
                <span class="w3-block">Número de chasis: </span>
                <input class="w3-input w3-border" type="number" name="nroChasis">
            </label>
            <label class="w3-container">
                <span class="w3-block">Marca: </span>
                <input class="w3-input w3-border" type="text" name="marca">
            </label>
            <label class="w3-container">
                <span class="w3-block">Modelo: </span>
                <input class="w3-input w3-border" type="text" name="modelo">
            </label>
            <label class="w3-container">
                <span>Tipo: </span>
                <select class="w3-input w3-border w3-margin-bottom" name="tipo" id="tipoSelect">
                    <option value="" disabled selected>Elija el tipo de vehículo</option>
                    <option value="1">Arrastrado</option>
                    <option value="2">Tractor</option>
                </select>
            </label>
            <label class="w3-container">
                <span class="w3-block">Kilómetros totales: </span>
                <input class="w3-input w3-border" type="number" name="kmTotales">
            </label>
            <label class="w3-container">
                <span class="w3-block">Año de fabricación: </span>
                <input class="w3-input w3-border" type="number" name="anoFabricacion">
            </label>
            <label class="w3-container">
                <span class="w3-block">Fecha de próximo service: </span>
                <input class="w3-input w3-border" type="date" name="fechaService">
            </label>
        </div>
        <div class="w3-center">
            <a class="w3-button w3-section w3-padding w3-border w3-border-red w3-text-red w3-white" href="/">Volver a Inicio</a>
            <button class="w3-button w3-red w3-section w3-padding" type="submit">Aceptar</button>
        </div>
    </form>
</div>
<script>
    $("#tipoSelect").change(function(){
        if(this.value == 1){
            $("label").remove("#nroMotor");
            $("label").remove("#consumo");
            $( "#formContainer" ).append( "<label class='w3-container' id='tipoCarga' >\n" +
                "                <span class='w3-block'>Tipo de carga: </span>\n" +
                "                <input class='w3-input w3-border' type='text' name='tipoCarga'>\n" +
                "            </label>" );
        }
        else{
            $("label").remove("#tipoCarga");
            $( "#formContainer" ).append( "<label class='w3-container' id='nroMotor' >\n" +
                "                <span class='w3-block'>Número de motor: </span>\n" +
                "                <input class='w3-input w3-border' type='number' name='nroMotor'>\n" +
                "            </label>" );
            $( "#formContainer" ).append( "<label class='w3-container' id='consumo' >\n" +
                "                <span class='w3-block'>Consumo promedio (en litros por cada 100 km): </span>\n" +
                "                <input class='w3-input w3-border' type='number' name='consumo'>\n" +
                "            </label>" );
        }
    })
    function myFunction(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace("w3-show", "");
        }
    }

</script>
{{> footer }}
