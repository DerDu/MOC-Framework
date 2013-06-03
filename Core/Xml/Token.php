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
 * Token
 * 06.01.2013 15:28
 */
namespace MOC\Core\Xml;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 *
 */
class Token implements Core {
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
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Token
	 */
	public static function InterfaceInstance() {
		return new Token();
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


	const TYPE_OPEN = 1;
	const TYPE_CLOSE = 2;
	const TYPE_SHORT = 3;
	const TYPE_CDATA = 4;
	const TYPE_COMMENT = 5;

	private $Name = '';
	private $AttributeList = array();
	private $Position = 0;
	private $Type = 0;

	private $PatternTagOpen = '!^[^\!/].*?[^/]$!is';
	private $PatternTagClose = '!^/.*?!is';
	private $PatternTagShort = '!^[^\!].*?/$!is';
	private $PatternTagCDATA = '!^\!\[CDATA.*?\]\]$!is';
	private $PatternTagComment = '!^\![^\[].*?--$!is';

	/**
	 * @param $Content
	 *
	 * @return Token
	 */
	public function Setup( &$Content ) {
		$this->Read( $Content );
		return $this;
	}

	/**
	 * @param $Content
	 */
	private function Read( &$Content ) {
		$this->Position = $Content[1];

		$Token = explode(' ', $Content[0] );
		$this->Name = preg_replace( '!/$!is', '', array_shift( $Token ) );

		preg_match_all( '![\w]+="[^"]*?"!is', $Content[0], $Matches );
		$Token = $Matches[0];

		$Attribute = array();
		while( null !== ( $AttributeString = array_pop( $Token ) ) ) {
			if( $AttributeString != '/' ) {
				preg_match( '!(.*?)="(.*?)(?=")!is', $AttributeString, $Attribute );
				if( count( $Attribute ) == 3 ) {
					$this->AttributeList[$Attribute[1]] = $Attribute[2];
				}
			}
		}
		$this->ReadType( $Content[0] );
	}

	/**
	 * @param $Content
	 */
	private function ReadType( &$Content ) {
		if( preg_match( $this->PatternTagOpen, $Content ) ) {
			$this->Type = self::TYPE_OPEN;
		} else
			if( preg_match( $this->PatternTagClose, $Content ) ) {
				$this->Type = self::TYPE_CLOSE;
			} else
				if( preg_match( $this->PatternTagShort, $Content ) ) {
					$this->Type = self::TYPE_SHORT;
				} else
					if( preg_match( $this->PatternTagCDATA, $Content ) ) {
						$this->Type = self::TYPE_CDATA;
					} else
						if( preg_match( $this->PatternTagComment, $Content ) ) {
							$this->Type = self::TYPE_COMMENT;
						}
	}

	/**
	 * @return string
	 */
	public function GetName() {
		return $this->Name;
	}

	/**
	 * @return int
	 */
	public function GetPosition() {
		return $this->Position;
	}

	/**
	 * @return array
	 */
	public function GetAttributeList() {
		return $this->AttributeList;
	}

	/**
	 * @return bool
	 */
	public function isOpenTag() {
		return $this->Type == self::TYPE_OPEN;
	}

	/**
	 * @return bool
	 */
	public function isCloseTag() {
		return $this->Type == self::TYPE_CLOSE;
	}

	/**
	 * @return bool
	 */
	public function isShortTag() {
		return $this->Type == self::TYPE_SHORT;
	}

	/**
	 * @return bool
	 */
	public function isCDATATag() {
		return $this->Type == self::TYPE_CDATA;
	}

	/**
	 * @return bool
	 */
	public function isCommentTag() {
		return $this->Type == self::TYPE_COMMENT;
	}

}
