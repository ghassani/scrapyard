<?php

namespace Miva\Migration\Database\ProStores;

use Miva\Migration\Database\CsvFileReader;

/**
* CustomerCsvFileReader     
*
* @author Gassan Idriss <gidriss@miva.com>
*/
class CustomerCsvFileReader extends CsvFileReader
{

	/**
	* {@inheritDoc}
	*/
	protected $headers = array(
		'CustomerNo',
		'LastName',
		'FirstName',
		'Company',
		'Street',
		'Street2',
		'City',
		'State',
		'Zip',
		'Country',
		'Phone',
		'Phone2',
		'Phone3',
		'Fax',
		'Fax2',
		'Pager',
		'Email',
		'Email2',
		'Resale',
		'AuthAllowed',
		'Notify',
		'Since',
		'ExtText1',
		'ExtText2',
		'ExtText3',
		'ExtText4',
		'ExtText5',
		'ExtText6',
		'ExtText7',
		'ExtText8',
		'GroupName',
	);

    /**
     * loggifyEmailAddress
     * 
     * @param mixed $emailAddress Description.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
	public static function loggifyEmailAddress($emailAddress)
	{
		return str_replace(array('@', '.'), '_', $emailAddress);
	}

}