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
 * Config
 * 26.03.2013 14:09
 */
namespace MOC\Module\Office\Chart\Data;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Config implements Module {

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
	 * @return Config
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Config();
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
	{
		clickable: boolean
		hoverable: boolean
		shadowSize: number
		highlightColor: color or number
	}
	*/
	private $Configuration = array();

	/** @var null|Type $GraphType */
	private $GraphType = null;

	/**
	 * @param string $HexColor
	 *
	 * @return Config
	 */
	public function Color( $HexColor = '#333333' ) {
		$this->Configuration['color'] = $HexColor;
		return $this;
	}

	/**
	 * @param int $Number
	 *
	 * @return Config
	 */
	public function XAxis( $Number = 1 ) {
		$this->Configuration['xaxis'] = $Number;
		return $this;
	}

	/**
	 * @param int $Number
	 *
	 * @return Config
	 */
	public function YAxis( $Number = 1 ) {
		$this->Configuration['yaxis'] = $Number;
		return $this;
	}

	/**
	 * @return Type
	 */
	public function Type() {
		if( $this->GraphType === null ) {
			$this->GraphType = Type::InterfaceInstance();
		}
		return $this->GraphType;
	}

	/**
	 * @return string
	 */
	public function _getConfiguration() {
		if( $this->GraphType !== null ) {
			return array_merge( $this->Configuration, $this->GraphType->_getConfiguration() );
		}
		return $this->Configuration;
	}
}
