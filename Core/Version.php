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
 * Changelog
 * 30.08.2012 13:47
 */
namespace MOC\Core;
use \MOC\Api;
/**
 *
 */
class Version implements \MOC\Generic\Device\Core {

	/** @var int $Release Official Changelog (Only for Major-Changes and Initial-Release */
	private $Release = 0;
	/** @var int $Build Internal Changelog (Update-Collection for Official Changelog, Minor Changes) */
	private $Build = 0;
	/** @var int $Update Changed Functionality (Add/Remove/Change/Fix-Collection) */
	private $Update = 0;
	/** @var int $Fix Correcting Errors (WONT ADD OR REMOVE Functionality) */
	private $Fix = 0;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Core\Version
	 */
	public static function InterfaceInstance() {
		return new \MOC\Core\Version();
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
	 * Official Changelog
	 *
	 * Only for Major-Changes and Initial-Release
	 *
	 * @param null|int $Number
	 *
	 * @return int|Version
	 */
	public function Release( $Number = null ) {
		if( $Number !== null ) {
			$this->Release = $Number;
			return $this;
		} return $this->Release;
	}

	/**
	 * Internal Changelog
	 *
	 * Update-Collection for Official Changelog, Minor Changes
	 *
	 * @param null|int $Number
	 *
	 * @return int|Version
	 */
	public function Build( $Number = null ) {
		if( $Number !== null ) {
			$this->Build = $Number;
			return $this;
		} return $this->Build;
	}

	/**
	 * Changed Functionality
	 *
	 * Add/Remove/Change/Fix-Collection
	 *
	 * @param null|int $Number
	 *
	 * @return int|Version
	 */
	public function Update( $Number = null ) {
		if( $Number !== null ) {
			$this->Update = $Number;
			return $this;
		} return $this->Update;
	}

	/**
	 * Correcting Errors
	 *
	 * WONT ADD OR REMOVE Functionality
	 *
	 * @param null|int $Number
	 *
	 * @return int|Version
	 */
	public function Fix( $Number = null ) {
		if( $Number !== null ) {
			$this->Fix = $Number;
			return $this;
		} return $this->Fix;
	}

	/**
	 * Changelog Number
	 *
	 * as String
	 *
	 * @return string
	 */
	public function Number() {
		return
			$this->Release
			.'.'.
			$this->Build
			.'.'.
			$this->Update
			.'.'.
			$this->Fix;
	}

	/**
	 * Compare Versions
	 *
	 * true - is newer Changelog
	 * false - is older Changelog
	 * null - both Versions are equal
	 *
	 * @param \MOC\Core\Version $From
	 * @param \MOC\Core\Version $To
	 *
	 * @return bool|null
	 */
	public function Compare( \MOC\Core\Version $From, \MOC\Core\Version $To ) {
		if( $To->Release() - $From->Release() > 0 ) { return true; }
		if( $To->Release() - $From->Release() < 0 ) { return false; }
		if( $To->Build() - $From->Build() > 0 ) { return true; }
		if( $To->Build() - $From->Build() < 0 ) { return false; }
		if( $To->Update() - $From->Update() > 0 ) { return true; }
		if( $To->Update() - $From->Update() < 0 ) { return false; }
		if( $To->Fix() - $From->Fix() > 0 ) { return true; }
		if( $To->Fix() - $From->Fix() < 0 ) { return false; }
		return null;
	}
}
