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
 * Axis
 * 27.03.2013 09:27
 */
namespace MOC\Module\Office\Chart;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Axis implements Module {

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
	 * @return Axis
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Axis();
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

	/** @var Axis\X[] $X */
	private static $X = array();
	/** @var Axis\Y[] $Y */
	private static $Y = array();

	/**
	 * @param int $Number
	 *
	 * @return Axis\X
	 */
	public function X( $Number = 1 ) {
		for( $Run = 1; $Run <= $Number; $Run++ ) {
			if( !array_key_exists( $Run, self::$X ) ) {
				self::$X[$Run] = Axis\X::InterfaceInstance();
			}
		}
		return self::$X[$Number];
	}

	/**
	 * @param int $Number
	 *
	 * @return Axis\Y
	 */
	public function Y( $Number = 1 ) {
		for( $Run = 1; $Run <= $Number; $Run++ ) {
			if( !array_key_exists( $Run, self::$Y ) ) {
				self::$Y[$Run] = Axis\Y::InterfaceInstance();
			}
		}
		return self::$Y[$Number];
	}

	/**
	 * @return string
	 */
	public function _getConfiguration() {
		return
		'xaxes:['.implode( ',', array_map(
			function( $Value ){
				/** @var Axis\X $Value */
				return $Value->_getConfiguration();
			},
			self::$X )
		).'], '.
		'yaxes:['.implode( ',', array_map(
			function( $Value ){
				/** @var Axis\Y $Value */
				return $Value->_getConfiguration();
			},
			self::$Y )
		).']';
	}

	/**
	 * Reset Static-Properties to Default-Values
	 */
	public function _doReset() {
		self::$X = array();
		self::$Y = array();
	}
}
