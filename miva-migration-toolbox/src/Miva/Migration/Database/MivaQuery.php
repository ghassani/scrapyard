<?php

namespace Miva\Migration\Database;

use Doctrine\DBAL\Connection;

/**
* MivaQuery     
*/
class MivaQuery extends QueryAbstract
{

    # CORE
    use Miva\CoreTrait;
    use Miva\CustomerTrait;
    use Miva\ProductTrait;
    use Miva\InventoryTrait;
    use Miva\CategoryTrait;
    use Miva\HdftTrait;
    use Miva\ImageTrait;
    use Miva\MetaTrait;
    use Miva\OrderTrait;
    use Miva\TemplateTrait;
    use Miva\CustomFieldTrait;

    # 3RD PARTY MODULES
    use Miva\SebenzaReviewTrait;
    use Miva\PagefinderTrait;
    use Miva\UltimateCouponsTrait;
    use Miva\VikingCodersAffiliateTrait;
    
	public $connection;
    
    public $tablePrefix;

    /**
     * __construct
     * 
     * @param \Connection.
     *
     * @access public
     */
    public function __construct(Connection $connection)
    {
        $params = $connection->getParams();
        $this->tablePrefix = isset($params['table_prefix']) ? $params['table_prefix'] : null;
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



             




