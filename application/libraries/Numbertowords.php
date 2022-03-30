<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Numbertowords {
	// function convert_number($number) {
	// 	if (($number < 0) || ($number > 999999999)) {
	// 		throw new Exception("Number is out of range");
	// 	}
	// 	$Gn = floor($number / 1000000);
	// 	/* Millions (giga) */
	// 	$number -= $Gn * 1000000;
	// 	$kn = floor($number / 1000);
	// 	/* Thousands (kilo) */
	// 	$number -= $kn * 1000;
	// 	$Hn = floor($number / 100);
	// 	/* Hundreds (hecto) */
	// 	$number -= $Hn * 100;
	// 	$Dn = floor($number / 10);
	// 	/* Tens (deca) */
	// 	$n = $number % 10;
	// 	/* Ones */
	// 	$res = "";
	// 	if ($Gn) {
	// 		$res .= $this->convert_number($Gn) .  "Million";
	// 	}
	// 	if ($kn) {
	// 		$res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";
	// 	}
	// 	if ($Hn) {
	// 		$res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";
	// 	}
	// 	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
	// 	$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
	// 	if ($Dn || $n) {
	// 		if (!empty($res)) {
	// 			$res .= " and ";
	// 		}
	// 		if ($Dn < 2) {
	// 			$res .= $ones[$Dn * 10 + $n];
	// 		} else {
	// 			$res .= $tens[$Dn];
	// 			if ($n) {
	// 				$res .= "-" . $ones[$n];
	// 			}
	// 		}
	// 	}
	// 	if (empty($res)) {
	// 		$res = "zero";
	// 	}
	// 	return $res;
	// }

	function convert_number($number) 
    {
        $no = floor($number);
		$point = round($number - $no, 2) * 100;
		$hundred = null;
		$digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'one', '2' => 'two',
			'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
			'7' => 'seven', '8' => 'eight', '9' => 'nine',
			'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
			'13' => 'thirteen', '14' => 'fourteen',
			'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
			'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
			'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
			'60' => 'sixty', '70' => 'seventy',
			'80' => 'eighty', '90' => 'ninety');
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number] .
					" " . $digits[$counter] . $plural . " " . $hundred
					:
					$words[floor($number / 10) * 10]
					. " " . $words[$number % 10] . " "
					. $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$points = ($point) ?
			"." . $words[$point / 10] . " " . 
				$words[$point = $point % 10] : '';
		// echo $result . "Rupees  " . $points . " Paise";
        return ucwords($result) . " Only";
    }
}
?>