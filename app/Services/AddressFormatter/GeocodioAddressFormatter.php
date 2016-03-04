<?php namespace App\Services\AddressFormatter;

class  GeocodioAddressFormatter implements AddressFormatter
{
    private $raw_address;
    private $query;
    private $lookup = false;

    public function init($address) {
        $this->raw_address = $address;
        try {
            $query = \Geocodio::get($address, config('geocodio.api_key'));
            if (isset($query->response->results) && isset($query->response->results[0])) {
                $this->lookup = $query->response->results[0];
            }
        } catch (\Exception $e) {
            return;
        }
    }

    public function getRawAddress() {
        return $this->raw_address;
    }

    public function getFormattedAddress() {
        return isset($this->lookup->formatted_address) ? $this->lookup->formatted_address : $this->raw_address;
    }

    public function getStreet() {
        return trim($this->lookup->address_components->number ?? '' . ' ' . $this->lookup->address_components->formatted_street ?? '');
    }

    public function getCity() {
        return $this->lookup->address_components->city ?? '';
    }

    public function getState() {
        return $this->lookup->address_components->state ?? '';
    }

    public function getZip() {
        return $this->lookup->address_components->zip ?? '';
    }

    public function getLatitude() {
        return $this->lookup->location->lat ?? '';
    }

    public function getLongitude() {
        return $this->lookup->location->lng ?? '';
    }
}