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
 * Tokenizer
 * 06.01.2013 15:37
 */
namespace MOC\Core\Xml;
use MOC\Api;
use MOC\Core\Xml\Token;
use MOC\Generic\Device\Core;

/**
 *
 */
class Tokenizer implements Core {
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
	 * @return Tokenizer
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Tokenizer();
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


	private $Content = '';
	private $PatternTokenizer = '!(?<=<)[^\?<>]*?(?=>)!is';
	private $PatternComment = '!(?<=<\!--).*?(?=//-->)!is';
	private $PatternCDATA = '!(?<=<\!\[CDATA\[).*?(?=\]\]>)!is';
	private $Result = array();

	/**
	 * @param $Content
	 *
	 * @return Tokenizer
	 */
	public function Setup( $Content ) {
		$this->Content = $Content;
		$this->Tokenize();
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetResult() {
		return $this->Result;
	}

	/**
	 * @return string
	 */
	public function GetContent() {
		return $this->Content;
	}
	private function Tokenize() {
		$this->EncodeComment();
		$this->EncodeCDATA();
		preg_match_all( $this->PatternTokenizer, $this->Content, $this->Result, PREG_OFFSET_CAPTURE );
		$this->Result = array_map( function($C){ return Token::InterfaceInstance()->Setup( $C ); }, $this->Result[0] );
	}
	private function EncodeComment() {
		$this->Content = preg_replace_callback(
			$this->PatternComment,
			array( $this, 'EncodeBase64' ),
			$this->Content
		);
	}
	private function EncodeCDATA() {
		$this->Content = preg_replace_callback(
			$this->PatternCDATA,
			array( $this, 'EncodeBase64' ),
			$this->Content
		);
	}

	/**
	 * @param $Content
	 *
	 * @return string
	 * @noinspection PhpUnusedPrivateMethodInspection
	 */
	private function EncodeBase64( $Content ) {
		return base64_encode( $Content[0] );
	}
}