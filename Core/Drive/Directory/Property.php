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
 * 31.07.2012 16:47
 */
namespace MOC\Core\Drive\Directory;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 * Directory-Property
 */
class Property implements Core {
	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return object
	 */
	public static function InterfaceInstance() {
		return new Property();
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


	/** @var null|string $Location */
	private $Location = null;

	/** @var null|string $Name */
	private $Name = null;
	/** @var null|string $Path */
	private $Path = null;
	/** @var null|int $Time */
	private $Time = null;

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
	 * Directory-Timestamp
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
	 * Read Directory-Properties
	 */
	protected function UpdateProperties() {
		$this->Name( pathinfo( $this->Location(), PATHINFO_FILENAME ) );
		$this->Path( pathinfo( $this->Location(), PATHINFO_DIRNAME ) );
		if( is_dir( $this->Location() ) ) {
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
		$Path = rtrim( preg_replace( '![\\\/]+!', DIRECTORY_SEPARATOR, $Path ), '\\/' );
		while( preg_match( '!(\\\|/)[^\\\/]+?(\\\|/)\.\.!', $Path, $Match ) ) {
			$Path = preg_replace( '!(\\\|/)[^\\\/]+?(\\\|/)\.\.!', '', $Path, 1 );
		}

		if( !is_dir( $Path ) && !is_file( $Path ) && !empty( $Path ) ) {
			mkdir( $Path, 0777, true );
		}
		return $Path;
	}

	/**
	 * Problem to fix: The $_SERVER["DOCUMENT_ROOT"] is empty in IIS.
	 *
	 * Based on: http://fyneworks.blogspot.com/2007/08/php-documentroot-in-iis-windows-servers.html
	 * Added by Diego, 13-AUG-2007.
	 *
	 * @static
	 * @return void
	 */
	protected function UpdateDocumentRootOnIIS() {
		// let's make sure the $_SERVER['DOCUMENT_ROOT'] variable is set
		if(!isset($_SERVER['DOCUMENT_ROOT'])){
			if(isset($_SERVER['SCRIPT_FILENAME'])){
				$_SERVER['DOCUMENT_ROOT'] = $this->UpdateSyntax( substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])) );
			};
		};
		if(!isset($_SERVER['DOCUMENT_ROOT'])){
			if(isset($_SERVER['PATH_TRANSLATED'])){
				$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr( $this->UpdateSyntax( $_SERVER['PATH_TRANSLATED'] ), 0, 0-strlen($_SERVER['PHP_SELF'])));
			};
		};
		// $_SERVER['DOCUMENT_ROOT'] is now set - you can use it as usual...
	}

}
