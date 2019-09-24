```php
    /**
     * Retorna el SRC de una imagen PNG con el barcode del texto enviado.
     * Basado en: https://github.com/davidscotttufts/php-barcode
     * y en: https://github.com/elminson/barcode
     *
     * Tipo: code128. code128b, code128a.
     *
     * Orientación: horizontal
     */
    final public function getBarcodeSRC($text)
    {
        $imagenBordeX = 6; //Default 2. Sólo números pares.
        $orientation = 'horizontal';
        $code_type = 'b'; //Default b. [b|a]
        $img_altura = 16; //Default:20
        $print = false;
        $stretch = 2;
        $density = 1;

        $code_string = '';

        // Translate the $text into barcode the correct $code_type
        if ('b' == $code_type) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ($X = 1; $X <= strlen($text); $X++) {
                $activeKey = substr($text, ($X - 1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum = ($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
            $code_string = "211214" . $code_string . "2331112";
        } elseif ('a' == $code_type) {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "NUL" => "111422", "SOH" => "121124", "STX" => "121421", "ETX" => "141122", "EOT" => "141221", "ENQ" => "112214", "ACK" => "112412", "BEL" => "122114", "BS" => "122411", "HT" => "142112", "LF" => "142211", "VT" => "241211", "FF" => "221114", "CR" => "413111", "SO" => "241112", "SI" => "134111", "DLE" => "111242", "DC1" => "121142", "DC2" => "121241", "DC3" => "114212", "DC4" => "124112", "NAK" => "124211", "SYN" => "411212", "ETB" => "421112", "CAN" => "421211", "EM" => "212141", "SUB" => "214121", "ESC" => "412121", "FS" => "111143", "GS" => "111341", "RS" => "131141", "US" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "CODE B" => "114131", "FNC 4" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ($X = 1; $X <= strlen($text); $X++) {
                $activeKey = substr($text, ($X - 1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum = ($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
            $code_string = "211412" . $code_string . "2331112";
        }

        // Rellene los bordes del código de barras
        $code_length = $imagenBordeX; //Default 20
        if ($print) {
            $text_height = 30;
        } else {
            $text_height = 0;
        }
        for ($i = 1; $i <= strlen($code_string); $i++) {
            $code_length = $code_length + (int) (substr($code_string, ($i - 1), 1));
        }
        if (strtolower($orientation) == "horizontal") {
            $img_width = $code_length * $stretch;
            $img_height = $img_altura;
        } else {
            $img_width = $img_altura;
            $img_height = $code_length * $stretch;
        }
        $imagen = @imagecreatetruecolor($img_width, $img_height + $text_height);
        if (false === $imagen) {
            throw new Exception(_('No se puede Iniciar el nuevo flujo a la imagen GD'));
        }

        // Convertirla a paleta sin entramado y 2 colores 
        // (desactivar si se va a usar transparencia)
        imagetruecolortopalette($imagen, false, 2);
        /*
	    //Necesario si se va a usar transparencia:
	    //Desactivar la mezcla alfa y establecer la bandera alfa
	    imagealphablending($imagen, false);
	    imagesavealpha($imagen, true);
		*/
        $black = imagecolorallocate($imagen, 0, 0, 0);
        $white = imagecolorallocate($imagen, 255, 255, 255);
        //$transparent = imagecolorallocatealpha($imagen,0x00,0x00,0x00,127);
        imagefill($imagen, 0, 0, $white);
        imagesetthickness($imagen, $density);
        if ($print) {
            imagestring($imagen, 5, 31, $img_height, $text, $black);
        }
        $location = $imagenBordeX / 2; //Default 10
        for ($position = 1; $position <= strlen($code_string); $position++) {
            $cur_size = $location + (substr($code_string, ($position - 1), 1));
            if (strtolower($orientation) == "horizontal")
                imagefilledrectangle($imagen, $location * $stretch, 0, $cur_size * $stretch, $img_height, ($position % 2 == 0 ? $white : $black));
            else
                imagefilledrectangle($imagen, 0, $location * $stretch, $img_width, $cur_size * $stretch, ($position % 2 == 0 ? $white : $black));
            $location = $cur_size;
        }

        // Obtenemos el src de la imagen:
        ob_start();
        imagepng($imagen);
        $getSRC = 'data:image/png;base64,' . base64_encode(ob_get_contents());
        ob_end_clean();

        // Destruimos la imagen en memoria para liberar recursos del servidor:
        imagedestroy($imagen);

        // Retornamos el src de la imagen:
        return $getSRC;
    }

```
Ejemplo de uso:
```php
echo '<img src="' . getBarcodeSRC('Hello world') . '" alt="Barcode 128" width="346" height="16">';
```
