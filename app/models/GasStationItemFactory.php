<?php

namespace app\models;

class GasStationItemFactory {

	private static $gasStationData = array(
		'alcohol' => 'Álcool',
		'biodiesel' => 'Biodiesel',
		'gasoline' => 'Gasolina',
		'gasoline_aditivada' => 'Gasolina Aditivada',
		'gasoline_podium' => 'Gasolina Pódium',
		'gasoline_premium' => 'Gasolina Premium',
		'gnv' => 'Gás Natural',
		'kerosene' => 'Querosene',
		'diesel' => 'Diesel',
		'diesel_aditivado' => 'Diesel Aditivado',
	);

	public static function generate($itemName, $data) {
		$gasStationItem = new GasStationItem();
		
		$itemName = self::sanitizeItemName($itemName);
		
		$gasStationItem->setLabel(self::sanitizeLabel($itemName, $data));
		$gasStationItem->setValue(self::sanitizeValue($itemName, $data));
		$gasStationItem->setAverageValue(self::sanitizeAverageValue($itemName, $data));
		$gasStationItem->setCollectDate(self::sanitizeCollectDate($itemName, $data));
		return $gasStationItem;
	}
	
	private static function getGasStationData() {
		return self::$gasStationData;
	}
	
	public static function sanitizeItemName($itemName) {
		return str_replace('price_','' , $itemName);
	}
	
	public static function sanitizeLabel($itemName, $data) {
		$data = self::getGasStationData();
		return $data[$itemName];
	}
	
	public static function sanitizeValue($itemName, $data) {
		return $data->{"price_" . $itemName};
	}
	
	public static function sanitizeAverageValue($itemName, $data) {
		return $data->{"average_" . $itemName};
	}
	
	public static function sanitizeCollectDate($itemName, $data) {
		if (isset($data->{"collect_date_" . $itemName})) {
			return date("d/m H:i", strtotime($data->{"collect_date_" . $itemName}));
		}
		return null;
	}
}
