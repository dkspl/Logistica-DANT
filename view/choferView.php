{{> header}}
{{#factura}}
<div id="factura" class="w3-modal" style="display: block;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="document.getElementById('factura').style.display='none'"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <div class="w3-container w3-padding-16">
            <p><strong>El viaje ha finalizado correctamente. ¡Buen trabajo!</strong></p>
        </div>
    </div>
</div>
{{/factura}}
<div class="w3-row-padding w3-padding-16">
    <div class="w3-container">
        <!--
        <div class="w3-container">
            <h2>Mis notificaciones</h2>
        </div>
            {{#buzon}}
                    <div class="w3-container">
                         <h5>No hay notificaciones.</h5>
                    </div>
            {{/buzon}}
            {{^buzon}}
                {{#notificaciones}}
                <div class="w3-responsive">
                    <table class="w3-table w3-hoverable w3-striped">
                        <tr>
                            <td>{{notificacion}}</td>
                        </tr>
                    </table>
                </div>
                {{/notificaciones}}
            {{/buzon}}
        -->
    </div>
    <div class="w3-container">
        <div class="w3-container">
            <h2>Mis acciones</h2>
        </div>
        <a href="/Chofer/searchMyTravel" class="w3-half">
            <div class="w3-container w3-orange w3-text-white w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">add_circle_outline</i></div>
                <!-- Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                -->
                <div class="w3-clear"></div>
                <h4 class="w3-center">Mi viaje</h4>
            </div>
        </a>
        <a href="/Chofer/myTravels" class="w3-half">
            <div class="w3-container w3-blue w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">list</i></div>
                <!-- Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                -->
                <div class="w3-clear"></div>
                <h4 class="w3-center">Historial de viajes</h4>
            </div>
        </a>
    </div>
</div>
{{> footer}}
