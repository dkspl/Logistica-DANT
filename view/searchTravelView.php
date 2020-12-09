{{> header}}
<div class="w3-container w3-blue w3-text-white">
    <h1>Acceder a mi viaje</h1>
</div>
<div class="w3-container w3-margin-16">
    <h3>Use el código QR para ver o actualizar su viaje</h3>
</div>
<div class="w3-row w3-padding w3-margin-bottom">
    <div class="w3-half w3-center">
        <div class="w3-container w3-green">
            <h4 class="w3-center"><b>Opción 1:</b> Escanear desde la cámara</h4>
        </div>
        <div id="reader" class="w3-container w3-center">

        </div>
        <form action="/Chofer/decodedQr" id="formQr" method="post">
            <input type="hidden" id="scanQr" value="" name="decodedQr">
        </form>
    </div>
    <div class="w3-half w3-center">
        <div class="w3-container w3-blue">
            <h4 class="w3-center"><b>Opción 2:</b> Subir imagen</h4>
        </div>
        <form method="post" action="/Chofer/decodeQr" enctype="multipart/form-data" class="w3-container" name="formulario">
            <div class="w3-center">
                <label class="w3-container" id="imageLabel">
                    <div class="w3-center"><i class="fa fa-picture-o" aria-hidden="true"></i></div>
                    <div class="w3-clear"></div>
                    <h4 class="w3-center">Haga click acá para subir una imagen</h4>
                    <input class="w3-button" type="file" name="file" id="imageForm">
                </label>
                <button class="w3-button w3-block w3-blue w3-section" type="submit" id="cargaImg">Cargar imagen</button>
            </div>
        </form>
    </div>

</div>
<script>
    $(document).ready(function () {
        $('#reader').html5_qrcode(function (data) {
                $('#scanQr').val(data);
                $('#formQr').submit();
            },
            function (error) {

            }, function (videoError) {
                alert("Necesitamos una cámara para poder escanear el código QR");
            }
        );
    });
</script>
{{>footer}}