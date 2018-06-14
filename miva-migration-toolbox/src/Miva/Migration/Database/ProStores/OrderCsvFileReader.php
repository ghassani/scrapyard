<?php

namespace Miva\Migration\Database\ProStores;

use Miva\Migration\Database\CsvFileReader;

/**
* OrderCsvFileReader     
*
* @author Gassan Idriss <gidriss@miva.com>
*/
class OrderCsvFileReader extends CsvFileReader
{
	/**
	* {@inheritDoc}
	*/
	protected $headers = array(
		'InvoiceID',
		'InvoiceNo',
		'EnterDate',
		'ShipDate',
		'CustomerNo',
		'Company',
		'Phone',
		'LastName',
		'FirstName',
		'Email',
		'Street',
		'Street2',
		'City',
		'State',
		'Zip',
		'Country',
		'Recipient',
		'ShipStreet',
		'ShipStreet2',
		'ShipCity',
		'ShipState',
		'ShipZip',
		'ShipCountry',
		'ShipPhone',
		'Items',
		'Weight',
		'SubTotal',
		'TotalShippingTax',
		'TotalStateTax',
		'TotalCountyTax',
		'TotalCityTax',
		'TotalDistrictTax',
		'PromoDiscount',
		'Shipping',
		'ShipMethod',
		'Total',
		'ResaleInd',
		'PaymentMethod',
		'CreditCard',
		'AuthInd',
		'Status',
		'PromotionCode',
		'ItemNo',
		'ItemSKU',
		'ItemQty',
		'ItemWeight',
		'ItemAttribute1',
		'ItemAttribute2',
		'ItemAttribute3',
		'ItemAttribute4',
		'ItemAttribute5',
		'ItemAttribute6',
		'ItemAttribute7',
		'ItemAttribute8',
		'ItemAttribute9',
		'ItemAttribute10',
		'ItemSubscriptionPeriod',
		'ItemBillingInterval',
		'ItemPrice',
		'ItemTotal',
		'Supplier Notified',
		'ShipDate',
		'Supplier Invoice',
		'Supplier Total',
		'Ship Qty',
		'Supplier Price',
		'PONo',
		'Affiliate',
	);

}