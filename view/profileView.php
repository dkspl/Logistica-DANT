{{> header}}

<div class="w3-container">
    <h1>Perfil</h1>
</div>
<div class="w3-container w3-center w3-margin w3-border w3-border-green">
    {{#usuario}}
    <div id="pwedit" class="w3-modal">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
            <span onclick="document.getElementById('pwedit').style.display='none'"
                  class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
            <form class="w3-container" action="/usuario/editPassword" method="post">
                    <p><strong>Cambiar contraseña</strong></p>
                    <input type="hidden" name="dni" value="{{dni}}">
                    <label class="w3-container">
                        <span class="w3-container">Contraseña actual: </span>
                        <input type="password" name="actual">
                    </label>
                    <label class="w3-container">
                        <span class="w3-container">Nueva contraseña: </span>
                        <input type="password" name="nueva">
                    </label>
                    <label class="w3-container">
                        <span class="w3-container">Confirmar contraseña: </span>
                        <input type="password" name="confirmar">
                    </label>
                    <button class="w3-button w3-block w3-red w3-section w3-padding" type="submit">Aceptar</button>
            </form>
        </div>
    </div>
    <h1 class="w3-green">{{apellido}}, {{nombre}} - DNI {{dni}}</h1>
    <form class="w3-container" action="/usuario/editUser" method="post">
        <div>
            <input type="hidden" value="{{dni}}" name="dni">
            <label class="w3-container">
                <span>E-mail: </span>
                <input type="text" name="email" value="{{email}}">
            </label>
            <label class="w3-container">
                <span>Fecha de nacimiento </span>
                <input type="date" name="fnac" value="{{fnac}}">
            </label>
            <label class="w3-container">
                <span>Puesto: {{rol}}</span>
            </label>
            <button class="w3-button w3-blue w3-section w3-padding" type="submit">Editar perfil</button>
            <a class="w3-button  w3-section w3-padding w3-green" href="/">Volver a Inicio</a>
        </div>
    </form>
    <button onclick="document.getElementById('pwedit').style.display='block'" class='w3-button w3-margin-bottom w3-red'>Editar contraseña</button>
    {{/usuario}}
</div>

{{> footer}}