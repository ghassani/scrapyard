<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Utility\Math;

/**
 * BCMath
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class BCMath
{

    const DEFAULT_PRECISION = 8;
    
    protected $precision;

    /**
     * @param int $precision
     */
    public function __construct($precision = null)
    {
        $this->precision = $precision;

        if (!function_exists('bcscale')) {
            throw new \RuntimeException('BC Math functions must be installed');
        }

        bcscale($this->getPrecision());
    }

    /**
     * getPrecision
     *
     * @return int
     */
    public function getPrecision()
    {
        return is_null($this->precision) ? static::DEFAULT_PRECISION : $this->precision;
    }

    /**
     *
     */
    public function add($left, $right, $precision = null)
    {
        return bcadd($left, $right, is_null($precision) ? $this->getPrecision() : $precision );
    }

    /**
     * subtract
     *
     * @param float|int $left
     * @param float|int $right
     * @param int       $precision
     */
    public function subtract($left, $right, $precision = null)
    {
        return bcsub($left, $right, is_null($precision) ? $this->getPrecision() : $precision );
    }

    /**
     * multiply
     *
     * @param float|int $left
     * @param float|int $right
     * @param int       $precision
     */
    public function multiply($left, $right, $precision = null)
    {
        return bcmul($left, $right, is_null($precision) ? $this->getPrecision() : $precision );
    }

    /**
     * divide
     *
     * @param float|int $left
     * @param float|int $right
     * @param int       $precision
     */
    public function divide($left, $right, $precision = null)
    {
        return bcdiv($left, $right, is_null($precision) ? $this->getPrecision() : $precision );
    }

    /**
     * percentOf
     *
     * @param float|int $of
     * @param float|int $percent
     * @param int       $precision
     */
    public function percentOf($of, $percent, $precision = null)
    {
        $percent = $percent > 1 ? $this->multiply($percent,100) : $percent;

        return $this->multiply($of, $percent, is_null($precision) ? $this->getPrecision() : $precision );
    }
}
