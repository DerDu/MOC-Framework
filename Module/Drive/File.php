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
 * File
 * 13.02.2013 08:46
 */
namespace MOC\Module\Drive;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 * Class which provides basic file access
 */
class File implements Module {

	/** @var \MOC\Core\Drive\File $Resource */
	private $Resource = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return File
	 */
	public static function InterfaceInstance() {
		$File = new File();
		$File->Resource = Api::Core()->Drive()->File();
		return $File;
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

	/**
	 * Opens file
	 * 
	 * @param $Location
	 *
	 * @return File
	 */
	public function Open( $Location ) {
		$this->Resource()->Handle( $Location );
		return $this;
	}

	/**
	 * Reads file
	 * 
	 * @return null|string
	 */
	public function Read() {
		if( $this->Resource()->Exists() ) {
			return $this->Resource()->Content();
		}
		return null;
	}

	/**
	 * Writes to file
	 * 
	 * @param $Content
	 *
	 * @return File
	 */
	public function Write( $Content ) {
		$Mode = $this->Resource;
		$this->Resource()->Content( $Content );
		$this->Resource()->Save( $Mode::MODE_WRITE_BINARY );
		return $this;
	}

	/**
	 * Appends to file
	 * 
	 * @param $Content
	 *
	 * @return File
	 */
	public function Append( $Content ) {
		$Mode = $this->Resource;
		$this->Resource()->Content( $Content );
		$this->Resource()->Save( $Mode::MODE_APPEND );
		return $this;
	}

	/**
	 * Deletes file
	 * 
	 * @return File
	 */
	public function Delete() {
		$this->Resource()->Remove();
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function Exists() {
		return $this->Resource()->Exists();
	}

	/**
	 * Gets filename name
	 * 
	 * @return null|string
	 */
	public function GetName() {
		return $this->Resource()->Name();
	}

	/**
	 * Get filename extension
	 * 
	 * @return null|string
	 */
	public function GetExtension() {
		return $this->Resource()->Extension();
	}

	/**
	 * Gets file location
	 * 
	 * @return null|string
	 */
	public function GetLocation() {
		return $this->Resource()->Location();
	}

	/**
	 * Gets file path
	 * 
	 * @return null|string
	 */
	public function GetPath() {
		return $this->Resource()->Path();
	}

	/**
	 * Gets URL of the file location
	 * 
	 * @return string
	 */
	public function GetUrl() {
		return Api::Core()->Proxy()->Url(
			Api::Core()->Drive()->File()->Handle( $this->GetLocation() )
		);
	}

	/**
	 * Gets file size
	 * 
	 * @return int|null
	 */
	public function GetSize() {
		return $this->Resource()->Size();
	}

	/**
	 * Gets time
	 * 
	 * @return int|null
	 */
	public function GetTime() {
		return $this->Resource()->Time();
	}

	/**
	 * Gets hash
	 * 
	 * @return null|string
	 */
	public function GetHash() {
		return $this->Resource()->Hash();
	}

	/**
	 * Copies file to a location
	 * 
	 * @param $Location
	 *
	 * @return File
	 */
	public function CopyTo( $Location ) {
		$this->Resource()->Copy( $Location );
		return $this;
	}

	/**
	 * Moves file to a location
	 * 
	 * @param $Location
	 *
	 * @return File
	 */
	public function MoveTo( $Location ) {
		$this->Resource()->Move( $Location );
		return $this;
	}

	/**
	 * Gets resource
	 * 
	 * @return \MOC\Core\Drive\File
	 */
	private function Resource() {
		return $this->Resource;
	}
}
