<?php namespace App\Scrapers;

use Sunra\PhpSimple\HtmlDomParser;

class FoodScraper {

	private $scraper;
	private $dom;

	public function __construct(\App\Scrapers\ScraperInterface $scraper) {
		$this->scraper = $scraper;
	}

	private function scrapeName() {
		return html_entity_decode($this->dom->find('.entry-title',0)->innertext);
	}

	private function scrapeAddress() {

		$possible_addresses = $this->dom->find('.entry-content a');
		foreach ($possible_addresses as $possible_address) {
			if (strpos($possible_address->href,'google.com/maps')) {
				return $possible_address->innertext;
				break;
			}
		}
		return null;

	}

	private function scrapeDescription() {

		return trim($this->dom->find('.entry-content',0)->innertext);

	}

	private function scrapeDescriptionPlainText() {

		return trim($this->dom->find('.entry-content',0)->plaintext);

	}

	private function scrapePermalink($currentpage = null) {

		$url = $this->dom->find('.entry-utility a[rel^=bookmark]',0);
		if (!empty($url)) {
			return $url->href;
		}
		return $currentpage;

	}

	private function scrapePhone() {

		$content = $this->dom->find('.entry-content',0)->plaintext;
		$result = preg_match('/1?\W*([2-9][0-8][0-9])\W*([2-9][0-9]{2})\W*([0-9]{4})(\se?x?t?(\d*))?/', $content, $matches);
		if ($result and count($matches)>0) {
			$phone = preg_replace("/[^0-9]/", "", $matches[0]);
			return '(' . substr($phone,0,3) . ') ' . substr($phone,3,3) . '-' . substr($phone,6,4);
		}

		return '';
	}

	private function scrapeWebsite() {

		foreach ($this->dom->find('.entry-content a') as $a) {
			if ($a->innertext == 'web site' || $a->innertext == 'web site') {
				return $a->href;
			}
		}
		return null;

	}

	private function scrapeTags($restaurant) {
				$possible_tags = $this->dom->find('.entry-utility a[rel^=tag]');
				$tags = [];
				foreach ($possible_tags as $possible_tag) {
					$tags[] = $possible_tag->innertext;
					$tag = \App\Term::updateOrCreate(['title'=>$possible_tag->innertext,'type'=>'tag']);
					if (!$restaurant->terms->contains($tag->id))
					{
					   $restaurant->terms()->attach($tag->id);
					}
				}
	}

	private function scrapeCategories($restaurant) {
			$possible_categories = $this->dom->find('.entry-utility a[rel^=category]');
			$categories = [];
			foreach ($possible_categories as $possible_category) {
				$categories[] = $possible_category->innertext;
				$category = \App\Term::updateOrCreate(['title'=>$possible_category->innertext,'type'=>'category']);
				if (!$restaurant->terms->contains($category->id))
				{
				   $restaurant->terms()->attach($category->id);
				}
			}
	}

	private function getNextLink() {

		$link = $this->dom->find('.nav-previous a',0);
		if (!$link) {
			return null;
		}
		return $link->href;

	}

	public function go() {
		$str = $this->scraper->get('https://tylercowensethnicdiningguide.com/');
		$this->dom = HtmlDomParser::str_get_html( $str );
		$nextlink = $this->dom->find('h2.entry-title a', 0)->href;
		do {

			$str = $this->scraper->get($nextlink);
			$this->dom = HtmlDomParser::str_get_html( $str );

			$permalink = $this->scrapePermalink($nextlink);
			$restaurant = \App\Restaurant::firstOrNew(['permalink' => $permalink]);

			$restaurant->name = $this->scrapeName();
			$restaurant->raw_address = $this->scrapeAddress();
			$restaurant->description = $this->scrapeDescription();
			$restaurant->description_plaintext = $this->scrapeDescriptionPlainText();
			$restaurant->phone = $this->scrapePhone();
			$restaurant->website = $this->scrapeWebsite();

			//TODO: Attemp to extract reviews
			//TODO: Scrape Date Added

			$restaurant->not_actual_restaurant = false;
			if (empty($restaurant->raw_address)) {
				$restaurant->not_actual_restaurant = true;
			}

			$restaurant->save();
			echo  PHP_EOL . 'SAVED: ' . $restaurant->name . PHP_EOL;
			$this->scrapeCategories($restaurant);
			$this->scrapeTags($restaurant);

			$nextlink = $this->getNextLink();

			$this->dom->clear();
		} while ($nextlink);

	}


}