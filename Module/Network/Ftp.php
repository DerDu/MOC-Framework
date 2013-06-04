<?php
/**
 * LICENSE (BSD)
 *
 * Copyright (c) 2012, Gerd Christian Kunze
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
 * phpFtp
 * 15.10.2012 15:36
 */
namespace MOC\Module\Network;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 * Class for FTP access
 */
class Ftp implements Module {

	/** @var Ftp $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Ftp
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Ftp();
		} return self::$Singleton;
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

	private $Connection = null;

	/**
	 * Opens a FTP connection
	 * 
	 * @param string $ServerName
	 * @param int $Port
	 * @param int $TimeOut
	 *
	 * @return Ftp
	 */
	public function Open( $ServerName, $Port = 21, $TimeOut = 15 ) {
		$this->Connection()->Open( $ServerName, $Port, $TimeOut );
		return $this;
	}

	/**
	 * Login to the FTP server
	 * 
	 * @param string $UserName
	 * @param string $Password
	 *
	 * @return Ftp
	 */
	public function Login( $UserName, $Password ) {
		$this->Connection()->Login( $UserName, $Password );
		return $this;
	}

	/**
	 * Gets FTP connection
	 * 
	 * @return Ftp\Transport\Connection
	 */
	private function Connection() {
		if( $this->Connection === null ) {
			$this->Connection = Ftp\Transport\Connection::InterfaceInstance();
		}
		return $this->Connection;
	}

	/**
	 * Gets directory
	 * 
	 * @return Ftp\Transport\Connection|Ftp\Directory|null
	 */
	public function Directory() {
		return Ftp\Directory::InterfaceInstance()->Connection( $this->Connection() );
	}

	/**
	 * Gets file
	 * 
	 * @return Ftp\Transport\Connection|Ftp\File|null
	 */
	public function File() {
		return Ftp\File::InterfaceInstance()->Connection( $this->Connection() );
	}
}
