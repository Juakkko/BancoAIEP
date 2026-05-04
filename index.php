<!DOCTYPE html>
<html lang="en">

//hola
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>BancoAIEP</title>
</head>
  <!------Inicio de sesion----->

<div class="logincuadrado">
<h3 class="mb-3">Iniciar Sesión</h3>

  <form action="login.php" method="POST">
    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <input type="text" name="usuario" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
  </form>
</div>


  <?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger mt-3">
    Usuario o contraseña incorrectos
  </div>
  <?php endif; ?>



<body>






  <!------JS------>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  </div>
</body>

</html>