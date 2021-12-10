<?php
/**
 * includes/formatcurrency.php
 *
 * @package default
 * @param unknown $floatcurr
 * @param unknown $curr      (optional)
 * @return unknown
 */


function formatcurrency($floatcurr, $curr = 'KSH') {

	/**
	 * A list of the ISO 4217 currency codes with symbol,format and symbol order
	 *
	 * Symbols from
	 * http://character-code.com/currency-html-codes.php
	 * http://www.phpclasses.org/browse/file/2054.html
	 * https://github.com/yiisoft/yii/blob/633e54866d54bf780691baaaa4a1f847e8a07e23/framework/i18n/data/en_us.php
	 *
	 * Formats from
	 * http://www.joelpeterson.com/blog/2011/03/formatting-over-100-currencies-in-php/
	 *
	 * Array with key as ISO 4217 currency code
	 * 0 - Currency Symbol if there's
	 * 1 - Round
	 * 2 - Thousands separator
	 * 3 - Decimal separator
	 * 4 - 0 = symbol in front OR 1 = symbol after currency
	 */
	$currencies = array(
		'KSH' => array('KSH ', 2, '.', ',', 0),          //  Kenyan Shilling
		'USD' => array('$', 2, '.', ',', 0),          //  US Dollar
	);


	//rupees weird format
	if ($curr == "INR")
		$number = formatinr($floatcurr);
	else
		$number = number_format($floatcurr, $currencies[$curr][1], $currencies[$curr][2], $currencies[$curr][3]);

	//adding the symbol in the back
	if ($currencies[$curr][0] === NULL)
		$number.= ' '.$curr;
	elseif ($currencies[$curr][4]===1)
		$number.= $currencies[$curr][0];
	//normally in front
	else
		$number = $currencies[$curr][0].$number;

	return $number;
}


/**
 * formats to indians rupees
 * from http://www.joelpeterson.com/blog/2011/03/formatting-over-100-currencies-in-php/
 *
 * @param float   $input money
 * @return string        formated currency
 */
function formatinr($input) {
	//CUSTOM FUNCTION TO GENERATE ##,##,###.##
	$dec = "";
	$pos = strpos($input, ".");
	if ($pos === false) {
		//no decimals
	} else {
		//decimals
		$dec = substr(round(substr($input, $pos), 2), 1);
		$input = substr($input, 0, $pos);
	}
	$num = substr($input, -3); //get the last 3 digits
	$input = substr($input, 0, -3); //omit the last 3 digits already stored in $num
	while (strlen($input) > 0) //loop the process - further get digits 2 by 2
		{
		$num = substr($input, -2).",".$num;
		$input = substr($input, 0, -2);
	}
	return $num . $dec;
}


/*
    echo '<br>'.formatcurrency(39174.00000000001);             //1,000,045.25 (USD)
    echo '<br>'.formatcurrency(1000045.25, "CHF");        //1'000'045.25
    echo '<br>'.formatcurrency(1000045.25, "EUR");      //1.000.045,25
    echo '<br>'.formatcurrency(1000045, "JPY");         //1,000,045
    echo '<br>'.formatcurrency(1000045, "VND");         //1 000 045
    echo '<br>'.formatcurrency(1000045.25, "INR");      //10,00,045.25
    echo '<br>'.formatcurrency(1000045.25, "ILS");      //10,00,045.25
    echo '<br>'.formatcurrency(1000045.25, "THB");      //10,00,045.25
    echo '<br>'.formatcurrency(1000045.25, "KRW");      //10,00,045.25
*/
