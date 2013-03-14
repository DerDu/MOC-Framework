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
 * 31.07.2012 16:48
 */
namespace MOC\Core\Drive\File;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 * File-Property
 */
class Property implements Core {

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
	 * @return object
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Property();
	}

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

	/** @var null|string $Location */
	private $Location = null;
	/** @var null|array|string $Content */
	protected $Content = null;
	/** @var bool $Changed */
	private $Changed = false;

	/** @var null|string $Name */
	private $Name = null;
	/** @var null|string $Extension */
	private $Extension = null;
	/** @var null|string $Path */
	private $Path = null;
	/** @var null|int $Size */
	private $Size = null;
	/** @var null|int $Time */
	private $Time = null;

	/**
	 * File-Location
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
	 * File-Name
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
	 * File-Extension
	 *
	 * @param string|null $Extension
	 *
	 * @return string|null
	 */
	public function Extension( $Extension = null ) {
		if( $Extension !== null ) {
			$this->Extension = $Extension;
		} return $this->Extension;
	}

	/**
	 * File-Path
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
	 * File-Size
	 *
	 * @param int|null $Size
	 *
	 * @return int|null
	 */
	public function Size( $Size = null ) {
		if( $Size !== null ) {
			$this->Size = $Size;
		} return $this->Size;
	}

	/**
	 * File-Timestamp
	 *
	 * @param int|null $Time
	 *
	 * @return int|null
	 */
	public function Time( $Time = null ) {
		if( $Time !== null ) {
			$this->Time = $Time;
		} return $this->Time;
	}

	/**
	 * File-Changed
	 *
	 * @param null|bool $Toggle
	 *
	 * @return bool
	 */
	public function Changed( $Toggle = null ) {
		if( $Toggle !== null ) {
			$this->Changed = $Toggle;
		} return $this->Changed;
	}

	/**
	 * Read File-Properties
	 */
	protected function UpdateProperties() {
		$this->Name( pathinfo( $this->Location(), PATHINFO_FILENAME ) );
		$this->Extension( pathinfo( $this->Location(), PATHINFO_EXTENSION ) );
		$this->Path( pathinfo( $this->Location(), PATHINFO_DIRNAME ) );
		if( file_exists( $this->Location() ) ) {
			$this->Size( filesize( $this->Location() ) );
			$this->Time( filemtime( $this->Location() ) );
		}
	}

	/**
	 * Correct Path-Syntax
	 *
	 * @param string $Path
	 *
	 * @return string
	 */
	protected function UpdateSyntax( $Path ) {
		return trim( preg_replace( '![\\\/]+!', DIRECTORY_SEPARATOR, $Path ), '\\/' );
	}

}
