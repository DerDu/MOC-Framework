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
 * Chart
 * 26.03.2013 14:01
 */
namespace MOC\Module\Office;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Chart implements Module{
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Chart
	 */
	public static function InterfaceInstance() {
		return new Chart();
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
	 * @param bool $ExCanvas true
	 * @param bool $jQuery false
	 *
	 * @return string
	 */
	public function Setup( $ExCanvas = true, $jQuery = false ) {
		$Script = '';
		if( $ExCanvas ) {
			$Script .= '<script type="text/javascript" src="'.
			Api::Core()->Proxy()->Url(
				Api::Core()->Drive()->File()->Handle( __DIR__.'/../../Extension/Flot/excanvas.min.js' )
			).'"></script>';
		}
		if( $jQuery ) {
			$Script .= '<script type="text/javascript" src="'.
			Api::Core()->Proxy()->Url(
				Api::Core()->Drive()->File()->Handle( __DIR__.'/../../Extension/Flot/jquery.js' )
			).'"></script>';
		}
		$Script .= '<script type="text/javascript" src="'.Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle(
			__DIR__.'/../../Extension/Flot/jquery.flot.js'
		)).'"></script>';
		// Bundled
		$Script .= '<script type="text/javascript" src="'.Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle(
			__DIR__.'/../../Extension/Flot/jquery.flot.categories.js'
		)).'"></script>';
		$Script .= '<script type="text/javascript" src="'.Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle(
			__DIR__.'/../../Extension/Flot/jquery.flot.navigate.js'
		)).'"></script>';
		$Script .= '<script type="text/javascript" src="'.Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle(
			__DIR__.'/../../Extension/Flot/jquery.flot.threshold.js'
		)).'"></script>';
		// Additional
		$Script .= '<script type="text/javascript" src="'.Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle(
			__DIR__.'/../../Extension/Flot/Plugins/flot.axislabels/jquery.flot.axislabels.js'
		)).'"></script>';
		return $Script;
	}

	/** @var Chart\Container $Container */
	private static $Container = null;
	/** @var Chart\Grid $Grid */
	private static $Grid = null;
	/** @var Chart\Axis $Axis */
	private static $Axis = null;
	/** @var Chart\Data $Data */
	private static $Data = null;
	/** @var Chart\Legend $Legend */
	private static $Legend = null;

	/**
	 * @return Chart\Container
	 */
	public function Container() {
		if( self::$Container === null ) {
			self::$Container = Chart\Container::InterfaceInstance();
		}
		return self::$Container;
	}

	/**
	 * @return Chart\Grid
	 */
	public function Grid() {
		if( self::$Grid === null ) {
			self::$Grid = Chart\Grid::InterfaceInstance();
		}
		return self::$Grid;
	}

	/**
	 * @return Chart\Data
	 */
	public function Data() {
		if( self::$Data === null ) {
			self::$Data = Chart\Data::InterfaceInstance();
		}
		return self::$Data;
	}

	/**
	 * @return Chart\Axis
	 */
	public function Axis() {
		if( self::$Axis === null ) {
			self::$Axis = Chart\Axis::InterfaceInstance();
		}
		return self::$Axis;
	}

	public function Legend() {

	}

	/**
	 * @return string
	 */
	public function Render() {

		$SeriesList = Api::Module()->Office()->Chart()->Data()->_getSeries();
		$DataList = array();

		foreach( (array)$SeriesList as $Label => $Data ) {

			$Data	= json_encode( $Data, JSON_FORCE_OBJECT );
			$Data	= str_replace( ',', "],[", $Data );
			$Data	= str_replace( ":", ",", $Data );
			$Data	= str_replace( '{', '[[', $Data );
			$Data	= str_replace( '}', ']]', $Data );
			$Data	= str_replace( '"', '', $Data );

			$Data = array( 'label' => $Label, 'data' => $Data );

			$Data = array_merge( $Data, Api::Module()->Office()->Chart()->Data()->Config( $Label )->_getConfiguration() );

			$DataList[] = $Data;

		}

		$DataList = json_encode( $DataList );
		$DataList	= str_replace( '"[', "[", $DataList );
		$DataList	= str_replace( ']"', "]", $DataList );

		//var_dump( $this->Axis()->_getConfiguration() );

		$Script = '<script type="text/javascript">'.
			"jQuery('#".$this->Container()->_getIdentifier()."').css({'width':'".$this->Container()->_getWidth()."','height':'".$this->Container()->_getHeight()."'});".
			"jQuery.plot('#".$this->Container()->_getIdentifier()."', ".$DataList.", {"
				.$this->Grid()->_getConfiguration().', '
				.$this->Axis()->_getConfiguration()
				//.', zoom: {interactive: true}, pan: { interactive: true }, xaxis: { panRange: [1, 12], zoomRange: [3,12] }, yaxis: { zoomRange: [100000,300000] }'
			."});".
		'</script>';

		$this->_doReset();

		return $Script;
	}

	/**
	 * Reset Static-Properties to Default-Values
	 *
	 * @return Chart
	 */
	public function _doReset() {

		self::$Container = null;
		self::$Grid = null;

		if( self::$Axis !== null ) {
			self::$Axis->_doReset();
			self::$Axis = null;
		}

		self::$Data = null;
		self::$Legend = null;

		return $this;
	}
}
