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

	}

	private function scrapeDescription() {

		return trim($this->dom->find('.entry-content',0)->innertext);

	}

	private function scrapeDescriptionPlainText() {

		return trim($this->dom->find('.entry-content',0)->plaintext);

	}

	private function scrapePermalink() {

		return $this->dom->find('.entry-utility a[rel^=bookmark]',0)->href;

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

			$permalink = $this->scrapePermalink();
			$restaurant = \App\Restaurant::firstOrNew(['permalink' => $permalink]);

			$restaurant->name = $this->scrapeName();
			$restaurant->raw_address = $this->scrapeAddress();
			$restaurant->description = $this->scrapeDescription();
			$restaurant->description_plaintext = $this->scrapeDescriptionPlainText();

			//TOD: Attempt to extract website
			//TODO: Attemp to extract reviews
			//TODO: Geocode and clean up addresses

			if (!empty($restaurant->raw_address)) {
				$restaurant->save();
				echo  PHP_EOL . 'SAVED: ' . $restaurant->name . PHP_EOL;
				$this->scrapeCategories($restaurant);
				$this->scrapeTags($restaurant);
			} else {
				//echo  PHP_EOL . 'NO ADDRESSS: ' . $restaurant->name . PHP_EOL;
			}

			$nextlink = $this->getNextLink();

			$this->dom->clear();

		} while ($nextlink);

	}


}