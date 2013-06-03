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
 * Factory
 * 31.07.2012 18:09
 */
namespace MOC\Core\Error;
use MOC\Api;
use MOC\Generic\Common;

/**
 *
 */
class Reporting implements Common {
	/** @var Reporting $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Reporting
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Reporting();
		} return self::$Singleton;
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
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/** @var int $Level */
	public static $Level = E_ALL;
	/** @var bool $Display */
	public static $Display = true;
	/** @var bool $Debug */
	public static $Debug = true;

	/**
	 * Apply settings
	 */
	public function Apply() {
		/**
		 * Set Level
		 */
		error_reporting( self::$Level );
		/**
		 * Disable PHP-Error-Display
		 */
		if( self::$Debug ) {
			ini_set('display_errors',1);
		} else {
			ini_set('display_errors',0);
		}
		/**
		 * Disable xDebug
		 */
		if( function_exists( 'xdebug_disable' ) ) {
			xdebug_disable();
		}
	}

	/**
	 * @param int $E_LEVEL
	 *
	 * @return Reporting
	 */
	public function Level( $E_LEVEL = null ) {
		if( null !== $E_LEVEL ) {
			self::$Level = $E_LEVEL;
		} return $this;
	}

	/**
	 * @param bool $Toggle
	 *
	 * @return Reporting
	 */
	public function Display( $Toggle = null ) {
		if( null !== $Toggle ) {
			self::$Display = $Toggle;
		} return $this;
	}

	/**
	 * @param bool $Toggle
	 *
	 * @return Reporting
	 */
	public function Debug( $Toggle = null ) {
		if( null !== $Toggle ) {
			self::$Debug = $Toggle;
		} return $this;
	}

}
