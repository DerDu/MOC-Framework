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
 * Image
 * 13.02.2013 13:39
 */
namespace MOC\Module\Office;
use \MOC\Api;
/**
 *
 */
class Image implements \MOC\Generic\Device\Module{

	/** @var Image $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Image
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Image();
		} return self::$Singleton;
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
	 * @return Image\Open
	 */
	public function Open() {
		return Image\Open::InterfaceInstance();
	}

	/**
	 * @return Image\Close
	 */
	public function Close() {
		return Image\Close::InterfaceInstance();
	}

	/** @var resource[] $Queue */
	private $Queue = array();
	/** @var resource $Current */
	private $Current = null;

	/**
	 * @return Image\Resize
	 */
	public function Resize() {
		return Image\Resize::InterfaceInstance();
	}

	/**
	 * @return Image\Filter
	 */
	public function Filter() {
		return Image\Filter::InterfaceInstance();
	}

	/**
	 * @return Image\Font
	 */
	public function Font() {
		return Image\Font::InterfaceInstance();
	}

	/**
	 * @param resource $Resource
	 *
	 * @return Image
	 */
	public function _openResource( $Resource ) {
		$this->Current = $Resource;
		array_push( $this->Queue, $this->Current );
		return $this;
	}

	/**
	 * @param $Index
	 *
	 * @return Image
	 */
	public function _selectResource( $Index ) {
		$this->Current = $this->Queue[$Index];
		return $this;
	}

	/**
	 * @return resource
	 */
	public function _getResource() {
		return $this->Current;
	}

	/**
	 * @return Image
	 */
	public function _closeResource() {
		$this->Current = null;
		return $this;
	}
}
