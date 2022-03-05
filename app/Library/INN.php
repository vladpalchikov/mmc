<?php

namespace MMC\Library;

use Sunra\PhpSimple\HtmlDomParser;

class INN
{
	private $url = 'https://service.nalog.ru/inn.do';

	function __construct(){}

	private function checkUrl()
	{
		$file = $this->url;
		$file_headers = @get_headers($file);
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
		    return false;
		}
		else {
		    return true;
		}
	}

	public function getCaptcha()
	{
		if ($this->checkUrl()) {
			$html = '';
			try {
				if (!$html = file_get_contents($this->url, false, stream_context_create(["ssl"=> ["verify_peer" => false, "verify_peer_name" => false]]))) {
					return [
			        	'status' => false,
			        	'captchaToken' => '',
			        	'error' => 'Связаться с сервером не удалось, попробуйте позже'
			        ];
				}

				if (!empty($html)) {
					$dom = HtmlDomParser::str_get_html($html);
					if (isset($dom->find('input[name="captchaToken"]')[0])) {
						$captchaToken = $dom->find('input[name="captchaToken"]')[0]->attr['value'];

						return [
				        	'status' => true,
				        	'captchaToken' => $captchaToken
				        ];
					} else {
						return [
				        	'status' => false,
				        	'captchaToken' => '',
				        	'error' => 'Связаться с сервером не удалось, попробуйте позже'
				        ];
					}
				} else {
					return [
			        	'status' => false,
			        	'captchaToken' => '',
			        	'error' => 'Связаться с сервером не удалось, попробуйте позже'
			        ];
				}
			} catch (\Exception $e) {
				return [
		        	'status' => false,
		        	'captchaToken' => '',
		        	'error' => 'Связаться с сервером не удалось, попробуйте позже'
		        ];
			}

		} else {
			return [
	        	'status' => false,
	        	'captchaToken' => '',
	        	'error' => 'Связаться с сервером не удалось, попробуйте позже'
	        ];
		}
	}

	public function getInn($foreignerData, $captchaToken, $captcha)
	{
		$client = new \GuzzleHttp\Client();
		try {
			$params = [
				'c' => 'innMy',
				'fam' => $foreignerData['surname'],
				'nam' => $foreignerData['name'],
				'bdate' => date('d.m.Y', strtotime($foreignerData['birthday'])),
				'doctype' => '10',
				'bplace' => '',
				'docno' => $foreignerData['document'],
				'docdt' => date('d.m.Y', strtotime($foreignerData['document_date'])),
				'captcha' => $captcha,
				'captchaToken' => $captchaToken
            ];

            if (!empty($foreignerData['middle_name'])) {
            	$params['otch'] = $foreignerData['middle_name'];
            } else {
            	$params['opt_otch'] = 1;
            }

			$res = $client->request('POST', 'https://service.nalog.ru/inn-proc.do', [
				'verify' => false,
	            'form_params' => $params
	        ]);

	        $response = $res->getBody();
	        $data = json_decode($res->getBody());

	        if (isset($data->inn)) {
	        	return [
		        	'status' => true,
		        	'inn' => $data->inn
		        ];
	        } else {
	        	return [
		        	'status' => false,
		        	'inn' => 0,
		        	'error' => 'Для данного гражданина невозможно определить ИНН, возможно данные гражданина указаны неправильно.'
		        ];
	        }

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
			$errors = json_decode($responseBodyAsString = $response->getBody()->getContents());
			$errorsString = '';
			foreach ($errors->ERRORS as $error) {
				$errorsString .= $error[0]."<br>";
			}

			return [
	        	'status' => false,
	        	'inn' => '',
	        	'error' => $errorsString
	        ];
		}
	}
}
