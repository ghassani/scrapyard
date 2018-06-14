<?php

namespace Miva\Migration\Database\ProStores;

use Miva\Migration\Database\CsvFileReader;

/**
* ProductSalesCsvFileReader     
*
* @author Gassan Idriss <gidriss@miva.com>
*/
class ProductSalesCsvFileReader extends CsvFileReader
{
    /**
    * {@inheritDoc}
    */
    protected $headers = array(
        'PromotionConfigNumber',
        'ProductNumber',
        'AttributeId',
        'Code',
        'Name',
        'InvoiceText',
        'StartDate',
        'EndDate',
        'ActiveInd',
        'ExclusiveInd',
        'Priority',
        'DiscountAmount',
        'DiscountType',
        'MaxUse',
        'OneTimeUseInd'
    );

}