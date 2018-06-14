<?php

namespace Miva\Migration\Database;

use Miva\Migration\Utility\StringSanitizer;

/**
* CsvFileReader     
*
* @author Gassan Idriss <gidriss@miva.com>
*/
class CsvFileReader
{

    /**
     * $columnHeaders
     *
     * @var array
     *
     * @access protected
     */
	protected $columnHeaders = array();

    /**
     * $filePath
     *
     * @var string
     *
     * @access protected
     */
	protected $filePath;

    /**
     * $fixUtf8
     *
     * @var mixed
     *
     * @access protected
     */
	protected $fixUtf8 = true;

    /**
     * $fh
     *
     * @var resource
     *
     * @access protected
     */
	protected $fh;


    /**
     * $result
     *
     * @var array
     *
     * @access protected
     */
	protected $result = array();

    /**
     * __construct
     * 
     * @param mixed $filePath Description.
     */
	public function __construct($filePath, array $headers = array(), $fixUtf8 = true)
	{
		if (!file_exists($filePath)) {
 			throw new \InvalidArgumentException(sprintf('File (%s) Does Not Exists', $filePath));
        }

        $this->filePath = $filePath;
        $this->fixUtf8  = $fixUtf8;
		$this->fh 	    = fopen($filePath, 'r');

        if ($this->fh === false) {
            throw new \InvalidArgumentException(sprintf('Could Not Open Import File (%s) For Reading', $filePath));
        }
	}

    /**
     * __destruct
     * 
     * @access public
     *
     * @return void
     */
	public function __destruct()
	{
		$this->close();
	}

    /**
     * setHeaders
     * 
     * @param array $headers.
     *
     * @access public
     *
     * @return self
     */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
		return $this;
	}

    /**
     * getHeaders
     * 
     * @access public
     *
     * @return array
     */
	public function getHeaders()
	{
		return $this->headers;
	}

    /**
     * getResult
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function getResult()
	{
		return $this->result;
	}

    /**
     * setResult
     * 
     * @param mixed $result.
     *
     * @access private
     *
     * @return self
     */
	private function setResult($result)
	{
		$this->result = $result;
		return $this;
	}

	/**
	* read
	*
	* @return int - The amount of rows read 
	*/
	public function read()
	{
        $i = 0;        
        $fileHeader = $this->getHeaders();        
        $data = array();

        while (($row = fgetcsv($this->fh, 0))) {
            if ($i == 0) { // validate file by headers
                foreach ($row as $column => $headerName) {
                    if(count($fileHeader) && $headerName != $fileHeader[$column]) {                       
                        $this->close();
                        throw new \InvalidArgumentException(sprintf('Invalid Import File. Header Field %s Not Found And Expected At Column %s', $headerName, $column));
                    }
                }
                ++$i;
                continue;
            }

            $data[$i] = array();

            if (count($fileHeader)) {
	            foreach ($fileHeader as $column => $name) {
	            	if (true === $this->fixUtf8 && class_exists('\ForceUTF8\Encoding')) {        		
	            		$data[$i][$name] = isset($row[$column]) ? \ForceUTF8\Encoding::toUTF8(StringSanitizer::clean($row[$column])) : null;
	            	} else {
	            		$data[$i][$name] = isset($row[$column]) ? StringSanitizer::clean($row[$column]) : null;
	            	}
	            }
        	} else {
        		$data[$i] = $row;
        	}

            ++$i;     
        }
        
        $this->setResult($data);

        return $i;
	}

	/**
	* reindexArrayByHeader
	*
	* @return array
	*/
	public static function reindexArrayByHeader($array, $headerName)
	{
		if (is_null($headerName) || !$headerName) {
			return $array;
		}

        // reindex the array if specified a key
        $return = array();
        foreach ($array as $row) {
        	if(!array_key_exists($headerName, $row)) {
        		throw new \InvalidArgumentException(sprintf('Argument headerName was set for field (%s) that does not exist in the resulting array', $headerName));
        	}
        	$return[$row[$headerName]] = $row;
        }
        
        return $return;
	}

    /**
     * close
     * 
     * @access public
     *
     * @return void
     */
	public function close()
	{
		if (is_resource($this->fh)) {
			fclose($this->fh);
		}
	}
}
