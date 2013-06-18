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
 * Font
 * 11.02.2013 14:33
 */
namespace MOC\Module\Office\Image;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Office\Image\Resource;

/**
 *
 */
class Font implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Font
	 */
	public static function InterfaceInstance() {
		return new Font();
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
		return Api::Core()->Changelog();
	}

	/** @var null|Resource $Resource */
	private $Resource = null;

	/**
	 * @param Resource $Resource
	 *
	 * @return Font
	 */
	public function UseResource( Resource $Resource ) {
		$this->Resource = $Resource;
		return $this;
	}

	/**
	 * @param        $FontLocation
	 * @param        $Text
	 * @param int    $Size
	 * @param string $Color
	 */
	public function EmbeddedImage( $FontLocation, $Text, $Size = 12, $Color = '#000000' ) {
		// TODO: Implement correct ... -.-
		/*
		$Font = __DIR__.'/../../Style/Framework/Resource/Font/CorpoA.ttf';
		$Text = self::ConvertToNCR( Api::Core()->Encoding()->MixedToLatin1( $Text ) );
		$Color = '#777777';
		$Size = 35;

		// Fetch Font-Box
		$TextBox = imagettfbbox( $Size, 0, $Font, $Text );
		// Fetch Dimensions ( + 2 px = FIX: FontStyle )
		$Width = abs($TextBox[2]) + abs($TextBox[0]) + 2;
		$Height = abs($TextBox[7]) + abs($TextBox[1]);

		$Image = Api::Module()->Image()->Resource()->Create( $Width, $Height );

		$Color = self::ConvertHEXToRGB( $Color );

		imagettftext( $Image->Get(), $Size, 0, 0 , abs( $TextBox[5] ),
			imagecolorallocate( $Image->Get(), $Color[0],$Color[1],$Color[2] ),
			$Font, $Text
		);

		$ResizeWidth = $Width * 0.45;
		$ResizeHeight = $Height * 0.59;

		$ResizeImage = Api::Module()->Image()->Resource()->Create( $ResizeWidth, $ResizeHeight );

		imagecopyresampled( $ResizeImage->Get(), $Image->Get(), 0, 0, 0, 0, $ResizeWidth, $ResizeHeight, $Width, $Height );

		ob_start();
		imagepng( $ResizeImage->Get() );
		$Image = ob_get_clean();

		return 'data:image/png;base64,'.base64_encode( $Image );
		*/
	}

	/**
	 * @param $Color
	 *
	 * @return array
	 */
	private function ConvertHEXToRGB( $Color ) {
		$Hex = str_split( substr( strtoupper( trim( $Color ) ), (strlen($Color)>4?-6:-3) ), (strlen($Color)>4?2:1) );
		foreach( (array)$Hex as $Index => $Color ){
			$Hex[$Index] = hexdec( str_pad( $Color, 2, $Color, STR_PAD_LEFT ) );
		} return $Hex;
	}

	/**
	 * @param $Color
	 *
	 * @return array
	 */
	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function ConvertHEXToRGBFloat( $Color ) {
		$Hex = self::ConvertHEXToRGB( $Color );
		foreach( (array)$Hex as $Index => $Color ){
			$Hex[$Index] = (100 / 255 * $Color) / 100;
		} return $Hex;
	}
}
