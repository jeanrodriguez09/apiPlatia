<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\API\ResponseTrait;

class AuthFilter implements FilterInterface
{
	use ResponseTrait;

	public function before(RequestInterface $request, $arguments = null)
	{
		$key        = Services::getSecretKey();
		$authHeader = $request->getServer('HTTP_AUTHORIZATION');
		$arr        = explode(' ', $authHeader);
		log_message('debug', 'aaaa: '.json_encode($arr));
		if(is_null($arr) || empty($arr) || empty($arr[1])) return redirect()->to("$_ENV[URL_LOGIN]");
		$token      = $arr[1];
		if(is_null($token) || empty($token)) return redirect()->to("$_ENV[URL_LOGIN]");

		try{
			JWT::decode($token, new Key($key, 'HS256'));
		}
		catch (\Exception $e){
			return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
		}
	}

	//--------------------------------------------------------------------

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}