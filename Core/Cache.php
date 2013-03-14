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
 * Cache
 * 11.09.2012 12:32
 */
namespace MOC\Core;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 *
 */
class Cache implements Core {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Cache
	 */
	public static function InterfaceInstance() {
		$Instance = new Cache();
		$Instance->CacheDirectory = Drive::InterfaceInstance()->Directory()->Handle( __DIR__.'/../Data/Cache' );
		$Instance->Identifier( uniqid( __METHOD__, true ) );
		$Instance->Timeout( 60 );
		return $Instance;
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '18.02.2013 13:52', 'Alpha' )
		;
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending()
			->Package( '\MOC\Core\Drive', Version::InterfaceInstance()->Build(1)->Update(3) );
	}

	/** @var Drive\Directory $CacheDirectory */
	private $CacheDirectory = null;

	/** @var int $CacheTimeout */
	private $CacheTimeout = 60;
	/** @var string $CacheIdentifier */
	private $CacheIdentifier = '';
	/** @var string $CacheGroup */
	private $CacheGroup = '';
	/** @var string $CacheExtension */
	private $CacheExtension = 'cache';

	/**
	 * @return Drive\Directory
	 */
	public function Directory() {
		return $this->CacheDirectory;
	}

	/**
	 * @param int $Seconds
	 *
	 * @return int|Cache
	 */
	public function Timeout( $Seconds = null ) {
		if( null !== $Seconds ) {
			$this->CacheTimeout = time() + $Seconds;
			return $this;
		} return $this->CacheTimeout;
	}
	/**
	 * @param mixed $Name
	 *
	 * @return string|Cache
	 */
	public function Group( $Name = null ) {
		if( null !== $Name ) {
			$this->CacheGroup = sha1( serialize( $Name ) );
			return $this;
		} return $this->CacheGroup;
	}
	/**
	 * @param string $Extension
	 *
	 * @return string|Cache
	 */
	public function Extension( $Extension = null ) {
		if( null !== $Extension ) {
			$this->CacheExtension = $Extension;
			return $this;
		} return $this->CacheExtension;
	}
	/**
	 * @param mixed $Data
	 *
	 * @return string|Cache
	 */
	public function Identifier( $Data = null ) {
		if( null !== $Data ) {
			$this->CacheIdentifier = sha1( serialize( $Data ) );
			return $this;
		} return $this->CacheIdentifier;
	}

	/**
	 * @return bool|Drive\File
	 */
	public function Get() {
		$CacheList = $this->CacheLocation()->FileList();
		/** @var Drive\File $Cache */
		foreach( (array)$CacheList as $Cache ) {
			if( $this->CacheIdentifier( $Cache ) == $this->Identifier() ) {
				if( $this->CacheTimestamp( $Cache ) > time() ) {
					if( $this->CacheExtension( $Cache ) == $this->Extension() ) {
						return $Cache;
					}
				} else {
					$this->Purge();
				}
			}
		}
		return false;
	}

	/**
	 * @param mixed $Data
	 *
	 * @return Drive\File
	 */
	public function Set( $Data ) {
		$Cache = Drive::InterfaceInstance()
			->File()
			->Handle( $this->CacheLocation()->Location().'/'.$this->Identifier().'.'.$this->Timeout().'.'.$this->Extension() )
			->Content( $Data );
		$Cache->Save();
		return $Cache;
	}

	public function Purge() {
		$CacheList = $this->Directory()->FileListRecursive();
		$Directory = null;
		/** @var Drive\File $Cache */
		foreach( (array)$CacheList as $Cache ) {
			// Get Cache Location
			if( $Directory === null ) {
				$Directory = Drive::InterfaceInstance()->Directory()->Handle( $Cache->Path() );
			}
			if( $Directory->Location() != $Cache->Path() ) {
				if( $Directory->IsEmpty() ) {
					$Directory->Remove();
				}
				$Directory = Drive::InterfaceInstance()->Directory()->Handle( $Cache->Path() );
			}
			// Remove Cache
			if( time() > $this->CacheTimestamp( $Cache ) ) {
				$Cache->Remove();
			}
		}
	}

	/**
	 * Cache-File Name-Convention
	 * [Identifier].[Timestamp].[Extension]
	 */

	/**
	 * @param Drive\File $File
	 *
	 * @return int
	 */
	private function CacheExtension( Drive\File $File ) {
		return $File->Extension();
	}
	/**
	 * @param Drive\File $File
	 *
	 * @return int
	 */
	private function CacheTimestamp( Drive\File $File ) {
		$Name = explode( '.', $File->Name() );
		if( isset( $Name[1] ) ) {
			return $Name[1];
		}
		return $File->Time();
	}
	/**
	 * @param Drive\File $File
	 *
	 * @return int
	 */
	private function CacheIdentifier( Drive\File $File ) {
		$Name = explode( '.', $File->Name() );
		return $Name[0];
	}
	/**
	 * @return Drive\Directory
	 */
	private function CacheLocation() {
		return Drive::InterfaceInstance()->Directory()->Handle(
			$this->Directory()->Location().'/'.$this->CacheGroup
		);
	}

	/**
	 * @return string
	 */
	function __toString() {
		return '';
	}
}
