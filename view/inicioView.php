{{> header}}
<div class="w3-container w3-padding-16 w3-xlarge">
    <h1>Bienvenido a DANT</h1>
    <h5>El sitio interno para que los empleados puedan cumplir sus funciones al alcance de la tecnología</h5>
</div>
{{#noRol}}
<div id="noRol" class="w3-modal" style="display: block;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="document.getElementById('noRol').style.display='none'"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <div class="w3-container">
            <p><strong>Un administrador le dará rol en la brevedad.</strong></p>
            <div class="w3-center">
                <button onclick="document.getElementById('noRol').style.display='none'"
                        type="button" class="w3-button w3-section w3-padding w3-blue">Aceptar</button>
            </div>
        </div>
    </div>
</div>
{{/noRol}}
{{#errorLogin}}
<div id="errorLogin" class="w3-modal" style="display: block;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px"><br>
        <span onclick="document.getElementById('errorLogin').style.display='none'"
              class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        <div class="w3-container">
            <p><strong>Error: datos incorrectos, no existentes o rol no asignado</strong></p>
            <div class="w3-center">
                <button onclick="document.getElementById('errorLogin').style.display='none'"
                        type="button" class="w3-button w3-section w3-padding w3-blue">Aceptar</button>
            </div>
        </div>
    </div>
</div>
{{/errorLogin}}
{{#mecanico}}
<div id="completeMecanico" class="w3-modal w3-center" style="display: block;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px">
        <form class="w3-container" action="/usuario/completeSignin" method="post">
            <div class="w3-section">
                <input type="hidden" value="{{dni}}" name="dni" required>
                <input type="hidden" value="{{rol}}" name="rol" required>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Matricula" name="matricula" required>
                <button class="w3-button w3-block w3-red w3-section w3-padding" type="submit">Finalizar</button>
            </div>
        </form>
    </div>
</div>
{{/mecanico}}
{{#chofer}}
<div id="completeChofer" class="w3-modal w3-center" style="display: block;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px">
        <form class="w3-container" action="/usuario/completeSignin" method="post">
            <div class="w3-section">
                <input type="hidden" value="{{dni}}" name="dni" required>
                <input type="hidden" value="{{rol}}" name="rol" required>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Tipo de licencia" name="tipoLicencia" required>
                <button class="w3-button w3-block w3-red w3-section w3-padding" type="submit">Finalizar</button>
            </div>
        </form>
    </div>
</div>
{{/chofer}}
<div id="login" class="w3-modal w3-center">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:400px">
        <div class="w3-center"><br>
            <span onclick="document.getElementById('login').style.display='none'"
                  class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        </div>
        <form class="w3-container w3-padding" action="/usuario/login" method="post">
            <div class="w3-section">
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="DNI" name="dni" required>
                <input class="w3-input w3-border w3-margin-bottom" type="password" placeholder="Contraseña" name="pass" required>
                <button class="w3-button w3-block w3-blue w3-section w3-padding" type="submit">Ingresar</button>
                <button onclick="document.getElementById('login').style.display='none'"
                        type="button" class="w3-button w3-block w3-red">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<div id="signin" class="w3-modal w3-center">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:500px">
        <div class="w3-center"><br>
            <span onclick="document.getElementById('signin').style.display='none'"
                  class="w3-button w3-red w3-display-topright w3-margin-8" title="Close Modal">&times;</span>
        </div>
        <form class="w3-container w3-padding" action="/usuario/register" method="post" id="formSignin">
            <div class="w3-section">
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="DNI" name="dni" required>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Nombre" name="nombre" required>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Apellido" name="apellido" required>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="E-mail" name="email" required>
                <input class="w3-input w3-border w3-margin-bottom" type="password" placeholder="Contraseña" name="pass" required>
                <input class="w3-input w3-border w3-margin-bottom" type="date" name="fnac">
                <select class="w3-input w3-border w3-margin-bottom" name="rol">
                    <option value="" disabled selected>Elija el puesto de trabajo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Supervisor</option>
                    <option value="3">Mecánico</option>
                    <option value="4">Chofer</option>
                </select>
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" id="register">Registrar</button>
                <button onclick="document.getElementById('signin').style.display='none'"
                        type="button" class="w3-button w3-block w3-red">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<div class="w3-row w3-padding w3-margin-bottom">
    <div class="w3-half w3-container">
        <h2 class="w3-container w3-blue">Ingresar</h2>
        <p>Ingrese aquí para iniciar sesión.</p>
        <p>En caso de no tener rol asignado, no podrá efectuar sus actividades</p>
        <button onclick="document.getElementById('login').style.display='block'" class='w3-button w3-block w3-blue'>Ingresar</button>
    </div>
    <div class="w3-half w3-container">
        <h2 class="w3-container w3-green ">Registrarse</h2>
        <p>Complete sus datos para registrarse en el sistema.</p>
        <p>El registro finalizará cuando un administrador le otorgue rol.</p>
        <button onclick="document.getElementById('signin').style.display='block'" class='w3-button w3-block w3-green'>Registrarse</button>
    </div>
</div>
{{> footer}}