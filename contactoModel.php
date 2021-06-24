<?php

//======================================================================
// LIBRERIAS
//======================================================================
require_once('vendor/autoload.php');

//======================================================================
// VARIABLES
//======================================================================

// Campos
$nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$acepto = isset($_REQUEST['acepto']);
$mensaje = isset($_REQUEST['mensaje']) ? $_REQUEST['mensaje'] : '';
$validado = isPost() &&
    validarObligatorio($nombre) &&
    validarObligatorio($email) &&
    validarFormatoEmail($email) &&
    validarObligatorio($mensaje) &&
    validarLetrasMaximas($mensaje, 20) &&
    $acepto;

//======================================================================
// FUNCIONES
//======================================================================

/**
 * Comprueba si estamos recibiendo el verbo POST
 * @return bool
 */
function isPost(): bool
{
    return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] === 'POST' : false;
}

/**
 * Genera un string aleatorio
 * @param int $length
 * @return string
 */
function generateRandomString(int $length = 20): string
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

/**
 * Valida que exista el valor
 * @param string $campo
 * @return bool
 */
function validarObligatorio(string $campo): bool
{
    return $campo !== '';
}

/**
 * Valida que tenga un formato de email
 * @param string $campo
 * @return bool
 */
function validarFormatoEmail(string $campo): bool
{
    // Extension one letter> boo@foo.a
    if (filter_var($campo, FILTER_VALIDATE_EMAIL)) {
       $emailSeparadoExtension = explode('.', $campo);
       if (count($emailSeparadoExtension) === 2
           && strlen($emailSeparadoExtension[1]) < 2) {
           return false;
       }
    }
    // Otros
    return filter_var($campo, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida que $texto no supere $limite
 * @param string $texto
 * @param int $limite
 * @return bool
 */
function validarLetrasMaximas(string $texto, int $limite): bool
{
    return strlen(trim($texto)) <= $limite;
}

//======================================================================
// INICIO
//======================================================================

// Envia el correo si al informacion es correcta
if ($validado) {

    // Generamos las plantillas
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    $variablesEmail = [
        'nombre' => $nombre,
        'email' => $email,
        'mensaje' => $mensaje
    ];
    $plantillaPlana =  $twig->render('contacto.txt', $variablesEmail);
    $plantillaHTML =  $twig->render('contacto.html', $variablesEmail);

    // Enviamos email
    $emailSendGrid = new \SendGrid\Mail\Mail();
    $emailSendGrid->setFrom("andros@fenollosa.email", "Web");
    $emailSendGrid->setSubject("Contacto desde mi web");
    $emailSendGrid->addTo("andros@fenollosa.email", "Yo");
    $emailSendGrid->addContent("text/plain", $plantillaPlana);
    $emailSendGrid->addContent( "text/html", $plantillaHTML);
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    try {
        $response = $sendgrid->send($emailSendGrid);
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
}

