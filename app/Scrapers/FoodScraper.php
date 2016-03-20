<?php namespace App\Scrapers;

use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;

class FoodScraper {

	private $scraper;
	private $dom;

	public function __construct(\App\Scrapers\ScraperInterface $scraper) {
		$this->scraper = $scraper;
	}

	private function scrapeName() {
		return html_entity_decode($this->dom->find('.entry-title',0)->innertext);
	}

	private function scrapeAddress($raw_address=null) {

		$possible_addresses = $this->dom->find('.entry-content a');
		foreach ($possible_addresses as $possible_address) {
			if (strpos($possible_address->href,'google.com/maps')) {
				return $possible_address->innertext;
				break;
			}
		}

		if ($raw_address) {
			return $raw_address;
		}

		return null;

	}

	private function scrapeDescription() {

		return trim($this->dom->find('.entry-content',0)->innertext);

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
			$cuisines = ['Afghan','African','American','Argentinean','Asian (Pan-Asian)','Bakery','Bangladeshi','Barbecue','Bars','Bolivian','Butcher','Cajun','Cambodian','Cantonese','Chicken','Chinese','Chinese-Peruvian','Crabs','Cuban','Deli','Desserts','Eritrean','Ethiopian','Filipino','Fine Dining','Food Stores','Food Trucks/Street Vendors','Franco-Mediterranean','French','Greek','Hamburgers','Hmong','Indian','Indonesian','Iranian','Israeli','Italian','Japanese','Korean','Laotian','Lebanese','Malaysian','Manchurian','Mexican','Moroccan','Nepalese','Nigerian','Pakistani','Palestinian','Persian','Peruvian','Pizza','Puerto Rican','Russian','Saudi Arabian','Seafood','Singaporean','Southern (see also Barbecue)','Southern Indian','Spanish','Sri Lankan','Steaks','Sushi','Taiwanese','Thai','Turkish','Uruguayan','Uyghur','Uzbekistani','Vegetarian','Venezuelan','Vietnamese','West African','Yemeni'];

			$locations = ['Adams Morgan/Mount Pleasant','Alexandria','Annandale','Arlington','Bailey\'s Crossroads','Bethesda/Chevy Chase','Bloomingdale','California','Capitol Hill/Union Station','Centreville/Manassas','Chicago','Chinatown/Verizon Center','College Park/Hyattsville','Columbia Heights/Howard University','Columbia/Laurel','Convention Center','Croatia','Crystal City/Pentagon City/National Airport','DC','Downtown','Dupont Circle','Eden Center','Fairfax','Falls Church/Seven Corners','Fredericksburg','Georgetown','GWU/Foggy Bottom','H Street NE','Herndon/Reston/Ashburn/Chantilly / Dulles Airport','Illinois','Logan Circle','Maryland','Merrifield','New York','Northeast','Outside DC','Rockville/Gaithersburg','Silver Spring','Singapore','Southeast','Springfield','Tenleytown/Van Ness','Texas','U Street','Union Market / Gallaudet University','Vienna/Tysons','Virginia','Wheaton/Kensington','Woodbridge/Potomac Mills'];

			$possible_categories = $this->dom->find('.entry-utility a[rel^=category]');
			$categories = [];
			foreach ($possible_categories as $possible_category) {
				$categories[] = $possible_category->innertext;

				$subtype = in_array($possible_category->innertext,$locations) ? 'location' : '';
				$subtype = in_array($possible_category->innertext,$cuisines) ? 'cuisine' : $subtype;

				$category = \App\Term::updateOrCreate(['title'=>$possible_category->innertext,'type'=>'category','subtype'=>$subtype]);
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

	private function getExcerpt() {
		$description = $this->scrapeDescription();
		$description = strip_tags($description);
		preg_match('/(])(.*)(Related Posts)/',$description,$matches);

		if (!count($matches)) {
			return $description;
		}

		$description = $matches[2];
		$description = html_entity_decode(trim($description));
		return $description;
	}

	private function getAddedOn() {

		$content = $this->dom->find('.entry-date',0);
		if (!is_object($content)) {
			return null;
		}
		$date = $content->plaintext;
		return Carbon::parse($date, 'America/New_York')->format("Y-m-d H:i:s");
	}

	private function getLinks() {

		$links = [];
		$this->dom = HtmlDomParser::str_get_html ($this->scraper->get('https://tylercowensethnicdiningguide.com/'));
		$links = array_merge($links,$this->dom->find('h2.entry-title a'));
		$i=0;
		while ($nextlink = $this->dom->find('.nav-previous a', 0)) {
			$i++;
			$this->dom = HtmlDomParser::str_get_html ($this->scraper->get($nextlink->href));
			$links = array_merge($links,$this->dom->find('h2.entry-title a'));
			// if ($i>=2) {
			// 	break;
			// }
		}

		foreach ($links as &$link) {
			$link = $link->href;
		}
		$this->dom->clear();
		return $links;

	}
	public function go() {

		$links = $this->getLinks();
		foreach ($links as $link) {

			$this->dom = HtmlDomParser::str_get_html( $this->scraper->get($link) );

			$permalink = $this->scrapePermalink($link);
			$restaurant = \App\Restaurant::firstOrNew(['permalink' => $permalink]);

			$restaurant->name = $this->scrapeName();
			$restaurant->raw_address = $this->scrapeAddress($restaurant->raw_address);
			$restaurant->description = $this->scrapeDescription();
			$restaurant->phone = $this->scrapePhone();
			$restaurant->website = $this->scrapeWebsite();
			$restaurant->excerpt = $this->getExcerpt();
			$restaurant->added_on = $this->getAddedOn();

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
		}

	}


}