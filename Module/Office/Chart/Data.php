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
 * Data
 * 26.03.2013 14:09
 */
namespace MOC\Module\Office\Chart;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Data implements Module {

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
	 * @return Data
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Data();
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

	private $Series = array();
	private $Configuration = array();

	/**
	 * @param string $Label
	 *
	 * @return Data\Config
	 */
	public function Config( $Label ) {
		if( !isset( $this->Configuration[$Label] ) ) {
			$this->Configuration[$Label] = Data\Config::InterfaceInstance();
		}
		return $this->Configuration[$Label];
	}

	/**
	 * @param string $Label
	 * @param string|int|float $X
	 * @param int|float $Y
	 *
	 * @return Data
	 */
	public function AddPoint( $Label, $X, $Y ) {
		if( !isset( $this->Series[$Label] ) ) {
			$this->Series[$Label] = array();
		}
		$this->Series[$Label][$X] = $Y;
		return $this;
	}

	/**
	 * @param string $Label
	 * @param array $Array array( X-Axis => Y-Axis, ... )
	 *
	 * @return Data
	 */
	public function AddPointList( $Label, $Array ) {
		if( !isset( $this->Series[$Label] ) ) {
			$this->Series[$Label] = array();
		}
		$this->Series[$Label] = array_merge( $this->Series[$Label], $Array );
		return $this;
	}

	/**
	 * @param array $Array 2D-Array : array( Row => array( Column => Value ), ... ) e.g. Database-Result
	 *
	 * @return Data
	 */
	public function AddSeries( $Array ) {
		foreach( (array)$Array as $X => $Series ) {
			foreach( (array)$Series as $Label => $Y ) {
				$this->AddPoint( $Label, $X, $Y );
			}
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function _getSeries() {
		return $this->Series;
	}

	/**
	 * @return string
	 */
	public function _getConfiguration() {
		return $this->Configuration;
	}
}
