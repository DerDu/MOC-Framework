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
 * Video
 * 13.02.2013 13:39
 */
namespace MOC\Module\Office;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Video implements Module{
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Video
	 */
	public static function InterfaceInstance() {
		return new Video();
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
	 * @return string
	 */
	public function Setup() {
		return '<script type="text/javascript" src="'.Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle(
			__DIR__.'/../../Extension/FlowPlayer/3rdParty/flowplayer-3.2.12.min.js'
		)).'"></script>';
	}

	/** @var Video\Container $Container */
	private static $Container = null;

	/**
	 * @return Video\Container
	 */
	public function Container() {
		if( self::$Container === null ) {
			self::$Container = Video\Container::InterfaceInstance();
		}
		return self::$Container;
	}

	/**
	 * @return string
	 */
	public function Render() {

		$B = Api::Module()->Drive()->Directory()->Open( __DIR__.'/../../Extension/FlowPlayer/3rdParty/' );
		$C = Api::Module()->Drive()->Directory()->Open( Api::Core()->Drive()->Directory()->DirectoryCurrent() );

		if( null === ( $Video = $this->Container()->_getUrl() ) ) {
			if( null === ( $Video = $this->Container()->_getFile() ) ) {
				return false;
			}
			$Video = Api::Core()->Proxy()->Url( Api::Core()->Drive()->File()->Handle( $Video->GetLocation() ) );
		}

		$Script = '<a id="'.$this->Container()->_getIdentifier().'" href="'.$Video.'" style="display: block; width: '.$this->Container()->_getWidth().'px; height:'.$this->Container()->_getHeight().'px;"></a>'.
		'<script type="text/javascript">'.
			//"jQuery('#".$this->Container()->_getIdentifier()."').css({'display':'block','width':'".$this->Container()->_getWidth()."','height':'".$this->Container()->_getHeight()."'});".
			"flowplayer( '".$this->Container()->_getIdentifier()."', '".$B->GetLocationRelative( $C ).'/flowplayer-3.2.16.swf'."' );".
		'</script>';

		$this->_doReset();

		return $Script;
	}

	/**
	 * Reset Static-Properties to Default-Values
	 *
	 * @return Video
	 */
	public function _doReset() {
		self::$Container = null;
		return $this;
	}
}
