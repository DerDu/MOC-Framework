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
 * Validation
 * 16.07.2013 12:53
 */
namespace MOC\Module\Network\Http\Request;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Network\Http\Request;

/**
 *
 */
class Validation implements Module {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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
	 * @return Validation
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Validation();
	}

	function __construct( Request $Value = null ) {
		$this->Request = $Value;
	}

	/** @var null|Request */
	private $Request = null;

	/**
	 * @return bool
	 */
	public function IsAvailable() {
		return isset( $_REQUEST[$this->Request->Name()] );
	}

	/**
	 * @return bool
	 */
	public function IsEmpty() {
		$Value = $this->Request->Get();
		return empty( $Value );
	}

	/**
	 * @return bool
	 */
	public function IsNotEmpty() {
		$Value = $this->Request->Get();
		return !empty( $Value );
	}

	/**
	 * @return bool
	 */
	public function IsInteger() {
		return $this->IsPattern( '!^[0-9]+$!is' );
	}

	/**
	 * @return bool
	 */
	public function IsCharacter() {
		return $this->IsPattern( '!^[a-z]{1}$!is' );
	}

	/**
	 * @param string $RegExp
	 *
	 * @return bool
	 */
	public function IsPattern( $RegExp ) {
		if( preg_match( $RegExp, $this->Request->Get() ) > 0 ) {
			return true;
		}
		return false;
	}

	/**
	 * @param mixed $Value
	 *
	 * @return bool
	 */
	public function IsEqual( $Value ) {
		if( $Value == $this->Request->Get() ) {
			return true;
		}
		return false;
	}
}

