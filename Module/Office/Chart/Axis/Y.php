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
 * Y
 * 27.03.2013 09:27
 */
namespace MOC\Module\Office\Chart\Axis;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Y implements Module {

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
	 * @return Y
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Y();
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

	/*
	xaxis, yaxis: {
	    mode: null or "time" ("time" requires jquery.flot.time.js plugin)
	    timezone: null, "browser" or timezone (only makes sense for mode: "time")

	    autoscaleMargin: null or number

	    ticks: null or number or ticks array or (fn: axis -> ticks array)
	    tickSize: number or array
	    minTickSize: number or array
	    tickFormatter: (fn: number, object -> string) or string
	    tickDecimals: null or number

	    labelWidth: null or number
	    labelHeight: null or number
	    reserveSpace: null or true

	    tickLength: null or number

	    alignTicksWithAxis: null or number
	}
	*/
	private $Configuration = array(
		'show' => true,
		'alignTicksWithAxis' => false,
		'axisLabelFontFamily' => 'sans-serif',
		'axisLabelUseCanvas' => true,
//		'axisLabelUseHtml' => true,
		'axisLabelFontSizePixels' => 11
	);

	/**
	 * @param string $Position 'left','right'
	 *
	 * @return Y
	 */
	public function Position( $Position = 'left' ) {
		$this->Configuration['position'] = $Position;
		return $this;
	}

	/**
	 * @param string $Label
	 *
	 * @return Y
	 */
	public function Label( $Label = 'Y-Axis' ) {
		$this->Configuration['axisLabel'] = $Label;
		return $this;
	}

	/**
	 * @param string $HexColor
	 *
	 * @return Y
	 */
	public function Color( $HexColor = '#333333' ) {
		$this->Configuration['color'] = $HexColor;
		return $this;
	}

	/**
	 * @param int $Size
	 *
	 * @return Y
	 */
	public function FontSize( $Size = 11 ) {
		//$this->Configuration['axisLabelFontSizePixels'] = $Size;
		$this->Configuration['font']['size'] = $Size;
		return $this;
	}

	/**
	 * @param string $Style
	 *
	 * @return Y
	 */
	public function FontStyle( $Style = 'italic' ) {
		$this->Configuration['font']['style'] = $Style;
		return $this;
	}

	/**
	 * @param string $Weight
	 *
	 * @return Y
	 */
	public function FontWeight( $Weight = 'bold' ) {
		$this->Configuration['font']['weight'] = $Weight;
		return $this;
	}

	/**
	 * @param string $Family
	 *
	 * @return Y
	 */
	public function FontFamily( $Family = 'sans-serif' ) {
		//$this->Configuration['axisLabelFontFamily'] = $Family;
		$this->Configuration['font']['family'] = $Family;
		return $this;
	}

	/**
	 * @param string $Variant
	 *
	 * @return Y
	 */
	public function FontVariant( $Variant = 'small-caps' ) {
		$this->Configuration['font']['variant'] = $Variant;
		return $this;
	}

	/**
	 * @param string $HexColor
	 *
	 * @return Y
	 */
	public function FontColor( $HexColor = '#333333' ) {
		$this->Configuration['font']['color'] = $HexColor;
		return $this;
	}

	/**
	 * @return Y
	 */
	public function ScaleToLog() {
		$this->Configuration['transform'] = 'function (v) { return Math.log(v); }';
		$this->Configuration['inverseTransform'] = 'function (v) { return Math.exp(v); }';
		return $this;
	}

	/**
	 * @return Y
	 */
	public function ScaleToCategory() {
		$this->Configuration['mode'] = "categories";
		$this->Configuration['tickLength'] = 0;
		return $this;
	}


	/**
	 * @param null|int|float $Minimum
	 *
	 * @return Y
	 */
	public function ScaleMinimum( $Minimum = null ) {
		$this->Configuration['min'] = $Minimum;
		return $this;
	}

	/**
	 * @param null|int|float $Maximum
	 *
	 * @return Y
	 */
	public function ScaleMaximum( $Maximum = null ) {
		$this->Configuration['max'] = $Maximum;
		return $this;
	}

	/**
	 * @param string $HexColor
	 *
	 * @return Y
	 */
	public function TickColor( $HexColor = '#333333' ) {
		$this->Configuration['tickColor'] = $HexColor;
		return $this;
	}

	/**
	 * @param int $Precision
	 *
	 * @return Y
	 */
	public function TickFormatDecimal( $Precision = 2 ) {
		$this->Configuration['tickDecimals'] = $Precision;
		$this->Configuration['tickFormatter'] = 'function formatter( val, axis ) { return val.toFixed( axis.tickDecimals ); }';
		return $this;
	}

	/**
	 * @param int|float $Tick
	 * @param string $Label
	 *
	 * @return $this
	 */
	public function TickFormatLabel( $Tick, $Label ) {
		$this->Configuration['ticks'][$Tick] = $Label;
		return $this;
	}

	/**
	 * @return string
	 */
	public function _getConfiguration() {

		// Fix: ticks object => array
		if( isset( $this->Configuration['ticks'] ) && is_array( $this->Configuration['ticks'] ) ) {
			$this->Configuration['ticks'] = json_encode(
				array_map(
					function($key, $value) { return array($key, $value); },
					array_keys( $this->Configuration['ticks'] ),
					array_values( $this->Configuration['ticks'] )
				)
			);
		}

		$Data =  json_encode( $this->Configuration, JSON_FORCE_OBJECT );

		// Fix: string is function
		$Data = preg_replace( '!"(function[^{]+{.*?})"(,|})!is', '${1}${2}', $Data );

		return $Data;
	}
}
