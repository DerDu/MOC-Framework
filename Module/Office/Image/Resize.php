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
 * Resize
 * 13.09.2012 22:33
 */
namespace MOC\Module\Office\Image;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Resize implements Module {
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
	 * @return Resize
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		// TODO: Implement InterfaceInstance() method.
	}

	/** @var Resource $Resource */
	private $Resource = null;

	/**
	 * @param Resource $Resource
	 *
	 * @return Resize
	 */
	public function UseResource( Resource $Resource ) {
		$this->Resource = $Resource;
		return $this;
	}

	/**
	 * @param null $Width
	 * @param null $Height
	 *
	 * @return Resize
	 */
	public function PixelRelative( $Width = null, $Height = null ) {
		$ResourceWidth = $this->GetWidth();
		$ResourceHeight = $this->GetHeight();
		if( ( $Width !== null ) || ( $Height !== null ) ) {
			$Width = ( $Width === null ? ( ( 100 / $ResourceHeight * $Height ) / 100 * $ResourceWidth ) : $Width );
			$Height = ( $Height === null ? ( ( 100 / $ResourceWidth * $Width ) / 100 * $ResourceHeight ) : $Height );
		}
		$Ratio = $ResourceWidth / $ResourceHeight;
		if( ( $Height * $Ratio ) > $Width ) {
			$Height = $Width / $Ratio;
		} else {
			$Width = $Height * $Ratio;
		}
		return $this->PercentAbsolute( round( $Width ), round( $Height ) );
	}

	/**
	 * @param null $Width
	 * @param null $Height
	 *
	 * @return Resize
	 */public function PixelAbsolute( $Width = null, $Height = null ) {
		$ResourceWidth = $this->GetWidth();
		$ResourceHeight = $this->GetHeight();
		if( ! ( $Width !== null ) && ( $Height !== null ) ) {
			$Width = ( $Width === null ? $ResourceWidth : $Width );
			$Height = ( $Height === null ? $ResourceHeight : $Height );
		}
		$Resource = Resource::InterfaceInstance()->Create( $Width, $Height );
		imagecopyresampled( $Resource->Get(), $this->Resource->Get(), 0, 0, 0, 0, $Width, $Height, $ResourceWidth, $ResourceHeight );
		return $this;
	}

	/**
	 * @param null $Width
	 * @param null $Height
	 *
	 * @return Resize
	 */public function PercentRelative( $Width = null, $Height = null ) {
		$Width = ( $Width === null ? 1 : $Width );
		$Height = ( $Height === null ? 1 : $Height );
		return $this->PixelRelative( $this->GetWidth() * $Width, $this->GetHeight() * $Height );
	}

	/**
	 * @param null $Width
	 * @param null $Height
	 *
	 * @return Resize
	 */public function PercentAbsolute( $Width = null, $Height = null ) {
		$Width = ( $Width === null ? 1 : $Width );
		$Height = ( $Height === null ? 1 : $Height );
		return $this->PixelAbsolute( $this->GetWidth() * $Width, $this->GetHeight() * $Height );
	}

	/**
	 * @return int
	 */
	public function GetWidth() {
		return imagesx( $this->Resource->Get() );
	}

	/**
	 * @return int
	 */
	public function GetHeight() {
		return imagesy( $this->Resource->Get() );
	}
}
