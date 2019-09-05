<?php
/**
* Basado en https://github.com/anigenero/php-barcode-generator
*/
function getBarcodeSRC($texto, $enEspanol = false) {
        if ($enEspanol) $texto = str_ireplace(chr(45), chr(47), $texto);
        $size = 16; // Default=20
        $DPI = 72; // DPI de 72
        $lengthText = strlen($texto);
        $density = 1;
        $ancho_barra = ($density / $DPI);
        $img_width = (((11 * $lengthText) + 35) * $ancho_barra);
        //$img_height = ($img_width * .15 > .7) ? $img_width * .15 : .7;
        //$img_height = ($img_height * $DPI);

        // Dimensiones:
        $img_width = round($img_width * $DPI);
        $img_height = $size;

        $CODES = array(
            212222, 222122, 222221, 121223, 121322, 131222, 122213, 122312, 132212, 221213,
            221312, 231212, 112232, 122132, 122231, 113222, 123122, 123221, 223211, 221132,
            221231, 213212, 223112, 312131, 311222, 321122, 321221, 312212, 322112, 322211,
            212123, 212321, 232121, 111323, 131123, 131321, 112313, 132113, 132311, 211313,
            231113, 231311, 112133, 112331, 132131, 113123, 113321, 133121, 313121, 211331,
            231131, 213113, 213311, 213131, 311123, 311321, 331121, 312113, 312311, 332111,
            314111, 221411, 431111, 111224, 111422, 121124, 121421, 141122, 141221, 112214,
            112412, 122114, 122411, 142112, 142211, 241211, 221114, 413111, 241112, 134111,
            111242, 121142, 121241, 114212, 124112, 124211, 411212, 421112, 421211, 212141,
            214121, 412121, 111143, 111341, 131141, 114113, 114311, 411113, 411311, 113141,
            114131, 311141, 411131, 211412, 211214, 211232, 23311120
        );
        $CODE128B_START_BASE = 103;
        $STOP = 106;

        // create a true color image at the specified width and height.
        $image = @imagecreatetruecolor($img_width, $img_height);
		
		// Convertirla a paleta sin entramado y 2 colores 
		// (desactivar si se va a usar transparencia)
        imagetruecolortopalette($image, false, 2);

        //Fill the image white
        //Set the line thickness (based on $density)
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $whiteColor);
        imagesetthickness($image, $density);

        //Create the checksum integer and the encoding array
        //Both will be assembled in the loop
        $checksum = $CODE128B_START_BASE;
        $encoding = array($CODES[$CODE128B_START_BASE]);

        //Add Code 128 values from ASCII values found in $texto
        for ($i = 0; $i < $lengthText; $i++) {
            //Add checksum value of character
            $checksum += (ord(substr($texto, $i, 1)) - 32) * ($i + 1);
            //Add Code 128 values from ASCII values found in $texto
            //Position is array is ASCII - 32
            array_push($encoding, $CODES[(ord(substr($texto, $i, 1))) - 32]);
        }

        //Insert the checksum character (remainder of $checksum/103) and STOP value
        array_push($encoding, $CODES[$checksum % 103]);
        array_push($encoding, $CODES[$STOP]);

        //Implode the array as string
        $enc_str = implode($encoding);
        $enc_str_length = strlen($enc_str);

        $blackColor = imagecolorallocate($image, 0, 0, 0);

        // //Assemble the barcode
        for ($i = 0, $x = 0, $inc = round(($density / 72) * 100); $i < $enc_str_length; $i++) {
            //Get the integer value of the string element
            $val = intval(substr($enc_str, $i, 1), 10);
            //Create lines/spaces
            //Bars are generated on even sequences, spaces on odd
            
            for ($n = 0; $n < $val; $n++, $x += $inc) {
                if ($i % 2 == 0) {
                    imageline($image, $x, 0, $x, $img_height, $blackColor);
                }
            }
        }

        ob_start();
        imagepng($image);
        $getSRC = 'data:image/png;base64,' . base64_encode(ob_get_contents());
        ob_end_clean();
        imagedestroy($image); // destruir la imagen en memoria para liberar recursos del servidor.

        return $getSRC;
    }
