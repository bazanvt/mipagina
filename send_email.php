<?php
// ==============================================================================
// 1. CONFIGURACIÓN
// ==============================================================================

// Dirección de correo del ejecutivo de ventas donde quieres recibir los datos.
$to = "vicente@moving.mx"; 

// Nombre que aparecerá en el asunto del correo.
$websiteName = "Mi Sitio Web de Contacto"; 

// Si no se envía por POST, redirigimos al formulario.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

// ==============================================================================
// 2. RECUPERACIÓN Y SANITIZACIÓN DE DATOS
// ==============================================================================
$nombre = htmlspecialchars(trim($_POST['nombre'] ?? 'No Proporcionado'));
$email = htmlspecialchars(trim($_POST['email'] ?? 'No Proporcionado'));
$telefono = htmlspecialchars(trim($_POST['telefono'] ?? 'No Proporcionado'));
$mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? 'Sin mensaje'));

// Validar que al menos el correo sea un formato válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Formato de correo electrónico inválido.");
}

// ==============================================================================
// 3. CONSTRUCCIÓN DEL CORREO
// ==============================================================================

// Asunto que verá el ejecutivo de ventas
$subject = "🔥 NUEVO LEAD WEB de " . $nombre;

// Cuerpo del mensaje en formato de texto plano para simplicidad
$email_body = "¡Hola equipo de ventas!\n\n";
$email_body .= "Tienes un nuevo contacto generado desde el formulario de " . $websiteName . ".\n\n";
$email_body .= "--- DATOS DEL CONTACTO ---\n";
$email_body .= "Nombre: " . $nombre . "\n";
$email_body .= "Correo: " . $email . "\n";
$email_body .= "Teléfono: " . $telefono . "\n";
$email_body .= "Mensaje:\n" . str_repeat("-", 40) . "\n" . $mensaje . "\n" . str_repeat("-", 40) . "\n\n";
$email_body .= "¡Procesa este lead rápidamente!";

// Encabezados (Headers) para asegurar que el correo se envíe correctamente
$headers = "From: Formulario Web <no-reply@" . parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// ==============================================================================
// 4. FUNCIÓN DE ENVÍO
// ==============================================================================

$success = mail($to, $subject, $email_body, $headers);

// ==============================================================================
// 5. RESPUESTA AL USUARIO
// ==============================================================================

if ($success) {
    // Redirección a una página de agradecimiento
    header("Location: index.html?status=success");
    exit;
} else {
    // Manejo de error.
    header("Location: index.html?status=error");
    exit;
}

?>
