<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleGeocode
{
    const MAP_API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';
    const REPLACE_PATTERN = '/\s|\(.*\)/';

    private $api_key = null;
    private $resource_format = false;

    public function format(bool $enable)
    {
        $this->resource_format = $enable;
        return $this;
    }

    public function setAuthorizationKey($api_key)
    {
        if (empty($api_key)) {
            throw new \InvalidArgumentException('please set the api key');
        }

        $this->api_key = $api_key;

        return $this;
    }

    public function fire($address_list)
    {
        $output = [];

        $address_list = (array) $address_list;

        foreach ($address_list as $target) {
            $res = Http::get(self::MAP_API_URL, [
                'address' => $this->formatSourceAddress($target),
                'key' => $this->getAuthorizationKey(),
                'language' => 'zh-TW',
            ]);

            $data = $res->json();

            if ($this->resource_format) {
                $data = $this->parse($data);
            }

            $output[$target] = [
                'address' => $data,
            ];
        }

        return $output;
    }

    private function formatSourceAddress($address)
    {
        return preg_replace(self::REPLACE_PATTERN, '', mb_convert_kana($address, 'rns'));
    }

    private function getAuthorizationKey()
    {
        if (is_null($this->api_key)) {
            $this->api_key = env('GOOGLE_MAP_API_KEY', '');
        }

        return $this->api_key;
    }

    private function parse($response_data)
    {
        $info = $response_data['results'][0] ?? [];
        $address_components = $info['address_components'];
        $address_mapping = collect($address_components)->map(function ($item, $key) {
            $type = collect($item['types'])->reject(function ($value, $type) {
                return $value === 'political';
            })->first();
            return [$type => $item['long_name']];
        })->collapse();

        $output = [
            'info' => $address_mapping,
            'geometry' => $info['geometry'],
            'formatted_address' => $info['formatted_address'],
        ];

        return $output;
    }
}
