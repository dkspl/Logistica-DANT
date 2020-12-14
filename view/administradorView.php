{{> header}}

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
        <a href="/Administrador/usersAdmin" class="w3-half">
            <div class="w3-container w3-orange w3-text-white w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">group</i></div>
                <!-- Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                -->
                <div class="w3-clear"></div>
                <h4 class="w3-center">Usuarios</h4>
            </div>
        </a>
        <a href="/Administrador/vehiculos" class="w3-half">
            <div class="w3-container w3-red w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">local_shipping</i></div>
                <!-- Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                -->
                <div class="w3-clear"></div>
                <h4 class="w3-center">Vehículos</h4>
            </div>
        </a>
        <a href="/Administrador/reportes" class="w3-half">
            <div class="w3-container w3-blue w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">leaderboard</i></div>
                <!-- Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                -->
                <div class="w3-clear"></div>
                <h4 class="w3-center">Reportes</h4>
            </div>
        </a>
        <!--<a href="/Administrador/Sistema" class="w3-half">
            <div class="w3-container w3-teal w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">build</i></div>
                Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                <div class="w3-clear"></div>
                <h4 class="w3-center">Sistema</h4>
            </div>
        </a>-->
        <a href="/usuario/profile" class="w3-half">
            <div class="w3-container w3-green w3-text-white w3-padding-16">
                <div class="w3-center"><i class="w3-xxxlarge material-icons">account_circle</i></div>
                <!-- Cantidad dinámica
                <div class="w3-right">
                    <h3>23</h3>
                </div>
                -->
                <div class="w3-clear"></div>
                <h4 class="w3-center">Editar perfil</h4>
            </div>
        </a>
    </div>
</div>
{{> footer}}
