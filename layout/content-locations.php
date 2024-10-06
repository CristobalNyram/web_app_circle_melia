<div class="page-container">
    <div class="main-content text-center">
        <h1 class="h1 animate__animated animate__fadeInDown">
            Nuestra presencia en el mundo
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
        <div class="row d-flex justify-content-center" id="modal-images">
          <!-- Las imágenes se añadirán dinámicamente aquí -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Ventana flotante superior derecha -->
<div id="tourInfo" class="card shadow-sm" style="position: fixed; top: 20px; right: 20px; width: 300px; display: none; z-index: 1000;">
  <div class="card-body">
    <h5 class="card-title" id="tourInfoTitle">Tour de Ubicaciones</h5>
    <p class="card-text" id="tourInfoDescription">Estamos recorriendo ubicaciones interesantes. Haga clic en las ubicaciones resaltadas para más información.</p>
    <div id="tourInfoDetails"></div>
    <button hidden id="cancelTour" class="btn btn-danger btn-sm">Cancelar Tour</button>
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

    // Inicializa el mapa con un estilo plano
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11', // Cambié a un estilo plano
        center: [-99.9, 41.5], // Coordenadas iniciales
        zoom: 1, // Zoom alejado para ver gran parte del mundo
        fadeDuration: 0 // Eliminamos la animación predeterminada de fade-in
    });

    map.addControl(new mapboxgl.NavigationControl());

    // Configuramos una ligera demora para mostrar gradualmente el mapa
    setTimeout(() => {
        document.getElementById('map').style.transition = "opacity 2s"; // Transición suave
        document.getElementById('map').style.opacity = 1; // Mostramos el mapa
    }, 500); // Inicia la animación después de medio segundo

    // Información de los hoteles de Circle by Meliá
    const nearbyLocations = [
    {
        title: "Paradisus Cancún",
        description: "Paradisus Cancún",
        coordinates: [-86.7720018, 21.0836384], // Coordenadas aproximadas para Cancún
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cancun/paradisus-cancun',
        country: "México"
    },
    {
        title: "Meliá Cozumel - All Inclusive",
        description: "Meliá Cozumel - All Inclusive",
        coordinates: [-86.9234349, 20.5525368], // Coordenadas aproximadas para Cozumel
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cozumel/melia-cozumel',
        country: "México"
    },
    {
        title: "ME Cabo",
        description: "ME Cabo", 
        coordinates: [-109.9042147, 22.8893707], // Coordenadas aproximadas para Los Cabos
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/los-cabos/me-cabo',
        country: "México"
    },
    {
        title: "Paradisus Los Cabos - Adults Only",
        description: "Paradisus Los Cabos - Adults Only",
        coordinates: [-109.7690772, 22.9805181], // Coordenadas aproximadas para Los Cabos
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/los-cabos/paradisus-los-cabos',
        country: "México"
    },
    {
        title: "Paradisus La Perla – Adults Only – Riviera Maya",
        description: "Paradisus La Perla – Adults Only – Riviera Maya",
        coordinates: [-87.0566886, 20.6471895], // Coordenadas aproximadas para Playa del Carmen
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/playa-del-carmen/paradisus-la-perla',
        country: "México"
    },
    {
        title: "Paradisus Playa del Carmen - Riviera Maya",
        description: "Paradisus Playa del Carmen - Riviera Maya",
        coordinates: [-87.0594551, 20.6470199], // Coordenadas aproximadas para Playa del Carmen
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/playa-del-carmen/paradisus-playa-del-carmen',
        country: "México"
    },
    {
        title: "Meliá Puerto Vallarta",
        description: "Meliá Puerto Vallarta",
        coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/puerto-vallarta/melia-puerto-vallarta',
        country: "México"
    },
    {
        title: "INNSiDE New York NoMad",
        description: "INNSiDE New York NoMad",
        coordinates: [-73.992624, 40.7459577], // Coordenadas aproximadas para Nueva York
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/estados-unidos/nueva-york/innside-new-york-nomad',
        country: "Estados Unidos"
    },
    {
        title: "Meliá Orlando Celebration",
        description: "Meliá Orlando Celebration",
        coordinates: [-81.5384511, 28.3310574], // Coordenadas aproximadas para Orlando
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/estados-unidos/orlando/melia-orlando-celebration',
        country: "Estados Unidos"
    },
    {
        title: "Meliá Costa Rey",
        description: "Meliá Costa Rey",
        coordinates: [-78.3475681, 22.5330516], // Coordenadas aproximadas para Cayo Coco
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/melia-costa-rey',
        country: "Cuba"
    },
    {
        title: "TRYP Cayo Coco",
        description: "TRYP Cayo Coco",
        coordinates: [-78.3707122, 22.5408726], // Coordenadas aproximadas para Cayo Coco
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/tryp-cayo-coco',
        country: "Cuba"
    },
    {
        title: "Meliá Brasil 21",
        description: "Meliá Brasil 21",
        coordinates: [-47.8957906, -15.7929072], // Coordenadas aproximadas para Brasilia
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/brasilia/melia-brasil-21',
        country: "Brasil"
    },
    {
        title: "Hotel Brasil 21 Suites Affiliated by Meliá",
        description: "Hotel Brasil 21 Suites Affiliated by Meliá",
        coordinates: [-47.8932408, -15.7936841], // Coordenadas aproximadas para Brasilia
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/brasilia/brasil-21-suites-by-melia',
        country: "Brasil"
    },
    {
        title: "Meliá Campinas",
        description: "Meliá Campinas",
        coordinates: [-47.0572486, -22.8975249], // Coordenadas aproximadas para Campinas
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/campinas/melia-campinas',
        country: "Brasil"
    },
    {
        title: "São Paulo Higienópolis Affiliated by Meliá",
        description: "São Paulo Higienópolis Affiliated by Meliá",
        coordinates: [-46.656242, -23.5451565], // Coordenadas aproximadas para São Paulo
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/sao-paulo/hotel-sao-paulo-higienopolis-by-melia',
        country: "Brasil"
    },
    {
        title: "Meliá Paulista",
        description: "Meliá Paulista",
        coordinates: [-46.6609418, -23.558052], // Coordenadas aproximadas para São Paulo
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/sao-paulo/melia-paulista',
        country: "Brasil"
    },
    {
        title: "Paradisus Grand Cana - All Suites",
        description: "Paradisus Grand Cana - All Suites",
        coordinates: [-68.4206361, 18.675375], // Coordenadas aproximadas para Punta Cana
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/paradisus-grand-cana',
        country: "República Dominicana"
    },
    {
        title: "Meliá Punta Cana Beach",
        description: "Meliá Punta Cana Beach",
        coordinates: [-68.4158052, 18.6706236], // Coordenadas aproximadas para Punta Cana
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/melia-punta-cana-beach-resort',
        country: "República Dominicana"
    },
    {
        title: "Paradisus Palma Real Golf & Spa Resort",
        description: "Paradisus Palma Real Golf & Spa Resort",
        coordinates: [-68.4124103, 18.6764801], // Coordenadas aproximadas para Punta Cana
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/paradisus-palma-real-golf-and-spa-resort',
        country: "República Dominicana"
    },
    {
        title: "Meliá Caracas",
        description: "Meliá Caracas",
        coordinates: [-66.8779819, 10.4917851], // Coordenadas aproximadas para Caracas
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/venezuela/caracas/melia-caracas',
        country: "Venezuela"
    },
    {
        title: "INNSiDE Aachen",
        description: "INNSiDE Aachen",
        coordinates: [6.0887299, 50.7794873], // Coordenadas aproximadas para Aquisgrán
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/alemania/aquisgran/innside-aachen',
        country: "Alemania"
    },
    {
        title: "Meliá Berlin",
        description: "Meliá Berlin",
        coordinates: [13.3884334, 52.5214812], // Coordenadas aproximadas para Berlín
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/alemania/berlin/melia-berlin',
        country: "Alemania"
    },
    {
        title: "Villa Marquis Meliá Collection",
        description: "Villa Marquis Meliá Collection",
        coordinates: [2.3045342, 48.8652151], // Coordenadas aproximadas para París
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/francia/paris/villa-marquis-melia-collection',
        country: "Francia"
    },
    {
        title: "Meliá Paris Vendôme",
        description: "Meliá Paris Vendôme",
        coordinates: [2.3258476, 48.8667918], // Coordenadas aproximadas para París
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/francia/paris/melia-paris-vendome',
        country: "Francia"
    },
    {
        title: "Meliá Athens",
        description: "Meliá Athens",
        coordinates: [23.730062, 37.9860708], // Coordenadas aproximadas para Atenas
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/grecia/atenas/melia-athens',
        country: "Grecia"
    },
    {
        title: "Sol Cosmopolitan Rhodes",
        description: "Sol Cosmopolitan Rhodes",
        coordinates: [28.1997049, 36.4266108], // Coordenadas aproximadas para Rodas
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/grecia/rodas/sol-cosmopolitan-rhodes',
        country: "Grecia"
    },
    {
        title: "Meliá Jinan",
        description: "Meliá Jinan",
        coordinates: [116.8972, 36.68098], // Coordenadas aproximadas para Jinan
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/china/jinan/melia-jinan',
        country: "China"
    },
    {
        title: "Meliá Shanghai Parkside",
        description: "Meliá Shanghai Parkside",
        coordinates: [121.66323, 31.137137], // Coordenadas aproximadas para Shanghái
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/china/shanghai/melia-shanghai-parkside',
        country: "China"
    },
    {
        title: "Meliá Koh Samui",
        description: "Meliá Koh Samui",
        coordinates: [100.083394, 9.5688762], // Coordenadas aproximadas para Koh Samui
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/tailandia/koh-samui/melia-koh-samui',
        country: "Tailandia"
    },
    {
        title: "Meliá Vinpearl Danang Riverfront",
        description: "Meliá Vinpearl Danang Riverfront",
        coordinates: [108.2294441, 16.0711221], // Coordenadas aproximadas para Da Nang
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/vietnam/da-nang/melia-vinpearl-danang-riverfront',
        country: "Vietnam"
    },
    {
        title: "Meliá Danang Beach Resort",
        description: "Meliá Danang Beach Resort",
        coordinates: [108.269255, 16.0005344], // Coordenadas aproximadas para Da Nang
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/vietnam/da-nang/melia-danang-beach-resort',
        country: "Vietnam"
    },
    {
        title: "Sol Oasis Marrakech",
        description: "Sol Oasis Marrakech",
        coordinates: [-7.9573917, 31.7068721], // Coordenadas aproximadas para Marrakech
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/marruecos/marrakech/sol-oasis-marrakech',
        country: "Marruecos"
    },
    {
        title: "ME Dubai",
        description: "ME Dubai",
        coordinates: [55.2668307, 25.1890259], // Coordenadas aproximadas para Dubái
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/emiratos-arabes-unidos/dubai/me-dubai',
        country: "Emiratos Árabes Unidos"
    },
    {
        title: "Sol Tropikal Durres",
        description: "Sol Tropikal Durres",
        coordinates: [19.4771, 41.3135], // Coordenadas aproximadas para Durrës
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/albania/durres/sol-tropikal-durres',
        country: "Albania"
    }
];

    // Ordenar por país
    nearbyLocations.sort((a, b) => a.country.localeCompare(b.country));

    // Mostrar información del tour en ventana flotante
    function showTourInfo(location) {
        const tourInfo = document.getElementById('tourInfo');
        document.getElementById('tourInfoTitle').textContent = location.title;
        document.getElementById('tourInfoDescription').textContent = location.description;
        const tourInfoDetails = document.getElementById('tourInfoDetails');
        tourInfoDetails.innerHTML = '';
        if (location.hotelsLink) {
            tourInfoDetails.innerHTML += `<a href="${location.hotelsLink}" target="_blank" class="btn btn-primary btn-sm mt-2">Ver hotel</a>`;
        }
        tourInfo.style.display = 'block';
    }

    // Ocultar información del tour
    function hideTourInfo() {
        const tourInfo = document.getElementById('tourInfo');
        tourInfo.style.display = 'none';
    }

    // Función para hacer un recorrido horizontal entre las ubicaciones
    function activateHorizontalTour() {
        let currentLocationIndex = 0;
        showTourInfo(nearbyLocations[currentLocationIndex]);

        function flyToNextLocation() {
            if (currentLocationIndex < nearbyLocations.length) {
                const location = nearbyLocations[currentLocationIndex];

                // Mueve el mapa hacia la siguiente ubicación
                map.flyTo({
                    center: location.coordinates,
                    zoom: 5, // Zoom más lejano para resaltar varias áreas
                    speed: 0.5 // Ajuste la velocidad del vuelo para un efecto más suave
                });

                // Después de volar a la ubicación, agregamos el marcador y popup
                const marker = new mapboxgl.Marker({
                    color: 'yellow', // Marcador amarillo para destacar
                    scale: 1.5 // Tamaño más grande
                })
                .setLngLat(location.coordinates)
                .setPopup(new mapboxgl.Popup().setText(location.description)) // Texto en el popup
                .addTo(map);

                // Mostrar el modal al hacer clic
                marker.getElement().addEventListener('click', function() {
                    showLocationModal(location);
                });

                // Mostrar información en la ventana flotante
                showTourInfo(location);

                // Pasar a la siguiente ubicación después de 4 segundos
                setTimeout(flyToNextLocation, 4000);

                currentLocationIndex++; // Avanzamos a la siguiente ubicación
            } else {
                hideTourInfo();
            }
        }

        flyToNextLocation(); // Comenzamos el recorrido
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
        if (location.hotelsLink) {
            const hotelButton = document.createElement('a');
            hotelButton.href = location.hotelsLink;
            hotelButton.target = "_blank";
            hotelButton.className = 'btn btn-primary mt-3';
            hotelButton.textContent = 'Ver hotel';
            imagesContainer.appendChild(hotelButton);
        }

        // Mostrar el modal (Bootstrap 4.3.1)
        $('#locationModal').modal('show');
    }

    // Asignar la función de recorrido al botón
    document.getElementById('startTour').addEventListener('click', activateHorizontalTour);

    // Asignar la función de cancelar tour al botón
    document.getElementById('cancelTour').addEventListener('click', function() {
        hideTourInfo();
    });

    // Mostrar los puntos de ubicación al cargar la página
    function showNearbyLocations() {
        let countryGroups = {};

        // Agrupar ubicaciones por país
        nearbyLocations.forEach(location => {
            if (!countryGroups[location.country]) {
                countryGroups[location.country] = [];
            }
            countryGroups[location.country].push(location);
        });

        // Mostrar las ubicaciones gradualmente por país
        let countryKeys = Object.keys(countryGroups);
        let currentCountryIndex = 0;

        function showCountryLocations() {
            if (currentCountryIndex < countryKeys.length) {
                let country = countryKeys[currentCountryIndex];
                let locations = countryGroups[country];

                locations.forEach((location, index) => {
                    setTimeout(() => {
                        const marker = new mapboxgl.Marker({
                            color: 'red', // Marcador de color rojo para resaltar
                            scale: 0.5 // Tamaño más pequeño
                        })
                        .setLngLat(location.coordinates)
                        .setPopup(new mapboxgl.Popup().setText(location.description)) // Texto en el popup
                        .addTo(map);

                        // Funcionalidad al hacer clic en los marcadores
                        marker.getElement().addEventListener('click', function() {
                            showLocationModal(location);
                        });
                    }, index * 500); // Mostrar gradualmente cada ubicación con una demora
                });

                // Pasar al siguiente país después de mostrar todas sus ubicaciones
                setTimeout(() => {
                    currentCountryIndex++;
                    showCountryLocations();
                }, locations.length * 500 + 1000);
            }
        }

        showCountryLocations();
    }

    // Mostrar los puntos de ubicación al cargar la página
    showNearbyLocations();
</script>