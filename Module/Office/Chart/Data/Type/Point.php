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
 * Point
 * 28.03.2013 08:13
 */
namespace MOC\Module\Office\Chart\Data\Type;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Point implements Module {

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
	 * @return Point
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Point();
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

	private $Configuration = array(
		'points' => array( 'show' => true )
	);

	/**
	 * @param bool $Toggle
	 *
	 * @return Point
	 */
	public function Show( $Toggle = true ) {
		$this->Configuration['points']['show'] = $Toggle;
		return $this;
	}

	/**
	 * @param bool|float $Toggle true/false or opaque (float) 0 - 1
	 * @param bool|string $Color false for default, else e.g '#FF0000'
	 *
	 * @return Point
	 */
	public function Fill( $Toggle = 0.5, $Color = false ) {
		$this->Configuration['points']['fill'] = $Toggle;
		if( $Color !== false ) {
			$this->Configuration['points']['fillColor'] = $Color;
		}
		return $this;
	}

	/**
	 * @param int $Number
	 *
	 * @return Point
	 */
	public function Width( $Number = 1 ) {
		$this->Configuration['points']['lineWidth'] = $Number;
		return $this;
	}

	/**
	 * @param int $Number
	 *
	 * @return Point
	 */
	public function Radius( $Number = 1 ) {
		$this->Configuration['points']['radius'] = $Number;
		return $this;
	}

	/**
	 * @return array
	 */
	public function _getConfiguration() {
		return $this->Configuration;
	}
}
