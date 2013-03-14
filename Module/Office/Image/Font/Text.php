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
 * Text
 * 11.02.2013 14:33
 */
namespace MOC\Module\Image\Font;
use \MOC\Api;
/**
 *
 */
class Text implements \MOC\Implement\Common {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Module\Image\Font\Text
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Image\Font\Text();
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
	 * Get Version
	 *
	 * @static
	 * @return \MOC\Core\Version
	 */
	public static function InterfaceVersion() {
		return Api::Core()->Version();
	}

	/**
	 * @param $Text
	 *
	 * @return string
	 */
	public function TextNCR( $Text ) {
		return $this->ConvertToNCR( $this->ConvertToLatin1( $Text ) );
	}

	/**
	 * @param $Text
	 * @param $Font
	 * @param $Size
	 *
	 * @return number
	 */
	public function TextWidth( $Text, $Font, $Size ) {
		// Fetch Font-Box
		$TextBox = imagettfbbox( $Size, 0, $Font, $Text );
		// Fetch Dimension ( + 2 px = FIX: FontStyle )
		return abs($TextBox[2]) + abs($TextBox[0]) + 2;
	}

	/**
	 * @param $Text
	 * @param $Font
	 * @param $Size
	 *
	 * @return number
	 */public function TextHeight( $Text, $Font, $Size ) {
		// Fetch Font-Box
		$TextBox = imagettfbbox( $Size, 0, $Font, $Text );
		// Fetch Dimension
		return abs($TextBox[7]) + abs($TextBox[1]);
	}

	/**
	 * @param $Text
	 *
	 * @return string
	 */
	private function ConvertToLatin1( $Text ) {
		return Api::Core()->Encoding()->MixedToLatin1( $Text );
	}

	/**
	 * @param $Text
	 *
	 * @return string
	 */private function ConvertToUtf8( $Text ) {
		return Api::Core()->Encoding()->MixedToUtf8( $Text );
	}

	/**
	 * @param $Text
	 *
	 * @return string
	 */private function ConvertToNCR( $Text ) {
		$Result = '';
		$Length = strlen( $Text );
		for( $Run = 0; $Run < $Length; $Run++ ) {
			$Result .= "&#".ord( $Text[$Run] ).";";
		}
		return $Result;
	}
}
