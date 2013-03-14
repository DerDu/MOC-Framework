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
 * Encoding
 * 11.02.2013 14:36
 */
namespace MOC\Core;
use \MOC\Api;
/**
 *
 */
class Encoding implements \MOC\Generic\Device\Core {
	/** @var Encoding $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Encoding
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Encoding();
		} return self::$Singleton;
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	private static $DictionaryLatin1ToUtf8 = null;
	private static $DictionaryUtf8ToLatin1 = null;

	private function BuildDictionary() {
		if( self::$DictionaryUtf8ToLatin1 === null || self::$DictionaryLatin1ToUtf8 === null ) {
			for ($Run = 32; $Run <= 255; $Run++) {
				self::$DictionaryLatin1ToUtf8[chr($Run)] = utf8_encode(chr($Run));
				self::$DictionaryUtf8ToLatin1[utf8_encode(chr($Run))] = chr($Run);
			}
		}
	}

	/**
	 * @param string $Text
	 *
	 * @return string
	 */
	public function MixedToLatin1( $Text ) {
		$this->BuildDictionary();
		foreach ( self::$DictionaryUtf8ToLatin1 as $Key => $Val) {
			$Text = str_replace( $Key, $Val, $Text );
		}
		return $Text;
	}

	/**
	 * @param string $Text
	 *
	 * @return string
	 */
	public function MixedToUtf8( $Text ) {
		$this->BuildDictionary();
		return utf8_encode( self::MixedToLatin1( $Text ) );
	}
}
