<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="alert alert-success">
        Bienvenido, <?php echo $_SESSION['usuario']; ?>
    </div>

    <a href="cerrarsesion.php" class="btn btn-danger">Cerrar Sesión</a>
</div>

</body>
</html>