<?php
namespace Miva\Migration\Database;

use Doctrine\DBAL\Connection;

class Manager
{

	protected $connections = array();

    /**
     * getConnection
     * 
     * @param string $name - Connection Name
     *
     * @access public
     *
     * @return PDO
     * @throws Exception - When connection not found by provided name
     */
	public function getConnection($name)
	{
		foreach($this->connections as $_name => $con) {
			if($name == $_name){
				return $con;
			}
		}
		throw new \Exception(sprintf('Connection With Name %s Does Not Exists. Available: ', $name, array_keys($this->connections)));
	}

	public function addConnection($name, Connection $connection)
	{
		$this->connections[$name] = $connection;
		return $this;
	}

    /**
     * getConnections
     * 
     * @access public
     *
     * @return array of PDO instances
     */
	public function getConnections()
	{
		return $this->connections;
	}


    /**
     * getAvailableConnections
     * 
     * @access public
     *
     * @return array
     */
	public function getAvailableConnections()
	{
		return array_keys($this->connections);
	}
}
