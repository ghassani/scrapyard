<?php

namespace Miva\Migration\Database\ProStores;

use Miva\Migration\Database\CsvFileReader;

/**
* AffiliateCsvFileReader     
*
* @author Gassan Idriss <gidriss@miva.com>
*/
class AffiliateCsvFileReader extends CsvFileReader
{

	/**
	* {@inheritDoc}
	*/
	protected $headers = array(
		'AffiliateNo',
		'Affiliate',
		'Contact',
		'Street',
		'Street2',
		'City',
		'State',
		'Zip',
		'Country',
		'Phone',
		'Fax',
		'Email',
		'URL',
		'Resale',
		'Commission',
		'ReferralPeriod',
	);

}