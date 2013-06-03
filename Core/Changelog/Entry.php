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
 * Entry
 * 18.02.2013 09:22
 */
namespace MOC\Core\Changelog;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 *
 */
class Entry implements Core {
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
	 * @return Entry
	 */
	public static function InterfaceInstance() {
		return new Entry();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance(  '18.02.2013 13:11', 'Alpha' )
			->Update()->Added(  '18.02.2013 13:13', 'Method Timestamp' )
		;
	}

	/** @var string $Timestamp */
	private $Timestamp = null;
	/** @var string $Version */
	private $Version = '-NA-';
	/** @var string $Type */
	private $Type = '-NA-';
	/** @var string $Type */
	private $Cause = '-NA-';
	/** @var string $Message */
	private $Message = '-NA-';
	/** @var string $Data */
	private $Location = '-NA-';

	/**
	 * @param null|string $Value
	 *
	 * @return null|string
	 */
	public function Timestamp( $Value = null ) {
		if( null !== $Value ) {
			$this->Timestamp = $Value;
		} return $this->Timestamp;
	}

	/**
	 * @param null|string $Value
	 *
	 * @return null|string
	 */
	public function Version( $Value = null ) {
		if( null !== $Value ) {
			$this->Version = $Value;
		} return $this->Version;
	}

	/**
	 * @param null|string $Value
	 *
	 * @return null|string
	 */
	public function Type( $Value = null ) {
		if( null !== $Value ) {
			$this->Type = $Value;
		} return $this->Type;
	}

	/**
	 * @param null|string $Value
	 *
	 * @return null|string
	 */
	public function Cause( $Value = null ) {
		if( null !== $Value ) {
			$this->Cause = $Value;
		} return $this->Cause;
	}

	/**
	 * @param null|string $Value
	 *
	 * @return null|string
	 */
	public function Message( $Value = null ) {
		if( null !== $Value ) {
			$this->Message = $Value;
		} return $this->Message;
	}

	/**
	 * @param null|string $Value
	 *
	 * @return null|string
	 */
	public function Location( $Value = null ) {
		if( null !== $Value ) {
			$this->Location = $Value;
		} return $this->Location;
	}
}
