<!-- Core Vendors JS -->
<script src="assets/js/vendors.min.js"></script>

<!-- page js -->
<script src="assets/vendors/chartjs/Chart.min.js"></script>
<!-- <script src="assets/js/pages/dashboard-default.js"></script> -->
<!-- <script src="assets/js/app/main.js"></script> -->

<!-- Core JS -->
<script src="assets/js/app.min.js"></script>
<!-- Incluir jQuery y el plugin ElevateZoom -->
<!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->
<!-- Incluir jQuery y Fancybox -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.27/dist/fancybox.css"/>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.27/dist/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const downloadLink = document.getElementById('downloadLink');

        downloadLink.addEventListener('click', function() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 || 12; // Convert to 12-hour format
            const formattedMinutes = minutes;
            const day = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based
            const year = now.getFullYear();

            const filename = `NO_BRAINER_${formattedHours}_${formattedMinutes}_${ampm}_${day}_${month}_${year}.pdf`;

            // Update the download attribute with the new filename
            downloadLink.setAttribute('download', filename);
        });
    });
</script>

</body>
</html>
