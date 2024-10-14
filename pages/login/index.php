<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Rol</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container text-center" style="margin-top: 50px;">
    <h1 class="mb-5">Selecciona tu Rol</h1>
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="window.location.href='equipos'">Soy Vendedor</button>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-secondary btn-lg btn-block" onclick="window.location.href='admin'">Soy Admin</button>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>