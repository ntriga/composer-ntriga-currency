<?php

namespace Ntriga;

class Currency
{
	private static function getXMLCurrency()
	{
		return simplexml_load_string(file_get_contents(Currency::getXMLCurrencyPath()));
	}

	private static function getJSONCurrency()
	{
		return json_decode(file_get_contents(Currency::getJSONCurrencyPath()));
	}

	private static function getXMLPath()
	{
		return __DIR__ . '/../xml/';
	}

	private static function getJSONPath()
	{
		return __DIR__ . '/../json/';
	}

	private static function getJSONCurrencyPath(){
		return Currency::getJSONPath().'currency.json';
	}

	private static function getXMLCurrencyPath(){
		return Currency::getXMLPath().'currency.xml';
	}

	public static function update(){
		$currency_xml = file_get_contents('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
		if (!empty($currency_xml)) {
			try {
				file_put_contents(Currency::getXMLCurrencyPath(), $currency_xml);
				$xml = Currency::getXMLCurrency();
				$json = array();
				foreach ($xml->Cube->Cube->Cube as $item) {
					$json[(string)$item->attributes()->currency] = (float)$item->attributes()->rate;
				}
				file_put_contents(Currency::getJSONCurrencyPath(), json_encode($json));
			} catch (Exception $e) {
				return 'updated failed: '.$e->getMessage();
			}
			return 'updated!';
		}else{
			return 'update failed';
		}
	}

	public static function convert($value, $from, $to){
		$json = Currency::getJSONCurrency();

		if (($from == 'EUR' || ($from != 'EUR' && isset($json->{$from}))) && isset($json->{$to})) {
			if ($from != 'EUR') {
				$to_eur = $value/$json->{$from};
				return $to_eur*$json->{$to};
			}else{
				return $value*$json->{$to};
			}
		}else{
			throw new \Exception("Unknown currency", 1);
		}

		return $json;
	}
}
