<?php

$host = "localhost";
$usuario = "root";
$contraseña = "";
$base_de_datos = "base de datos de libros";

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Capturar datos del formulario con validación
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : null;
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
$edad = isset($_POST['edad']) ? $_POST['edad'] : null;
$correo = isset($_POST['correo']) ? $_POST['correo'] : null;
$contraseña = isset($_POST['password']) ? $_POST['password'] : null;
$ulti = isset($_POST['ultima_lectura']) ? $_POST['ultima_lectura'] : null;
$autor = isset($_POST['autor']) ? $_POST['autor'] : null;
$genero = isset($_POST['genero']) ? $_POST['genero'] : null;
$apubli = isset($_POST['a_publicacion']) ? $_POST['a_publicacion'] : null;

// Validar datos
if (!$id_usuario || !$nombre || !$edad || !$correo || !$contraseña || !$ulti || !$autor || !$genero || !$apubli) {
    die("Por favor, completa todos los campos del formulario.");
}


// Iniciar transacción
$conexion->begin_transaction();

try {
    // Insertar datos en la tabla usuario
    $stmt_usuario = $conexion->prepare(
        "INSERT INTO usuario (`id_usuario`, `nombre`, `edad`, `correo`, `password`, `ultima_lectura`, `autor`, `genero`, `a_publicacion`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt_usuario->bind_param("isisssssi", $id_usuario, $nombre, $edad, $correo, $contraseña, $ulti, $autor, $genero, $apubli);
    $stmt_usuario->execute();

    // Confirmar transacción
    $conexion->commit();

    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conexion->rollback();
    echo "Error al insertar los datos: " . $e->getMessage();
}

// Mostrar datos de la tabla usuario
$sql = "SELECT * FROM usuario";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Edad</th><th>Última Lectura</th><th>Autor</th><th>Género</th><th>Año de Publicación</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_usuario"] . "</td>";
        
        echo "<td>" . $row["edad"] . "</td>";
       
        echo "<td>" . $row["ultima_lectura"] . "</td>";
        echo "<td>" . $row["autor"] . "</td>";
        echo "<td>" . $row["genero"] . "</td>";
        echo "<td>" . $row["a_publicacion"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay datos";
}

// Cerrar conexión
$conexion->close();

?>
