<?php

namespace App\Services;

class GoogleGeocode
{
    private $api_key = null;

    public function setAuthorizationKey($api_key)
    {
        if (empty($api_key)) {
            throw new \InvalidArgumentException('please set the api key');
        }

        $this->api_key = $api_key;

        return $this;
    }

    private function getAuthorizationKey()
    {
        if (is_null($this->api_key)) {
            $this->api_key = env('GOOGLE_MAP_API_KEY', '');
        }

        return $this->api_key;
    }
}
