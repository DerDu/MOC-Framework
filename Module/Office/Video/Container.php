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
 * Container
 * 30.05.2013 11:44
 */
namespace MOC\Module\Office\Video;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Container implements Module {

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
	 * @return Container
	 */
	public static function InterfaceInstance() {
		return new Container();
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

	private $Width = 320;
	private $Height = 240;
	private $Identifier = '';
	/** @var \MOC\Module\Drive\File $File */
	private $File = null;
	/** @var string $Url */
	private $Url = null;

	/**
	 * @param int $Pixel
	 *
	 * @return Container
	 */
	public function Width( $Pixel = 320 ) {
		$this->Width = $Pixel;
		return $this;
	}

	/**
	 * @param int $Pixel
	 *
	 * @return Container
	 */
	public function Height( $Pixel = 240 ) {
		$this->Height = $Pixel;
		return $this;
	}

	/**
	 * @param string $Id
	 *
	 * @return Container
	 */
	public function Identifier( $Id = 'Placeholder' ) {
		$this->Identifier = $Id;
		return $this;
	}

	/**
	 * @param \MOC\Module\Drive\File $File
	 *
	 * @return Container
	 */
	public function File( File $File ) {
		$this->File = $File;
		return $this;
	}

	/**
	 * @param string $Url
	 *
	 * @return Container
	 */
	public function Url( $Url ) {
		$this->Url = $Url;
		return $this;
	}

	/**
	 * @return int
	 */
	public function _getWidth() {
		return $this->Width;
	}

	/**
	 * @return int
	 */
	public function _getHeight() {
		return $this->Height;
	}

	/**
	 * @return string
	 */
	public function _getIdentifier() {
		return $this->Identifier;
	}

	/**
	 * @return File
	 */
	public function _getFile() {
		return $this->File;
	}

	/**
	 * @return string
	 */
	public function _getUrl() {
		return $this->Url;
	}
}
