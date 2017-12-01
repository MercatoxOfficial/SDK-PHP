<?php

/**
 * Class MXTrade
 *
 * @package app\models
 *
 *************************
 * Private methods
 *************************
 *
 * @method getBalance(array $array)
 * @method getOrders(array $array)
 * @method order(array $array)
 * @method cancel(array $array)
 *
 *************************
 * Public methods
 *************************
 *
 * @method book(string $symbol)
 * @method symbols()
 */
 
class MXTrade
{
    const URL_API = 'https://mercatox.com/api';
    const PUBLIC_URL = self::URL_API . '/public';
    const PRIVATE_URL = self::URL_API . '/private';

    private $private;
    private $public;
    public $access = 'public';

    public function __construct($public_key, $private_key)
    {
        $this->public = $public_key;
        $this->private = $private_key;
    }

    private function curl($method, $data)
    {
        $access = $this->access;

        $url = ($access === 'public' ? self::PUBLIC_URL : self::PRIVATE_URL) . '/' . $method . ($access === 'public' ? '/' . $data : '');
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $data['public'] = $this->public;

        $data = base64_encode(json_encode($data));

        $signature = hash_hmac('sha512', $data, $this->private);

        if ($access === 'private') {
            $data = [
                'data'      => $data,
                'signature' => $signature
            ];

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $curl_err = curl_error($curl);

        curl_close($curl);

        if ($response !== false && $info['http_code'] == 200) return $response; else return $curl_err;

    }

    public function __call($name, $arguments)
    {
        return json_decode($this->curl($name, $arguments[0]), 1);
    }

    public function pub()
    {
        $this->access = 'public';

        return $this;
    }

    public function priv()
    {
        $this->access = 'private';

        return $this;
    }

}
