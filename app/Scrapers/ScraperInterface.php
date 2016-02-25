<?php namespace App\Scrapers;

interface ScraperInterface {

	function post($url, $fields, $options);
	function get($url, $fields, $options);
	function log($text, $subject);


}