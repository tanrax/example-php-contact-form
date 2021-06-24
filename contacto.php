<?php
require_once('contactoModel.php');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacto</title>
    <style>
        .contacto__error {
            color: red;
        }

        .contacto__enviado {
            color: green;
        }
    </style>
</head>
<body>
    <main>
        <h1>Formulario de contacto</h1>
        <section class="contacto">
            <form method="post" novalidate>
                <p>
                    <label>
                        Nombre
                        <input type="text" name="nombre" value="<?= $nombre ?>">
                    </label>
                </p>
                <?php if (isPost() && !validarObligatorio($nombre)): ?>
                <p class="contacto__error">
                    Campo obligatorio
                </p>
                <?php endif; ?>
                <p>
                    <label>
                        E-mail
                        <input type="email" name="email" value="<?= $email ?>">
                    </label>
                </p>
                <?php if (isPost() && !validarObligatorio($email)): ?>
                <p class="contacto__error">
                    Campo obligatorio
                </p>
                <?php endif; ?>
                <?php if (isPost() && !validarFormatoEmail($email)): ?>
                <p class="contacto__error">
                    Formato no valido
                </p>
                <?php endif; ?>
                <p>
                    <label>
                        Mensaje
                        <textarea name="mensaje"><?= $mensaje ?></textarea>
                    </label>
                </p>
                <?php if (isPost() && !validarObligatorio($mensaje)): ?>
                <p class="contacto__error">
                    Campo obligatorio
                </p>
                <?php endif; ?>
                <?php if (isPost() && !validarLetrasMaximas($mensaje, 20)): ?>
                <p class="contacto__error">
                    Debe tener mas de 20 caracteres
                </p>
                <?php endif; ?>
                <p>
                    <input type="checkbox" name="acepto"<?= $acepto ? ' checked': '' ?>> Acepto que rastres y vendas mis datos
                </p>
                <?php if (isPost() && !$acepto): ?>
                <p class="contacto__error">
                    Debes aceptar nuestras condiciones
                </p>
                <?php endif; ?>
                <?php if ($validado): ?>
                <p class="contacto__enviado">
                    Enviado con exito
                </p>
                <?php endif; ?>
                <p>
                    <button type="submit">Enviar</button>
                </p>
            </form>
        </section>
    </main>
</body>
</html>