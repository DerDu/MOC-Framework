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
 * Grid
 * 27.03.2013 08:37
 */
namespace MOC\Module\Office\Chart;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Grid implements Module {

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
	 * @return Grid
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Grid();
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
		'show' => true,
		'aboveData' => false,
		'color' => '#333333',
		'backgroundColor' => '#FFFFFF',
		'borderColor' => '#999999',

		'borderWidth' => array(
			'top' => 1,
			'right' => 1,
			'bottom' => 1,
			'left' => 1,
		),
		'margin' => array(
			'top' => 1,
			'right' => 1,
			'bottom' => 1,
			'left' => 1,
		)
	);

	/**
	 * @param bool $Toggle
	 *
	 * @return Grid
	 */
	public function ConfigVisible( $Toggle = true ) {
		$this->Configuration['show'] = ($Toggle?true:false);
		return $this;
	}
	/**
	 * @param bool $Toggle
	 *
	 * @return Grid
	 */
	public function ConfigAboveData( $Toggle = false ) {
		$this->Configuration['aboveData'] = ($Toggle?true:false);
		return $this;
	}

	/**
	 * @param string $HexColor
	 *
	 * @return Grid
	 */
	public function ConfigColorGrid( $HexColor = '#333333' ) {
		$this->Configuration['color'] = $HexColor;
		return $this;
	}

	/**
	 * @param string $HexColor
	 *
	 * @return Grid
	 */
	public function ConfigColorBackground( $HexColor = '#FFFFFF' ) {
		$this->Configuration['backgroundColor'] = $HexColor;
		return $this;
	}

	/**
	 * @param string $HexColor
	 *
	 * @return Grid
	 */
	public function ConfigColorBorder( $HexColor = '#999999' ) {
		$this->Configuration['borderColor'] = $HexColor;
		return $this;
	}

	/*
	grid: {
	    labelMargin: number
	    axisMargin: number
	    minBorderMargin: number or null
	    clickable: boolean
	    hoverable: boolean
	    autoHighlight: boolean
	    mouseActiveRadius: number
	}
	*/

	/**
	 * @return string
	 */
	public function _getConfiguration() {
		return 'grid:'.json_encode( $this->Configuration, JSON_FORCE_OBJECT );
	}
}
