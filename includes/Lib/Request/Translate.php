<?php

namespace Teral\Translate\Lib\Request;

/**
 * Class Translate
 */
class Translate {

    protected $apiKey;

    protected $from;

    protected $to;

    protected $texts;

    protected $request_url;

    public function __construct($apiKey, $from, $to, $texts, $request_url) {
        $this->apiKey = $apiKey;
        $this->from = $from;
        $this->to = $to;
        $this->texts = $texts;
        $this->request_url = $request_url;
    }

    /**
     * Performs a translation request
     * @return array
     */
    public function process() {

    	// Api call
    	$url = "https://translate.teral.io/v1/translate?apiKey=" . $this->apiKey;

      $client = new \GuzzleHttp\Client();

    	// Send an asynchronous request.
    	$data = [
            'from'  => $this->from,
            'to'    => $this->to,
            'texts' =>  $this->texts,
            'request_url' => $this->request_url
        ];

      $response = $client->post($url, [
    		\GuzzleHttp\RequestOptions::JSON  => $data
    	]);

      $result = $response->getBody();

      $jsonResult = json_decode($result);

      if($jsonResult &&  isset($jsonResult->success) && 1 == $jsonResult->success){
          return (array) $jsonResult->translations;
      }

      return [];
    }
}
