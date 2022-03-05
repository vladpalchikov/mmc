<?php

namespace MMC\Models;

class Helper
{
    public static function plural($number, $one, $two, $five) {
		if (($number - $number % 10) % 100 != 10) {
			if ($number % 10 == 1) {
				$result = $one;
			} elseif ($number % 10 >= 2 && $number % 10 <= 4) {
				$result = $two;
			} else {
				$result = $five;
			}
		} else {
			$result = $five;
		}
		return $result;
	}

	public static function mb_str_split($string) {
		return preg_split('#(?<!^)(?!$)#u', $string);
	}

	public static function num2str($inn, $stripkop = true) {
	    $nol = 'ноль';
	    $str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
	    $str[11] = array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
	    $str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
	    $sex = array(
	        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
	        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
	    );
	    $forms = array(
	        array('', '', '', 1), // 10^-2
	        array('', '', '',  0), // 10^ 0
	        array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
	        array('миллион', 'миллиона', 'миллионов',  0), // 10^ 6
	        array('миллиард', 'миллиарда', 'миллиардов',  0), // 10^ 9
	        array('триллион', 'триллиона', 'триллионов',  0), // 10^12
	    );
	    $out = $tmp = array();
	    // Поехали!
	    $tmp = explode('.', str_replace(',','.', $inn));
	    $rub = number_format($tmp[0], 0,'','-');
	    if ($rub== 0) $out[] = $nol;
	    // нормализация копеек
	    $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0,2) : '00';
	    $segments = explode('-', $rub);
	    $offset = sizeof($segments);
	    if ((int)$rub== 0) { // если 0 рублей
	        $o[] = $nol;
	        $o[] = self::morph( 0, $forms[1][ 0],$forms[1][1],$forms[1][2]);
	    }
	    else {
	        foreach ($segments as $k=>$lev) {
	            $sexi= (int) $forms[$offset][3]; // определяем род
	            $ri = (int) $lev; // текущий сегмент
	            if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
	                $offset--;
	                continue;
	            }
	            // нормализация
	            $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
	            // получаем циферки для анализа
	            $r1 = (int)substr($ri, 0,1); //первая цифра
	            $r2 = (int)substr($ri,1,1); //вторая
	            $r3 = (int)substr($ri,2,1); //третья
	            $r22= (int)$r2.$r3; //вторая и третья
	            // разгребаем порядки
	            if ($ri>99) $o[] = $str[100][$r1]; // Сотни
	            if ($r22>20) {// >20
	                $o[] = $str[10][$r2];
	                $o[] = $sex[ $sexi ][$r3];
	            }
	            else { // <=20
	                if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
	                elseif($r22> 0) $o[] = $sex[ $sexi ][$r3]; // 1-9
	            }
	            // Рубли
	            $o[] = self::morph($ri, $forms[$offset][0], $forms[$offset][1], $forms[$offset][2]);
	            $offset--;
	        }
	    }
	    // Копейки
	    if (!$stripkop) {
	        $o[] = $kop;
	        $o[] = self::morph($kop,$forms[ 0][ 0],$forms[ 0][1],$forms[ 0][2]);
	    }
	    return preg_replace("/\s{2,}/",' ',implode(' ',$o));
	}

	/**
	 * Склоняем словоформу
	 */
	public static function morph($n, $f1, $f2, $f5) {
	    $n = abs($n) % 100;
	    $n1= $n % 10;
	    if ($n>10 && $n<20) return $f5;
	    if ($n1>1 && $n1<5) return $f2;
	    if ($n1==1) return $f1;
	    return $f5;
	}

	public static function translit($str) {
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		return str_replace($rus, $lat, $str);
	}

	public static function ru_date($format, $date = false)
	{
		setlocale(LC_ALL, 'ru_RU.utf8');
		if ($date === false)
		{
			$date = time();
		}
		if ($format === '')
		{
			$format = '%e&nbsp;%bg&nbsp;%Y&nbsp;г.';
		}

		$months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
		$format = preg_replace("/\%b/", $months[date('n', $date)], $format);
		$res = strftime($format, $date);
		return $res;
	}

	public static function formatDateForQuery($date)
	{
		return date('Y-m-d', \DateTime::createFromFormat('d.m.y', $date)->getTimestamp());
	}

	public static function age($date)
	{
		return \Carbon\Carbon::parse($date)->diffInYears(\Carbon\Carbon::now());
	}

	public static function capitalize($string)
	{
		return mb_convert_case($string, MB_CASE_TITLE);
	}

	public static function ucfirst($string)
	{
		return ucfirst($string);
	}

	public static function dateFromString($str_date)
	{
		$obj_date = \DateTime::createFromFormat('d.m.y', $str_date);
		return date('d.m.Y', $obj_date->getTimestamp());
	}

	public static function hyphenize($string) {
	    $dict = array(
	        "I'm"      => "I am",
	        "thier"    => "their",
	        // Add your own replacements here
	    );
	    return strtolower(
	        preg_replace(
	          array( '#[\\s-]+#', '#[^A-Za-z0-9А-Яа-я\. -]+#' ),
	          array( '-', '' ),
	          // the full cleanString() can be downloaded from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
	          Helper::cleanString(
	              str_replace( // preg_replace can be used to support more complicated replacements
	                  array_keys($dict),
	                  array_values($dict),
	                  urldecode($string)
	              )
	          )
	        )
	    );
	}

	public static function cleanString($text) {
	    $utf8 = array(
	        '/[áàâãªä]/u'   =>   'a',
	        '/[ÁÀÂÃÄ]/u'    =>   'A',
	        '/[ÍÌÎÏ]/u'     =>   'I',
	        '/[íìîï]/u'     =>   'i',
	        '/[éèêë]/u'     =>   'e',
	        '/[ÉÈÊË]/u'     =>   'E',
	        '/[óòôõºö]/u'   =>   'o',
	        '/[ÓÒÔÕÖ]/u'    =>   'O',
	        '/[úùûü]/u'     =>   'u',
	        '/[ÚÙÛÜ]/u'     =>   'U',
	        '/ç/'           =>   'c',
	        '/Ç/'           =>   'C',
	        '/ñ/'           =>   'n',
	        '/Ñ/'           =>   'N',
	        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
	        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
	        '/[“”«»„]/u'    =>   ' ', // Double quote
	        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
	    );
	    return preg_replace(array_keys($utf8), array_values($utf8), $text);
	}

	public static function canBeEdit($date)
    {
        return \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::createFromTimestamp(strtotime($date))) > 90 ? false : true;
    }
}
