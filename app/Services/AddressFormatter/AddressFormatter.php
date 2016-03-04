<?php namespace App\Services\AddressFormatter;

interface AddressFormatter
{
    public function init($raw_address);
    public function getRawAddress();
    public function getFormattedAddress();
    public function getStreet();
    public function getCity();
    public function getState();
    public function getZip();
    public function getLatitude();
    public function getLongitude();
}