<?php

set_time_limit(0);
require_once 'zoho.php';

$access_token = getToken();



// Ruta de la carpeta principal
$carpeta_principal = "D:\sitios\backups\\";

// Obtener la lista de carpetas en la carpeta principal
$carpetas = array_diff(scandir($carpeta_principal, SCANDIR_SORT_ASCENDING), array('..', '.'));

// Tomar las 4 carpetas más antiguas
$carpetas_mas_antiguas = array_slice($carpetas, 0, 4);

// Iterar sobre las carpetas
foreach ($carpetas_mas_antiguas as $carpeta) {
    $ruta_carpeta = $carpeta_principal . $carpeta;
    var_dump($carpeta);
    // Subir la carpeta a Zoho WorkDrive aquí (usando la API)

     $id_carpeta = creaCarpeta($access_token,$carpeta);

    // Puedes usar una función recursiva para obtener la lista de archivos en la carpeta
    $archivos = obtenerListaArchivos($ruta_carpeta);

    // Iterar sobre los archivos y subir cada uno a Zoho WorkDrive
    foreach ($archivos as $archivo) {
        // Subir archivo a Zoho WorkDrive aquí (usando la API)
        // Puedes utilizar la misma lógica que se proporcionó en el primer ejemplo
        // Asegúrate de ajustar las rutas según sea necesario

    	var_dump($archivo);
    	$nombre_archivo = obtenerNombreArchivo($archivo);
    	$id_aleatorio = generarIdAleatorio();

    	$suben_archivos = subeArchivo($access_token,$archivo,$nombre_archivo,$id_carpeta,$id_aleatorio);

    	var_dump($suben_archivos);

    }

    // Eliminar la carpeta local después de subir todos los archivos
    eliminarCarpetaRecursiva($ruta_carpeta);
}



function obtenerNombreArchivo($ruta_archivo) {
    // Utiliza la función pathinfo para obtener el nombre del archivo de la ruta
    $info_archivo = pathinfo($ruta_archivo);
    return $info_archivo['basename'];
}

function generarIdAleatorio() {
    // Generar un ID aleatorio de 7 dígitos combinando los números del 1 al 9
    $id_aleatorio = '';
    $numeros_disponibles = range(1, 9);

    for ($i = 0; $i < 7; $i++) {
        $id_aleatorio .= $numeros_disponibles[array_rand($numeros_disponibles)];
    }

    return $id_aleatorio;
}


// Función para obtener la lista de archivos en una carpeta de manera recursiva
function obtenerListaArchivos($carpeta) {
    $archivos = [];

    $contenido = scandir($carpeta);
    foreach ($contenido as $elemento) {
        if ($elemento !== "." && $elemento !== "..") {
            $ruta_elemento = $carpeta . '\\' . $elemento;
            if (is_dir($ruta_elemento)) {
                $archivos = array_merge($archivos, obtenerListaArchivos($ruta_elemento));
            } else {
                $archivos[] = $ruta_elemento;
            }
        }
    }

    return $archivos;
}

// Función para eliminar una carpeta de manera recursiva
function eliminarCarpetaRecursiva($carpeta) {
    $archivos = glob($carpeta . '\*');
    foreach ($archivos as $archivo) {
        is_dir($archivo) ? eliminarCarpetaRecursiva($archivo) : unlink($archivo);
    }
    rmdir($carpeta);
}


function getToken() {

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('client_id' => 'xxxxxxxxxxxxxxxxxxxxx','client_secret' => 'XXXXXXXXXXXXXXXXXXXXXXXXX','refresh_token' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX','grant_type' => 'refresh_token'),
        CURLOPT_HTTPHEADER => array(
            'Cookie: _zcsr_tmp=8810bdaa-ab3a-456f-a228-b0bdd9a43db5; b266a5bf57=57c7a14afabcac9a0b9dfc64b3542b70; iamcsr=8810bdaa-ab3a-456f-a228-b0bdd9a43db5'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        $data = json_decode($response, true);
        return $data['access_token'];

}


function creaCarpeta($access_token,$carpeta) {


    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.zohoapis.com/workdrive/api/v1/files',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
   "data": {
      "attributes": {
         "name": "'.$carpeta.'",
         "parent_id": "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
      },
      "type": "files"
   }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$access_token.'',
    'Cookie: 60503825ae=9e078e2b68767b3d7c09e1c4d12fb332; JSESSIONID=562093C7B57739C1CF8BCFB72370EB57; _zcsr_tmp=887dd2c7-fd08-4b8d-a90f-0e8059c834be; zpcc=887dd2c7-fd08-4b8d-a90f-0e8059c834be'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$data = json_decode($response, true);
    return $data['data']['id'];
//echo $response;
  

}

function subeArchivo($access_token,$archivo,$nombre_archivo,$id_carpeta,$id_aleatorio) {

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://upload.zoho.com/workdrive-api/v1/stream/upload',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($archivo)),
      CURLOPT_HTTPHEADER => array(
        'x-filename: '.$nombre_archivo.'',
        'x-parent_id: '.$id_carpeta.'',
        'upload-id: '.$id_aleatorio.'',
        'x-streammode: 1',
        'Authorization: Bearer '.$access_token.''
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $data = json_decode($response, true);
    return $data;


}
?>

