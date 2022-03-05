<?php

namespace MMC\Models;

class FormOutput
{
	public static function string($string, $countSquare) {
		$output = [];
		$outputString = '';
		$chars = Helper::mb_str_split(mb_substr(trim($string), 0, $countSquare));

		for ($i = 0; $i < count($chars); $i++) {
			$output[$i] = '<li>'.$chars[$i].'</li>';
		}

		for ($i = count($chars); $i < $countSquare; $i++) {
			$output[$i] = '<li></li>';
		}

		foreach ($output as $item) {
			$outputString .= $item;
		}

		return $outputString;
	}

	public static function day($date) {
		$outputString = '';

		if ($date == '0000-00-00' || !$date) {
			$chars[0] = '';
			$chars[1] = '';
		} else {
			$date = date('d', strtotime($date));
			$chars = Helper::mb_str_split($date);
		}

		$outputString = '<li>'.$chars[0].'</li><li>'.$chars[1].'</li>';

		return $outputString;
	}

	public static function month($date) {
		$outputString = '';

		if ($date == '0000-00-00' || !$date) {
			$chars[0] = '';
			$chars[1] = '';
		} else {
			$date = date('m', strtotime($date));
			$chars = Helper::mb_str_split($date);
		}

		$outputString = '<li>'.$chars[0].'</li><li>'.$chars[1].'</li>';

		return $outputString;
	}

	public static function year($date) {
		$outputString = '';

		if ($date == '0000-00-00' || !$date) {
			$chars[0] = '';
			$chars[1] = '';
			$chars[2] = '';
			$chars[3] = '';
		} else {
			$date = date('Y', strtotime($date));
			$chars = Helper::mb_str_split($date);
		}

		$outputString = '<li>'.$chars[0].'</li><li>'.$chars[1].'</li><li>'.$chars[2].'</li><li>'.$chars[3].'</li>';

		return $outputString;
	}
}