<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\IMEI;

/**
 * Validator
 * 
 * This class has some static methods to validate an IMEI
 * and determine a check digit if needed.
 * 
 * @author Gassan Idriss <ghassani@gmail.com>
 * 
 * Check Digit Algorithm from http://forum.gsmhosting.com/vbb/f83/how-do-imei-check-validate-224211/index2.html#post1399832
 */
class Validator
{
    const IMEI_REGEXP = '/^\d{15}$/';
    const IMEI_NO_CHECK_REGEXP = '/^\d{14}$/';
    
    /**
     * validate
     * 
     * @param string $imei
     * 
     * 
     */
    public static function validate($imei){
        if(!preg_match(static::IMEI_REGEXP, $imei)){
            return false;
        }
        
        if(static::determineCheckDigit($imei) == $imei[14]){
            return true;
        }
        
        return false;
    }
    
    /**
     * determineCheckDigit
     */
    public static function determineCheckDigit($imei)
    {
        if(!preg_match(static::IMEI_NO_CHECK_REGEXP, $imei)){
            if(preg_match(static::IMEI_REGEXP, $imei)){
                $originalImei = $imei;
                $imei = substr($imei,0,14);
            } else {
                throw new \InvalidArgumentException(sprintf('IMEI Should Be 14 Digits Without Checksum. You Provided %s - %s',
                    strlen($imei), 
                    $imei
                ));
            }
        }
        
    
        
        $cs = 0;
        for( $i = 0; $i < 14; $i += 2 ) {
            $tmp = $imei[ $i + 1 ] << 1;
            $cs += $imei[ $i ] + (int)( $tmp / 10 ) + ( $tmp % 10 );
        }
        $cs = ( 10 - ( $cs % 10 )) % 10;
        return $cs;
    }
} 
