<?php
/**
 * LICENSE (BSD)
 *
 * Copyright (c) 2013, Gerd Christian Kunze
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 *  * Neither the name of Gerd Christian Kunze nor the names of the
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Database
 * 26.02.2013 19:52
 */
namespace MOC\Module;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Database implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Database
	 */
	public static function InterfaceInstance() {
		return new Database();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/** @var Database\Driver $Queue */
	private $Queue = array();
	/** @var Database\Driver $Current */
	private $Current = null;

	const DRIVER_MYSQL = 10;
	const DRIVER_ODBC_MSSQL = 101;
	const DRIVER_ODBC_ORACLE = 102;

	/**
	 * @param int $DRIVER
	 *
	 * @return Database
	 */
	public function Driver( $DRIVER = self::DRIVER_ODBC_MSSQL ) {
		switch( $DRIVER ) {
			case self::DRIVER_MYSQL: {
				$this->_openResource( new Database\Driver\Mysql() );
				break;
			}
			case self::DRIVER_ODBC_MSSQL: {
				$this->_openResource( new Database\Driver\OdbcMssql() );
				break;
			}
			case self::DRIVER_ODBC_ORACLE: {
				$this->_openResource( new Database\Driver\OdbcOracle() );
				break;
			}
		}
		return $this;
	}

	/**
	 * @param      $Host
	 * @param      $User
	 * @param      $Password
	 * @param null $Database
	 *
	 * @return Database
	 */
	public function Open( $Host, $User, $Password, $Database = null ) {
		$this->_getResource()->Open( $Host, $User, $Password, $Database );
		return $this;
	}

	/**
	 * @return Database
	 */
	public function Close() {
		$this->_getResource()->Close();
		$this->_closeResource();
		return $this;
	}

	/**
	 * @param string $SqlTemplate
	 *
	 * @return Database
	 */
	public function Statement( $SqlTemplate ) {
		$this->_getResource()->Statement( $SqlTemplate );
		return $this;
	}

	/**
	 * @param mixed $Value
	 * @param null|string $Key
	 * @param int $Type
	 *
	 * @return Database
	 */
	public function Parameter( $Value, $Key = null, $Type = Database\Driver::PARAM_TYPE_NONE ) {
		$this->_getResource()->Parameter( $Value, $Key, $Type );
		return $this;
	}

	/**
	 * @param int $FETCH_AS
	 *
	 * @return mixed
	 */
	public function Execute( $FETCH_AS = Database\Driver::RESULT_AS_ARRAY_ASSOC ) {
		return $this->_getResource()->Execute( $FETCH_AS );
	}

	/**
	 * @param Database\Driver $Resource
	 *
	 * @return Database
	 */
	public function _openResource( $Resource ) {
		$this->Current = $Resource;
		array_push( $this->Queue, $this->Current );
		return $this;
	}

	/**
	 * @param $Index
	 *
	 * @return Database
	 */
	public function _selectResource( $Index ) {
		$this->Current = $this->Queue[$Index];
		return $this;
	}

	/**
	 * @return Database\Driver
	 */
	public function _getResource() {
		return $this->Current;
	}

	/**
	 * @return Database
	 */
	public function _closeResource() {
		$this->Current = null;
		array_pop( $this->Queue );
		$this->_selectResource( count( $this->Queue ) -1 );
		return $this;
	}
}
