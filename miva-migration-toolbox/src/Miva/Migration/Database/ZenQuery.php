<?php

namespace Miva\Migration\Database;

use Doctrine\DBAL\Connection;

class ZenQuery extends QueryAbstract
{

    use Zen\CategoryTrait;
    use Zen\CustomerTrait;
    use Zen\ManufacturerTrait;
    use Zen\OrderTrait;
    use Zen\ProductTrait;
    use Zen\ReviewTrait;
    use Zen\CouponTrait;
    use Zen\ZoneTrait;
    use Zen\CountryTrait;

	public $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

    /**
     * getConnection
     * 
     * @access public
     *
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

}