<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RequestLogger implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Obtén datos POST y GET
        $postData = (array) $request->getPost();
        $getData = (array) $request->getGet();
        $varData = (array) $request->getVar();

        // Verifica si las palabras claves se encuentran dentro de los parametros enviados
        if ($this->hasSQLKeywords($postData) || $this->hasSQLKeywords($getData) || $this->hasSQLKeywords($varData)) {
            // Intenta obtener la dirección IP desde $_SERVER['HTTP_X_FORWARDED_FOR']
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';

            // Si no se encuentra en $_SERVER['HTTP_X_FORWARDED_FOR'], intenta obtenerla desde $_SERVER['REMOTE_ADDR']
            if (empty($ip)) $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

            // Obtener el país de origen usando un servicio de geolocalización (ejemplo utilizando un servicio gratuito)
            $apiUrl = "https://ipinfo.io/{$ip}/json";
            $ipInfo = json_decode(file_get_contents($apiUrl));

            // Obtener el país desde la información proporcionada por el servicio
            $pais = isset($ipInfo->country) ? $ipInfo->country : 'Desconocido';
            // log_message('debug', json_encode($ipInfo));
            // log_message('debug', "MAQUINA MALISIOSA => IP: $ip; PAIS: $pais");
			return Services::response()
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                            ->setBody('No me quieras joder la chamba manito :C');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }

    private function hasSQLKeywords(array $data)
    {
        foreach ($data as $key => $val) {
            if(is_array($val)){
                foreach ($val as $value) {
                    if(is_array($value) || is_object($value)) $value = json_encode($value);

                    $respuesta = $this->verificarPalabras($value);
                    if($respuesta) return true;
                }
            }else{
                $respuesta = $this->verificarPalabras($val);
                if($respuesta) return true;
            }
        }

        return false;
    }

    private function verificarPalabras($val){
        if(empty($val)) return false;
        // Convierte los valores a minúsculas para hacer la comparación sin distinción entre mayúsculas y minúsculas
        $lowercaseVal = strtolower($val);
        
        // Verifica si las palabras clave "select" o "where" están presentes
        $palabras_prohibidas = ['drop ', 'select ', 'from ', 'where ', 'insert ', 'update ', 'delete ', '<script>'];
        foreach ($palabras_prohibidas as $palabra) {
            if (strpos($lowercaseVal, $palabra) !== false){
                log_message('debug', "PALABRA ($palabra): ".$lowercaseVal);
                return true;
            }
        }

        return false;
    }
}
