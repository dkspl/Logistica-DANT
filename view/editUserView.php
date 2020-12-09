{{> header}}
{{#user}}
<div class="w3-container w3-margin">
    <h1>Modificar usuario: {{dni}}</h1>
</div>
<div class="w3-container w3-margin">
    <form class="w3-container w3-card-4 w3-padding-16 w3-white" action="/Administrador/editEmployee" method="post">
        <div class="w3-container w3-green">
            <h1 class="w3-container">Datos personales</h1>
        </div>
        <div class="w3-container">
            <br>
            <input type="hidden" name="dni" value="{{dni}}">
            <label class="w3-section">
                <span>DNI:</span>
                <span class="w3-input">{{dni}}</span>
            </label>
            <br>
            <label class="w3-section">
                <span>Apellidos:</span>
                <input type="text" class="w3-input" name="apellido" value="{{apellido}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Nombres:</span>
                <input type="text" class="w3-input" name="nombre" value="{{nombre}}">
            </label>
            <br>
            <label class="w3-section">
                <span>E-mail:</span>
                <input type="email" class="w3-input" name="email" value="{{email}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Fecha de nacimiento:</span>
                <input type="date" class="w3-input" name="fnac" value="{{fnac}}">
            </label>
            <br>
            <label class="w3-section">
                <span>Puesto de trabajo:</span>
                <span class="w3-input">{{rol}}</span>
            </label>
        </div>
        <br>
        <div class="w3-container w3-margin-16 w3-center">
            <a href="/Administrador/usersAdmin" class="w3-button w3-border w3-border-green w3-text-green w3-white">Volver</a>
            <button class="w3-button w3-green" type="submit">Guardar cambios</button>
        </div>
{{/user}}
    </form>
</div>
{{> footer}}
