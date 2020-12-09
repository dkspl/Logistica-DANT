

function showMapFrom(id, latitud, longitud, texto){
    let map = L.map(id).setView([40.91, -96.63], 4);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    let searchControl = L.esri.Geocoding.geosearch().addTo(map);

    let results = L.layerGroup().addTo(map);

    searchControl.on('results', function (data) {
        results.clearLayers();
        for (var i = data.results.length - 1; i >= 0; i--) {
            results.addLayer(L.marker(data.results[i].latlng)
                .addTo(map).bindPopup(""+data.results[i].text).openPopup());
            setLatLongText(latitud, longitud, texto,
                data.latlng.lat,data.latlng.lng,data.results[i].text);
        }
    });
}

function setLatLongText(idLat,idLong,idText,lat,long,text){
    let latitud = document.getElementById(idLat);
    let longitud = document.getElementById(idLong);
    let direccion = document.getElementById(idText);

    latitud.value = lat;
    longitud.value = long;
    direccion.value = text;
}

function showMapWithMarkerFrom(id,patente, latitud, longitud, texto){
    let map = L.map(id).setView([latitud, longitud], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    L.marker([latitud, longitud]).addTo(map)
        .bindPopup('<b>Vehículo: '+patente+'</b><br>Última vez: '+texto)
        .openPopup();
}

function getLocation(lat, long) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){
            showPosition(position,lat,long);
        });
    } else {
        alert("Por favor, otorgue permisos de ubicación a la aplicación.");
    }
}

function showPosition(position, lat, long) {
    document.getElementById(lat).value = position.coords.latitude;
    document.getElementById(long).value = position.coords.longitude;
}