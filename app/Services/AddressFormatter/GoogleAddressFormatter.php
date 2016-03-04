<?php namespace App\Services\AddressFormatter;

class  GoogleAddressFormatter implements AddressFormatter
{
    private $raw_address;
    private $query;
    private $lookup = false;

    private function findAddressComponent($name) {

        if (!$this->lookup || ! $this->lookup->address_components) {
            return '';
        }


        foreach ($this->lookup->address_components as $component) {
            if (in_array($name,$component->types)) {
                return $component->long_name;
            }
        }

        return '';
    }

    public function init($address) {

        $this->raw_address = $address;
        try {
            $param = array(
                "address"=>$address,
                "components"=>"country:US"
            );
            $response = \Geocoder::geocode('json', $param);
            $response = json_decode($response);
            if (count($response->results)>0 && $response->results[0]->formatted_address != "United States") {
                $this->lookup = $response->results[0];
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
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
        $number = $this->findAddressComponent('street_number');
        $street = $this->findAddressComponent('route');
        return implode(' ',[$number,$street]);
    }

    public function getCity() {
        return $this->findAddressComponent('locality');
    }

    public function getState() {
        return $this->findAddressComponent('administrative_area_level_1');
    }

    public function getZip() {
        return $this->findAddressComponent('postal_code');
    }

    public function getLatitude() {
        return $this->lookup->geometry->location->lat ?? '';
    }

    public function getLongitude() {
        return $this->lookup->geometry->location->lng ?? '';
    }
}