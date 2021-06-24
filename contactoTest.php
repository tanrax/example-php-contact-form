<?php
use PHPUnit\Framework\TestCase;

require_once('vendor/autoload.php');
require_once('contactoModel.php');

class ContactoTest extends TestCase
{
    public function testObligatorio(): void
    {
        $this->assertSame(true, validarObligatorio('texto'), "Validador obligatorio no reconoce  'texto'");
        $this->assertSame(false, validarObligatorio(''), "Validador obligatorio no reconoce  ''");
    }

    public function testFormatoEmail(): void
    {
        // Buenos
        $miFaker =  Faker\Factory::create();
        foreach (range(0, 50) as $pos) {
            $tempEmail = $miFaker->email;
            $this->assertSame(true, validarFormatoEmail($tempEmail), "Validador email $tempEmail valido");
        }
        // Malos
        $this->assertSame(false, validarFormatoEmail('@correo'), "Validador falso email");
        $this->assertSame(false, validarFormatoEmail('@correo.com'), "Validador falso email");
        $this->assertSame(false, validarFormatoEmail('mi@correo'), "Validador falso email");
        $this->assertSame(false, validarFormatoEmail('micorreo'), "Validador falso email");
        $this->assertSame(false, validarFormatoEmail('micorreo@ff.e'), "Validador falso email");
        $this->assertSame(false, validarFormatoEmail('micorreo@f-f.e'), "Validador falso email");
    }


    public function testLetrasMaximas(): void
    {

        // Funciona - Limite 20 con caracteres de 0 a 20
        foreach (range(0, 20) as $pos) {
            $tempText = generateRandomString($pos);
            $this->assertSame(true, validarLetrasMaximas($tempText, 20), "Validador limite $pos maximo '$tempText'");
        }

        // No funciona - Limite 20 con caracteres de 21 a 40
        foreach (range(21, 40) as $pos) {
            $tempText = generateRandomString($pos);
            $this->assertSame(false, validarLetrasMaximas($tempText, 20), "Validador limite $pos maximo '$tempText'");
        }
    }
}

