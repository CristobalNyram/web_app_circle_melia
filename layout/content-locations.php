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
    // const nearbyLocations = [
    //     {
    //         title: "Meliá Casa Maya - Cancún - All Inclusive",
    //         description: "Meliá Casa Maya - Cancún - All Inclusive",
    //         coordinates: [-68.366, 18.582], // Coordenadas aproximadas
    //         images: [
                
    //         ],
    //         mapsLink: '',
    //         hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cancun/melia-casa-maya-cancun-all-inclusive',
    //     },
    //     {
    //         title: "Paradisus Cancún",
    //         description: "Paradisus Cancún",
    //         coordinates: [-68.366, 18.582], // Coordenadas aproximadas
    //         images: [
                
    //         ],
    //         mapsLink: '',
    //         hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cancun/paradisus-cancun',
    //     },
    //     {
    //         title: "Meliá Cozumel - All Inclusive",
    //         description: "Meliá Cozumel - All Inclusive",
    //         coordinates: [-68.366, 18.582], // Coordenadas aproximadas
    //         images: [
                
    //         ],
    //         mapsLink: '',
    //         hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cozumel/melia-cozumel',
    //     },
    //     {
    //         title: "ME Cabo",
    //         description: "ME Cabo",
    //         coordinates: [-68.366, 18.582], // Coordenadas aproximadas
    //         images: [
                
    //         ],
    //         mapsLink: '',
    //         hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cancun/melia-casa-maya-cancun-all-inclusive',
    //     },
    //     {
    //         title: "Paradisus Los Cabos - Adults Only",
    //         description: "Paradisus Los Cabos - Adults Only",
    //         coordinates: [-68.366, 18.582], // Coordenadas aproximadas
    //         images: [
                
    //         ],
    //         mapsLink: '',
    //         hotelsLink: 'https://www.melia.com/es/hoteles/mexico/los-cabos/paradisus-los-cabos',
    //     },





       

        
    //     // Puedes agregar más hoteles aquí siguiendo el mismo formato
    // ];

    const nearbyLocations = [
    // {
    //     title: "Meliá Casa Maya - Cancún - All Inclusive",
    //     description: "Meliá Casa Maya - Cancún - All Inclusive",
    //     coordinates: [-86.8475, 21.1619], // Coordenadas aproximadas para Cancún
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cancun/melia-casa-maya-cancun-all-inclusive',
    // },
    // Mexico
    {
        title: "Paradisus Cancún",
        description: "Paradisus Cancún",
        coordinates: [-86.7720018,  21.0836384], // Coordenadas aproximadas para Cancún
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cancun/paradisus-cancun',
    }
    ,
    {
        title: "Meliá Cozumel - All Inclusive",
        description: "Meliá Cozumel - All Inclusive",
        coordinates: [-86.9234349,20.5525368], // Coordenadas aproximadas para Cozumel
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/cozumel/melia-cozumel',
    }
    ,
    {
        title: "ME Cabo",
        description: "ME Cabo", 
        coordinates: [-109.9042147,  22.8893707,], // Coordenadas aproximadas para Los Cabos
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/los-cabos/me-cabo',
    }
    ,
    {
        title: "Paradisus Los Cabos - Adults Only",
        description: "Paradisus Los Cabos - Adults Only",
        coordinates: [-109.7690772, 22.9805181], // Coordenadas aproximadas para Los Cabos
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/los-cabos/paradisus-los-cabos',
    }
    
    ,
    {
        title: "Paradisus La Perla – Adults Only – Riviera Maya",
        description: "Paradisus La Perla – Adults Only – Riviera Maya",
        // 20.6471895,-87.0566886
        coordinates: [-87.0566886,  20.6471895], // Coordenadas aproximadas para Playa del Carmen
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/playa-del-carmen/paradisus-la-perla',
    }
    
    ,
    {
        title: "Paradisus Playa del Carmen - Riviera Maya",
        description: "Paradisus Playa del Carmen - Riviera Maya",
        // 20.6470199,-87.0594551
        coordinates: [-87.0594551, 20.6470199], // Coordenadas aproximadas para Playa del Carmen
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/playa-del-carmen/paradisus-playa-del-carmen',
    }
    ,
    {
        title: "Meliá Puerto Vallarta",
        description: "Meliá Puerto Vallarta",
        // 20.6607226,
        coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/mexico/puerto-vallarta/melia-puerto-vallarta',
    }

    // USA
    ,
    {
        title: "INNSiDE New York NoMad",
        description: "INNSiDE New York NoMad",
        // 40.7459577,-73.992624,
        coordinates: [-73.992624, 40.7459577], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/estados-unidos/nueva-york/innside-new-york-nomad',
    }
    ,
    {
        title: "Meliá Orlando Celebration",
        description: "Meliá Orlando Celebration",
        // 20.6607226,
        // 28.3310574,
        coordinates: [-81.5384511, 28.3310574], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/estados-unidos/orlando/melia-orlando-celebration',
    }

    //cuba
    ,
    {
        title: "Meliá Costa Rey",
        description: "Meliá Costa Rey",
        // 20.6607226,
        // 22.5330516,-78.3475681
        coordinates: [-78.3475681,22.5330516], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/melia-costa-rey',
    }
    ,
    {
        title: "TRYP Cayo Coco",
        description: "TRYP Cayo Coco",
        // 20.6607226,
        // 22.5408726,-78.3707122,17
        coordinates: [-78.3707122, 22.5408726], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/tryp-cayo-coco',
    }
    // ,
    // {
    //     title: "Sol Cayo Coco",
    //     description: "Sol Cayo Coco",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/sol-cayo-coco',
    // }

    // ,
    // {
    //     title: "Meliá Jardines del Rey",
    //     description: "Meliá Jardines del Rey",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/melia-jardines-del-rey',
    // }
    // ,
    // {
    //     title: "Meliá Cayo Coco",
    //     description: "Meliá Cayo Coco",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/cuba/cayo-coco/melia-cayo-coco',
    // }

    //brasil 
    ,
    {
        title: "Meliá Brasil 21",
        description: "Meliá Brasil 21",
        // -15.7929072,-47.8957906,17
        coordinates: [-47.8957906, -15.7929072], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/brasilia/melia-brasil-21',
    }
    ,
    {
        title: "Hotel Brasil 21 Suites Affiliated by Meliá",
        description: "Hotel Brasil 21 Suites Affiliated by Meliá",
        // -15.7936841,-47.8932408,
        coordinates: [-47.8932408, -15.7936841], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/brasilia/brasil-21-suites-by-melia',
    }
    // ,
    // {
    //     title: "Hotel Brasil 21 Convention Affiliated by Meliá",
    //     description: "Hotel Brasil 21 Suites Affiliated by Meliá",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/brasil/brasilia/brasil-21-suites-by-melia',
    // }
    ,
    {
        title: "Meliá Campinas",
        description: "Meliá Campinas",
        // -22.8975249,-47.0572486
        coordinates: [-47.0572486, -22.8975249], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/campinas/melia-campinas',
    }

    ,
    {
        title: "São Paulo Higienópolis Affiliated by Meliá",
        description: "São Paulo Higienópolis Affiliated by Meliá",
        // -23.5451565,-46.656242
        coordinates: [-46.656242, -23.5451565], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/sao-paulo/hotel-sao-paulo-higienopolis-by-melia',
    }

    ,
    {
        title: "Meliá Paulista",
        description: "Meliá Paulista",
        // -23.558052,-46.6609418
        coordinates: [-46.6609418, -23.558052], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/sao-paulo/melia-paulista',
    }
    ,    
    {
        title: "São Paulo Tatuapé Affiliated by Meliá",
        description: "São Paulo Tatuapé Affiliated by Meliá",
        // -23.5470833,-46.570903
        coordinates: [-46.570903,  -23.5470833], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/brasil/sao-paulo/hotel-sao-paulo-tatuape-by-melia',
    }

    //REPUBLICA DOMINICANA 
    ,    
    {
        title: "Paradisus Grand Cana - All Suites",
        description: "Paradisus Grand Cana - All Suites",
        // 18.675375,-68.4206361
        coordinates: [-68.4206361, 18.675375], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/paradisus-grand-cana',
    }
    ,    
    {
        title: "Meliá Punta Cana Beach",
        description: "Meliá Punta Cana Beach",
        // 18.6706236,-68.4158052
        coordinates: [-68.4158052, 18.6706236], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/melia-punta-cana-beach-resort',
    }

    // ,    
    // {
    //     title: "The Reserve at Paradisus Palma Real",
    //     description: "The Reserve at Paradisus Palma Real",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/the-reserve-at-paradisus-palma-real',
    // }
    ,    
    {
        title: "Paradisus Palma Real Golf & Spa Resort",
        description: "Paradisus Palma Real Golf & Spa Resort",
        // 20.6607226,
        coordinates: [-68.4124103,18.6764801], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/paradisus-palma-real-golf-and-spa-resort',
    }
    // ,    
    // {
    //     title: "Meliá Caribe Beach Resort",
    //     description: "Meliá Caribe Beach Resort",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/melia-caribe-beach-resort',
    // }
    // ,    
    // {
    //     title: "Zel Punta Cana",
    //     description: "Zel Punta Cana",
    //     // 20.6607226,
    //     coordinates: [-105.2555874, 20.6607226], // Coordenadas aproximadas para Puerto Vallarta
    //     images: [],
    //     mapsLink: '',
    //     hotelsLink: 'https://www.melia.com/es/hoteles/republica-dominicana/punta-cana/zel-punta-cana',
    // }


    //venezuela
    ,    
    {
        title: "Meliá Caracas",
        description: "Meliá Caracas",
        // 10.4917851,-66.8779819
        coordinates: [-66.8779819, 10.4917851], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/venezuela/caracas/melia-caracas',
    }

    
    //alemania
    ,    
    {
        title: "INNSiDE Aachen",
        description: "INNSiDE Aachen",
        // 50.7794873,6.0887299,
        coordinates: [6.0887299,50.7794873], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/alemania/aquisgran/innside-aachen',
    }

    ,    
    {
        title: "Meliá Berlin",
        description: "Meliá Berlin",
        // 52.5214812,13.3884334
        coordinates: [13.3884334, 52.5214812], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/alemania/berlin/melia-berlin',
    }

    //francia
    ,    
    {
        title: "Villa Marquis Meliá Collection",
        description: "Villa Marquis Meliá Collection",
        // 48.8652151,2.3045342
        coordinates: [2.3045342, 48.8652151], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/francia/paris/villa-marquis-melia-collection',
    }
    ,    
    {
        title: "Meliá Paris Vendôme",
        description: "Meliá Paris Vendôme",
        // 48.8667918,2.3258476
        coordinates: [2.3258476, 48.8667918], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/francia/paris/melia-paris-vendome',
    }

    //grecia
    ,    
    {
        title: "Meliá Athens",
        description: "Meliá Athens",
        // 37.9860708,23.730062
        coordinates: [23.730062, 37.9860708], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/grecia/atenas/melia-athens',
    }
    ,
    {
        title: "Sol Cosmopolitan Rhodes",
        description: "Sol Cosmopolitan Rhodes",
        // 36.4266108,28.1997049
        coordinates: [28.1997049, 36.4266108], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/grecia/rodas/sol-cosmopolitan-rhodes',
    }

     //italia
     ,
    {
        title: "SolMeliá Genova",
        description: "Meliá Genova",
        // 44.4017255,8.9382956
        coordinates: [8.9382956, 44.4017255], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/italia/genova/melia-genova',
    }

    //Reino Unido
    ,
    {
        title: "The Level at Meliá White House",
        description: "The Level at Meliá White House",
        // 51.5253415,-0.1436268
        coordinates: [-0.1436268, 51.5253415], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/reino-unido/londres/the-level-at-melia-white-house',
    }
    ,
    {
        title: "ME London",
        description: "ME London",
        // 51.5118147,-0.1186952
        coordinates: [-0.1186952, 51.5118147], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/reino-unido/londres/me-london',
    }

    //República Checa
    ,
    {
        title: "INNSiDE Prague Old Town",
        description: "INNSiDE Prague Old Town",
        // 50.0915153,14.431021
        coordinates: [14.431021, 50.0915153], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/republica-checa/praga/innside-prague-old-town',
    }

    //china
    ,
    {
        title: "Meliá Jinan",
        description: "Meliá Jinan",
        // 36.68098,116.8972
        coordinates: [116.8972, 36.68098], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/china/jinan/melia-jinan',
    }
    ,
    {
        title: "Meliá Shanghai Parkside",
        description: "Meliá Shanghai Parkside",
        // 31.137137,121.66323
        coordinates: [121.66323, 31.137137], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/china/shanghai/melia-shanghai-parkside',
    }
    //Tailandia
    ,
    {
        title: "Meliá Koh Samui",
        description: "Meliá Koh Samui",
        // 9.5688762,100.083394
        coordinates: [100.083394, 9.5688762], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/tailandia/koh-samui/melia-koh-samui',
    }

    //vietnam
    ,
    {
        title: "Meliá Vinpearl Danang Riverfront",
        description: "Meliá Vinpearl Danang Riverfront",
        // 16.0711221,108.2294441
        coordinates: [108.2294441, 16.0711221], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/vietnam/da-nang/melia-vinpearl-danang-riverfront',
    }

    ,
    {
        title: "Meliá Danang Beach Resort",
        description: "Meliá Danang Beach Resort",
        // 16.0005344,108.269255
        coordinates: [108.269255, 16.0005344], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/vietnam/da-nang/melia-danang-beach-resort',
    }

    //marruecos
    ,
    {
        title: "Sol Oasis Marrakech",
        description: "Sol Oasis Marrakech",
        // 31.7068721,-7.9573917
        coordinates: [-7.9573917,31.7068721], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/marruecos/marrakech/sol-oasis-marrakech',
    }

    //Emiratos Árabes Unidos
    ,
    {
        title: "ME Dubai",
        description: "ME Dubai",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/emiratos-arabes-unidos/dubai/me-dubai',
    }

    //albania
    ,
    {
        title: "Sol Tropikal Durres",
        description: "Sol Tropikal Durres",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/albania/durres/sol-tropikal-durres',
    }
    
    ,
    {
        title: "Meliá Durres Albania",
        description: "Meliá Durres Albania",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/albania/durres/melia-durres-albania',
    }
    
    ,
    {
        title: "Hotel Elisa Tirana Affiliated by Meliá",
        description: "Hotel Elisa Tirana Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/albania/tirana/hotel-elisa-tirana-affiliated-by-melia',
    }
    ,
    {
        title: "Velipoja Grand Europa Resort Affiliated by Meliá",
        description: "Velipoja Grand Europa Resort Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/albania/tirana/hotel-elisa-tirana-affiliated-by-melia',
    }
    
  //espania
    ,
    {
        title: "Meliá Maria Pita",
        description: "Meliá Maria Pita",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/a-coruna/melia-maria-pita',
    }
    ,
    {
        title: "Hotel Spa Porta Maris by Meliá",
        description: "Hotel Spa Porta Maris by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/alicante/hotel-spa-porta-maris-by-melia',
    }

    ,
    {
        title: "Meliá Alicante",
        description: "Meliá Alicante",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/alicante/melia-alicante',
    }
    ,
    {
        title: "The Level at Meliá Villaitana",
        description: "The Level at Meliá Villaitana",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/benidorm/the-level-at-melia-villaitana',
    }
    
    ,
    {
        title: "Meliá Villaitana",
        description: "Meliá Villaitana",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/benidorm/melia-villaitana',
    }

    ,
    {
        title: "Hotel Suites del Mar by Meliá",
        description: "Hotel Suites del Mar by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/alicante/hotel-suites-del-mar-by-melia',
    }
    ,
    {
        title: "The Level at Meliá Alicante",
        description: "The Level at Meliá Alicante",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/alicante/the-level-at-melia-alicante',
    }
    ,
    {
        title: "Hotel Alicante Gran Sol Affiliated by Meliá",
        description: "Hotel Alicante Gran Sol Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/alicante/hotel-alicante-gran-sol-by-melia',
    }
    ,
    {
        title: "Sol Los Fenicios",
        description: "Sol Los Fenicios",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/almunecar/sol-los-fenicios',
    }
    ,
    {
        title: "Palacio de Avilés Affiliated by Meliá",
        description: "Palacio de Avilés Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/aviles/palacio-de-aviles-by-melia',
    }
    ,
    {
        title: "Meliá de Tredós",
        description: "Meliá de Tredós",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/baqueira-beret/melia-tredos-baqueira',
    }
    ,
    {
        title: "Hotel Vielha Val d'Arán Affiliated by Meliá",
        description: "Hotel Vielha Val d'Arán Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/vielha-valle-de-aran/hotel-vielha-baqueira-by-melia',
    }
    ,
    {
        title: "Meliá Barcelona Sarrià",
        description: "Meliá Barcelona Sarrià",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/melia-barcelona-sarria',
    }
    ,
    {
        title: "Meliá Barcelona Sarrià",
        description: "Meliá Barcelona Sarrià",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/melia-barcelona-sarria',
    }
    ,
    {
        title: "Hotel Barcelona Condal Mar Affiliated by Meliá",
        description: "Hotel Barcelona Condal Mar Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/hotel-barcelona-condal-mar-by-melia',
    }
    ,
    {
        title: "INNSiDE Barcelona Apolo",
        description: "INNSiDE Barcelona Apolo",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/innside-barcelona-apolo',
    }

    ,
    {
        title: "ME Barcelona",
        description: "ME Barcelona",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/me-barcelona',
    }
    ,
    {
        title: "Torre Melina Gran Meliá",
        description: "Torre Melina Gran Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/torre-melina-gran-melia',
    }
    ,
    {
        title: "INNSiDE Barcelona Aeropuerto",
        description: "INNSiDE Barcelona Aeropuerto",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/hotel-barcelona-aeropuerto-by-melia',
    }

    ,
    {
        title: "The Level at Meliá Barcelona Sky",
        description: "The Level at Meliá Barcelona Sky",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/the-level-at-melia-barcelona-sky',
    }

    ,
    {
        title: "Meliá Barcelona Sky",
        description: "Meliá Barcelona Sky",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/barcelona/melia-barcelona-sky',
    }
    ,
    {
        title: "Sol Timor Apartamentos",
        description: "Sol Timor Apartamentosy",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/torremolinos/sol-timor-apartamentos',
    }

    ,
    {
        title: "Hotel Las Arenas Affiliated by Meliá",
        description: "Hotel Las Arenas Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/benalmadena/hotel-las-arenas-by-melia',
    }
    ,
    {
        title: "Hotel Ocean House Costa del Sol Affiliated by Meliá",
        description: "Hotel Ocean House Costa del Sol Affiliated by Meliá",
        // 25.1890259,55.2668307
        coordinates: [55.2668307,25.1890259], // Coordenadas aproximadas para Puerto Vallarta
        images: [],
        mapsLink: '',
        hotelsLink: 'https://www.melia.com/es/hoteles/espana/torremolinos/hotel-ocean-house-costa-del-sol-by-melia',
    }

    ,


    {
    title: "GRAN MELIÁ PALACIO de ISORA",
    description: "GRAN MELIÁ PALACIO de ISORA",
    coordinates: [-16.840056, 28.291565], // Coordenadas aproximadas para Tenerife
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/tenerife/gran-melia-palacio-de-isora',
},
{
    title: "PARADISUS Gran CANARIA",
    description: "PARADISUS Gran CANARIA",
    coordinates: [-15.547687, 27.920220], // Coordenadas aproximadas para Gran Canaria
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/gran-canaria/paradisus-gran-canaria',
},
{
    title: "PARADISUS Salinas LANZAROTE",
    description: "PARADISUS Salinas LANZAROTE",
    coordinates: [-13.663429, 29.046854], // Coordenadas aproximadas para Lanzarote
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/lanzarote/paradisus-salinas-lanzarote',
},
{
    title: "HOTEL Don PEPE GRAN MELIÁ",
    description: "HOTEL Don PEPE GRAN MELIÁ",
    coordinates: [-4.882447, 36.510071], // Coordenadas aproximadas para Marbella
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/marbella/gran-melia-don-pepe',
},
{
    title: "GRAN MELIÁ Sancti PETRI",
    description: "GRAN MELIÁ Sancti PETRI",
    coordinates: [-6.196194, 36.416141], // Coordenadas aproximadas para Cádiz
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/cadiz/gran-melia-sancti-petri',
},
{
    title: "HOTEL de MAR GRAN MELIÁ",
    description: "HOTEL de MAR GRAN MELIÁ",
    coordinates: [2.650160, 39.639039], // Coordenadas aproximadas para Mallorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/hotel-de-mar-gran-melia',
},
{
    title: "VILLA LE BLANC GRAN MELIÁ",
    description: "VILLA LE BLANC GRAN MELIÁ",
    coordinates: [4.080148, 39.998022], // Coordenadas aproximadas para Menorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/menorca/villa-le-blanc-gran-melia',
},
{
    title: "MELIÁ Zahara ATLANTERRA",
    description: "MELIÁ Zahara ATLANTERRA",
    coordinates: [-6.035862, 36.139349], // Coordenadas aproximadas para Cádiz
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/cadiz/melia-zahara-atlanterra',
},
{
    title: "MELIÁ Cala GALDANA",
    description: "MELIÁ Cala GALDANA",
    coordinates: [4.013758, 39.939335], // Coordenadas aproximadas para Menorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/menorca/melia-cala-galdana',
},
{
    title: "HACIENDA DEL CONDE MELIÁ COLLECTION",
    description: "HACIENDA DEL CONDE MELIÁ COLLECTION",
    coordinates: [-16.833298, 28.349839], // Coordenadas aproximadas para Tenerife
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/tenerife/hacienda-del-conde-melia-collection',
},
{
    title: "MELIÁ VILLATANA",
    description: "MELIÁ VILLATANA",
    coordinates: [-0.132020, 38.538232], // Coordenadas aproximadas para Benidorm
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/benidorm/melia-villatana',
},
{
    title: "MELIÁ CALVIÁ BEACH",
    description: "MELIÁ CALVIÁ BEACH",
    coordinates: [2.528399, 39.512684], // Coordenadas aproximadas para Mallorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/melia-calvia-beach',
},
{
    title: "MELIÁ SOUTH BEACH",
    description: "MELIÁ SOUTH BEACH",
    coordinates: [2.535600, 39.514820], // Coordenadas aproximadas para Mallorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/melia-south-beach',
},
{
    title: "SOL BARBADOS",
    description: "SOL BARBADOS",
    coordinates: [2.530733, 39.515489], // Coordenadas aproximadas para Calviá
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/sol-barbados',
},
{
    title: "PARADISUS PLAYA del CARMEN",
    description: "PARADISUS PLAYA del CARMEN",
    coordinates: [-87.081470, 20.629558], // Coordenadas aproximadas para Playa del Carmen
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/mexico/playa-del-carmen/paradisus-playa-del-carmen',
},

//inicio ia
{
    title: "GRAN MELIÁ PALACIO de ISORA",
    description: "GRAN MELIÁ PALACIO de ISORA",
    coordinates: [-16.840056, 28.291565], // Coordenadas aproximadas para Tenerife
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/tenerife/gran-melia-palacio-de-isora',
},
{
    title: "PARADISUS Gran CANARIA",
    description: "PARADISUS Gran CANARIA",
    coordinates: [-15.547687, 27.920220], // Coordenadas aproximadas para Gran Canaria
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/gran-canaria/paradisus-gran-canaria',
},
{
    title: "PARADISUS Salinas LANZAROTE",
    description: "PARADISUS Salinas LANZAROTE",
    coordinates: [-13.663429, 29.046854], // Coordenadas aproximadas para Lanzarote
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/lanzarote/paradisus-salinas-lanzarote',
},
{
    title: "HOTEL Don PEPE GRAN MELIÁ",
    description: "HOTEL Don PEPE GRAN MELIÁ",
    coordinates: [-4.882447, 36.510071], // Coordenadas aproximadas para Marbella
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/marbella/gran-melia-don-pepe',
},
{
    title: "GRAN MELIÁ Sancti PETRI",
    description: "GRAN MELIÁ Sancti PETRI",
    coordinates: [-6.196194, 36.416141], // Coordenadas aproximadas para Cádiz
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/cadiz/gran-melia-sancti-petri',
},
{
    title: "HOTEL de MAR GRAN MELIÁ",
    description: "HOTEL de MAR GRAN MELIÁ",
    coordinates: [2.650160, 39.639039], // Coordenadas aproximadas para Mallorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/hotel-de-mar-gran-melia',
},
{
    title: "VILLA LE BLANC GRAN MELIÁ",
    description: "VILLA LE BLANC GRAN MELIÁ",
    coordinates: [4.080148, 39.998022], // Coordenadas aproximadas para Menorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/menorca/villa-le-blanc-gran-melia',
},
{
    title: "MELIÁ Zahara ATLANTERRA",
    description: "MELIÁ Zahara ATLANTERRA",
    coordinates: [-6.035862, 36.139349], // Coordenadas aproximadas para Cádiz
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/cadiz/melia-zahara-atlanterra',
},
{
    title: "MELIÁ Cala GALDANA",
    description: "MELIÁ Cala GALDANA",
    coordinates: [4.013758, 39.939335], // Coordenadas aproximadas para Menorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/menorca/melia-cala-galdana',
},
{
    title: "HACIENDA DEL CONDE MELIÁ COLLECTION",
    description: "HACIENDA DEL CONDE MELIÁ COLLECTION",
    coordinates: [-16.833298, 28.349839], // Coordenadas aproximadas para Tenerife
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/tenerife/hacienda-del-conde-melia-collection',
},
{
    title: "MELIÁ VILLATANA",
    description: "MELIÁ VILLATANA",
    coordinates: [-0.132020, 38.538232], // Coordenadas aproximadas para Benidorm
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/benidorm/melia-villatana',
},
{
    title: "MELIÁ CALVIÁ BEACH",
    description: "MELIÁ CALVIÁ BEACH",
    coordinates: [2.528399, 39.512684], // Coordenadas aproximadas para Mallorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/melia-calvia-beach',
},
{
    title: "MELIÁ SOUTH BEACH",
    description: "MELIÁ SOUTH BEACH",
    coordinates: [2.535600, 39.514820], // Coordenadas aproximadas para Mallorca
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/melia-south-beach',
},
{
    title: "SOL BARBADOS",
    description: "SOL BARBADOS",
    coordinates: [2.530733, 39.515489], // Coordenadas aproximadas para Calviá
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/espana/mallorca/sol-barbados',
},
{
    title: "PARADISUS PLAYA del CARMEN",
    description: "PARADISUS PLAYA del CARMEN",
    coordinates: [-87.081470, 20.629558], // Coordenadas aproximadas para Playa del Carmen
    images: [],
    mapsLink: '',
    hotelsLink: 'https://www.melia.com/es/hoteles/mexico/playa-del-carmen/paradisus-playa-del-carmen',
}


    //img spania

    // GRAN MELIÁ PALACIO de ISORA Tenerife, España

    // PARADISUS Gran CANARIA Gran Canaria, España

    // PARADISUS Salinas LANZAROTE
    // Lanzarote, España

    //     HOTEL Don PEPE GRAN MELIÁ
    // Marbella, España

    // GRAN MELIÁ Sancti PETRI
    // Cádiz, España


    // HOTEL de MAR GRAN MELIÁ
    // Mallorca, España

    // VILLA LE BLANC GRAN MELIÁ
    // Menorca, España


    // MELIÁ Zahara ATLANTERRA
    // Cádiz, España

    // MELIÁ Cala GALDANA
    // Menorca, España


//     HACIENDA DEL CONDE MELIÁ COLLECTION
// Tenerife, España

// MELIÁ VILLATANA
// Benidorm, España

// MELIÁ CALVIÁ BEACH
// Mallorca, España

// MELIÁ SOUTH BEACH
// Mallorca, España


// SOL BARBADOS
// Mallorca - Calviá, España

// PARADISUS PLAYA del CARMEN
// Playa del Carmen, México




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
