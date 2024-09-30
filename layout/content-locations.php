<div class="page-container">
    <div class="main-content text-center">
        <h1 class="h1 animate__animated animate__fadeInDown">
            Nuestras ubicaciones
        </h1>

        <!-- Botón para iniciar el recorrido -->
        <button id="startTour" class="btn btn-primary mb-3">Iniciar recorrido</button>

        <!-- Contenedor del mapa -->
        <div id="map" style="height: 500px; width: 100%; opacity: 0;"></div> <!-- Mapa con opacidad inicial de 0 -->
    </div>
</div>

<!-- Modal de Bootstrap para mostrar ubicaciones adicionales -->
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="locationModalLabel">Ubicaciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="modal-description"></p>
        <div class="row" id="modal-images">
          <!-- Las imágenes se añadirán dinámicamente aquí -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap CSS and JS para 4.3.1 -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Mapbox JS and CSS -->
<script src="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
<link href="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet" />

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibnlyYW1jYXV0aXZhIiwiYSI6ImNtMW8yemtjNDEwbzgycG85ZzhhenB2NGMifQ.SE8F9lJLM5o3kWxJv9wK7g';

    // Inicializa el mapa dentro del contenedor 'map' con un zoom inicial alejado para ver todo el mundo
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/dark-v11', // Estilo oscuro del mapa
        center: [-99.9, 41.5], // Coordenadas iniciales (aproximadamente el centro de América del Norte)
        zoom: 1, // Zoom muy alejado para ver gran parte del mundo
        pitch: 45, // Inclina el mapa para dar una perspectiva 3D
        fadeDuration: 0 // Eliminamos la animación predeterminada de fade-in
    });

    map.addControl(new mapboxgl.NavigationControl());

    // Configuramos una ligera demora para mostrar gradualmente el mapa
    setTimeout(() => {
        document.getElementById('map').style.transition = "opacity 2s"; // Transición suave
        document.getElementById('map').style.opacity = 1; // Mostramos el mapa
    }, 500); // Inicia la animación después de medio segundo

    let rotationRequest; // Para controlar la animación de rotación

    // Función para obtener la ubicación del usuario
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const userCoordinates = [position.coords.longitude, position.coords.latitude];
                console.log("Ubicación del usuario:", userCoordinates);

                // Centrar el mapa en la ubicación del usuario
                map.flyTo({
                    center: userCoordinates,
                    zoom: 10, // Zoom cercano a la ubicación
                    speed: 1 // Velocidad de la animación
                });

                // Resaltar la ubicación del usuario
                new mapboxgl.Marker({ color: 'red' })
                    .setLngLat(userCoordinates)
                    .setPopup(new mapboxgl.Popup().setText("Estás aquí")) // Popup de la ubicación del usuario
                    .addTo(map);

                // Agregar los lugares cercanos con el efecto secuencial
                showNearbyLocations(userCoordinates);

            }, function (error) {
                console.error("Error al obtener la ubicación del usuario:", error);
                // Si falla, aplicamos el efecto de encendido y giro del mapa
                activateSequentialLightingWithRotation();
            });
        } else {
            console.error("La geolocalización no está disponible en este navegador.");
            // Si la geolocalización no está disponible, aplicamos el efecto de encendido y giro del mapa
            activateSequentialLightingWithRotation();
        }
    }

    // Información de los hoteles de Circle by Meliá
    const nearbyLocations = [
        {
            title: "Circle by Meliá Punta Cana",
            description: "Circle by Meliá en Punta Cana, República Dominicana.",
            coordinates: [-68.366, 18.582], // Coordenadas aproximadas
            images: [
                { src: "https://dam.melia.com/melia/file/dQN7Y5ZnNP2M7fPVmV4E.jpg?im=RegionOfInterestCrop=(480,320),regionOfInterest=(2080.0,3120.0)", alt: "Hotel 1", caption: "" },
                { src: "https://dam.melia.com/melia/file/MFytv4d7FXRkS2f2p3dW.jpg?im=RegionOfInterestCrop=(480,320),regionOfInterest=(3072.0,1728.0)", alt: "Hotel 2", caption: "" }
            ],
            mapsLink: 'https://maps.app.goo.gl/GN195SjeowVYUfGe7'
        },
        {
            title: "Circle by Meliá Cancún",
            description: "Circle by Meliá en Cancún, México.",
            coordinates: [-86.8475, 21.1619], // Coordenadas aproximadas
            images: [
                { src: "https://dam.melia.com/melia/file/dQN7Y5ZnNP2M7fPVmV4E.jpg?im=RegionOfInterestCrop=(480,320),regionOfInterest=(2080.0,3120.0)", alt: "Hotel 1", caption: "" },
                { src: "https://dam.melia.com/melia/file/MFytv4d7FXRkS2f2p3dW.jpg?im=RegionOfInterestCrop=(480,320),regionOfInterest=(3072.0,1728.0)", alt: "Hotel 2", caption: "" }
            ],
            mapsLink: 'https://maps.app.goo.gl/GN195SjeowVYUfGe7'
        },
        {
            title: "Circle by Meliá Playa del Carmen",
            description: "Circle by Meliá en Playa del Carmen, México.",
            coordinates: [-87.0739, 20.6296], // Coordenadas aproximadas
            images: [
                { src: "https://dam.melia.com/melia/file/dQN7Y5ZnNP2M7fPVmV4E.jpg?im=RegionOfInterestCrop=(480,320),regionOfInterest=(2080.0,3120.0)", alt: "Hotel 1", caption: "" },
                { src: "https://dam.melia.com/melia/file/MFytv4d7FXRkS2f2p3dW.jpg?im=RegionOfInterestCrop=(480,320),regionOfInterest=(3072.0,1728.0)", alt: "Hotel 2", caption: "" }
            ],
            mapsLink: 'https://maps.app.goo.gl/GN195SjeowVYUfGe7'
        }
        // Puedes agregar más hoteles aquí siguiendo el mismo formato
    ];

    // Función para mostrar lugares cercanos con un efecto secuencial
    function showNearbyLocations(userCoordinates) {
        nearbyLocations.forEach((location, index) => {
            setTimeout(() => {
                const marker = new mapboxgl.Marker()
                    .setLngLat(location.coordinates)
                    .setPopup(new mapboxgl.Popup().setText(location.description)) // Texto en el popup
                    .addTo(map);

                // Funcionalidad al hacer clic en los marcadores cercanos
                marker.getElement().addEventListener('click', function() {
                    showLocationModal(location);
                });
            }, index * 1000); // Aparecen secuencialmente con 1 segundo de diferencia
        });
    }

    // Función para aplicar el efecto de encendido secuencial y rotar el mapa
    function activateSequentialLightingWithRotation() {
        // Inicia la rotación continua del mapa
        rotateMap();

        nearbyLocations.forEach((location, index) => {
            setTimeout(() => {
                const marker = new mapboxgl.Marker({ color: 'yellow' }) // Marcadores amarillos como "foquitos"
                    .setLngLat(location.coordinates)
                    .setPopup(new mapboxgl.Popup().setText(location.description)) // Texto en el popup
                    .addTo(map);

                // Funcionalidad al hacer clic en los marcadores cercanos
                marker.getElement().addEventListener('click', function() {
                    showLocationModal(location);
                });

                // Detener la rotación cuando se haya marcado el último punto
                if (index === nearbyLocations.length - 1) {
                    setTimeout(() => cancelAnimationFrame(rotationRequest), 2000); // Detiene el giro un poco después de marcar el último punto
                }

            }, index * 3000); // Aparecen secuencialmente con 3 segundos de diferencia
        });
    }

    // Función para hacer girar el mapa
    function rotateMap() {
        let rotationAngle = 0;
        function rotate() {
            rotationAngle += 0.5; // Incrementa el ángulo de rotación
            map.rotateTo(rotationAngle, { duration: 100 }); // Ajusta la rotación
            rotationRequest = requestAnimationFrame(rotate); // Continuar la rotación
        }
        rotate(); // Inicia la rotación
    }

    // Función para mostrar el modal con la información del lugar
    function showLocationModal(location) {
        document.getElementById('locationModalLabel').textContent = location.title;
        document.getElementById('modal-description').textContent = location.description;

        const imagesContainer = document.getElementById('modal-images');
        imagesContainer.innerHTML = ''; // Limpiar imágenes anteriores

        // Añadir imágenes dinámicamente
        location.images.forEach(image => {
            const colDiv = document.createElement('div');
            colDiv.className = 'col-md-6';
            colDiv.innerHTML = `
                <img src="${image.src}" alt="${image.alt}" class="img-fluid">
                <p>${image.caption}</p>
            `;
            imagesContainer.appendChild(colDiv);
        });

        // Añadir el enlace de Google Maps si existe
        if (location.mapsLink) {
            const mapsButton = document.createElement('a');
            mapsButton.href = location.mapsLink;
            mapsButton.target = "_blank";
            mapsButton.className = 'btn btn-primary mt-3';
            mapsButton.textContent = 'Ver en Google Maps';
            imagesContainer.appendChild(mapsButton);
        }

        // Mostrar el modal (Bootstrap 4.3.1)
        $('#locationModal').modal('show');
    }

    // Función para hacer un recorrido por las ubicaciones
    function startTour() {
        nearbyLocations.forEach((location, index) => {
            setTimeout(() => {
                // Hacer que el mapa vuele a cada ubicación
                map.flyTo({
                    center: location.coordinates,
                    zoom: 12, // Zoom cercano para ver detalles
                    speed: 1.2 // Velocidad del vuelo entre ubicaciones
                });

                // Después de volar a la ubicación, mostramos el modal con la información
                setTimeout(() => {
                    showLocationModal(location);
                }, 2000); // Mostrar el modal 2 segundos después de volar a la ubicación
            }, index * 5000); // Espera de 5 segundos entre cada ubicación
        });
    }

    // Asignar la función de recorrido al botón
    document.getElementById('startTour').addEventListener('click', startTour);

    // Obtener la ubicación del usuario al cargar la página
    getUserLocation();
</script>
