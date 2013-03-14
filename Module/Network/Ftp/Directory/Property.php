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
 * Property
 * 16.10.2012 10:42
 */
namespace MOC\Module\Network\Ftp\Directory;
use \MOC\Api;
/**
 * Directory-Property
 */
class Property implements \MOC\Generic\Device\Module {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Property
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Property();
	}

	/** @var null|\MOC\Module\Network\Ftp\Transport\Connection $Connection */
	private $Connection = null;

	/**
	 * @param null|\MOC\Module\Network\Ftp\Transport\Connection $Connection
	 *
	 * @return null|\MOC\Module\Network\Ftp\Transport\Connection|\MOC\Module\Network\Ftp\Directory
	 */
	public function Connection( \MOC\Module\Network\Ftp\Transport\Connection $Connection = null ) {
		if( $Connection !== null ) {
			$this->Connection = $Connection;
			return $this;
		} return $this->Connection;
	}

	/** @var null|string $Location */
	private $Location = '/';

	/** @var null|string $Name */
	private $Name = null;
	/** @var null|string $Path */
	private $Path = null;

	/**
	 * Directory-Location
	 *
	 * @param null|string $Location
	 *
	 * @return null|string
	 */
	public function Location( $Location = null ) {
		if( $Location !== null ) {
			$this->Location = $this->UpdateSyntax( $Location );
		} return $this->Location;
	}

	/**
	 * Directory-Name
	 *
	 * @param string|null $Name
	 *
	 * @return string|null
	 */
	public function Name( $Name = null ) {
		if( $Name !== null ) {
			$this->Name = $Name;
		} return $this->Name;
	}

	/**
	 * Directory-Path
	 *
	 * @param string|null $Path
	 *
	 * @return string|null
	 */
	public function Path( $Path = null ) {
		if( $Path !== null ) {
			$this->Path = $this->UpdateSyntax( $Path );
		} return $this->Path;
	}

	/**
	 * Read Directory-Properties
	 */
	protected function UpdateProperties() {
		$this->Path( dirname( $this->Location() ) );
		$this->Name( basename( $this->Location() ) );
	}

	/**
	 * Correct Path-Syntax
	 *
	 * @param string $Path
	 *
	 * @return string
	 */
	protected function UpdateSyntax( $Path ) {
		return '/'.trim( preg_replace( '![\\\/]+!', '/', $Path ), '\\/' );
	}
}
