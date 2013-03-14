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
 * Session
 * 30.08.2012 16:56
 */
namespace MOC\Core;
use \MOC\Api;
/**
 *
 */
class Session implements \MOC\Generic\Device\Core {
	/** @var \MOC\Core\Session $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Core\Session
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new \MOC\Core\Session();
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

	/** @var string $Identifier */
	private $Identifier = __CLASS__;
	/**
	 * @static
	 * @return string Handler ID
	 */
	public function Start() {
		if( !strlen( session_id() ) > 0 ) {
			session_start();
			if( !isset( $_SESSION[$this->Identifier()] ) ) {
				$_SESSION[$this->Identifier()] = array();
			}
		} return session_id();
	}
	/**
	 * @static
	 * @return string Handler ID
	 */
	public function Id() {
		$this->Start();
		return session_id();
	}
	/**
	 * @static
	 * @param string $Index
	 * @param mixed $Value
	 * @return mixed
	 */
	public function Write( $Index, $Value ) {
		$this->Start();
		return $_SESSION[$this->Identifier()][$Index] = $Value;
	}
	/**
	 * @static
	 * @param null $Index
	 * @return null|array|mixed
	 */
	public function Read( $Index = null ) {
		$this->Start();
		if( $Index !== null ) {
			if( isset( $_SESSION[$this->Identifier()][$Index] ) ) {
				return $_SESSION[$this->Identifier()][$Index];
			} else {
				return null;
			}
		} return $_SESSION[$this->Identifier()];
	}
	/**
	 * @static
	 * @param null $Identifier
	 * @return string
	 */
	private function Identifier( $Identifier = null ) {
		if( $Identifier !== null ) {
			$this->Identifier = $Identifier;
		} return $this->Identifier;
	}
}
