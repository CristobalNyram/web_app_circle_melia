<script>
    let sessionCheckInterval;

    function checkSession() {
        const baseUrl = "<?php echo BASE_URL_PROJECT; ?>"; // Define la URL base desde PHP

        console.log('VerSe');

        fetch(`${baseUrl}app/api/v1/auth/?action=checkSessionStatus`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status) {
                    if (data.time_remaining <= 300) { // Si quedan 5 minutos o menos
                        clearInterval(sessionCheckInterval); // Detener el chequeo regular
                        
                        Swal.fire({
                            title: 'Sesión a punto de expirar',
                            text: 'Su sesión está a punto de expirar. Será redirigido al inicio de sesión pronto.',
                            icon: 'warning',
                            timer: 5000,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            window.location.href = `${baseUrl}pages/login/admin`; // Redirigir al login
                        }, 5000); // Espera 5 segundos antes de redirigir
                    }
                } else {
                    Swal.fire({
                        title: 'Sesión expirada',
                        text: 'Su sesión ha expirado. Será redirigido a la página de inicio de sesión.',
                        icon: 'info',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = `${baseUrl}pages/login/admin`; // Redirigir al login
                    });
                }
            })
            .catch(error => console.error('Error al verificar la sesión:', error));
    }

    function startSessionCheck() {
        // Verificar cada minuto (60000 ms)
        console.log('checkSession');
        sessionCheckInterval = setInterval(checkSession, 180000);
    }

    // Iniciar la verificación de la sesión
    startSessionCheck();
</script>
