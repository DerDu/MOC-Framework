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
 * Variable
 * 28.12.2012 14:56
 */
namespace MOC\Core\Template;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 *
 */
class Variable implements Core {
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
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Variable
	 */
	public static function InterfaceInstance() {
		return new Variable();
	}

	const REGEX_PATTERN_LEFT = '!(\$\{(';
	const REGEX_PATTERN_RIGHT = ')\})!s';

	/** @var string $Identifier */
	private $Identifier = '';
	/** @var string $Value */
	private $Value = '';
	/** @var Format|null $Format */
	private $Format = null;

	/**
	 * @param $Identifier
	 * @param $Value
	 *
	 * @return Variable
	 */
	function SetData( $Identifier, $Value ) {
		$this->Identifier = $Identifier;
		$this->Value = $Value;
		return $this;
	}

	/**
	 * @param Format $TplFormat
	 *
	 * @return Variable
	 */
	public function AssignFormat( Format $TplFormat = null ) {
		$this->Format = $TplFormat;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetIdentifier() {
		return $this->Identifier;
	}

	/**
	 * @return mixed|string
	 */
	public function GetPayload() {
		if( $this->Format === null ) {
			return $this->Value;
		} else {
			return preg_replace( $this->Format->GetSearchPattern(), $this->Format->GetFormatPattern(), $this->Value );
		}
	}
}
