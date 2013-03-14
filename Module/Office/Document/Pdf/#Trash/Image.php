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
 * Image
 * 14.09.2012 08:30
 */
namespace MOC\Module\Office\Document\Pdf;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Image1 implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Image
	 */
	public static function InterfaceInstance() {
		return new Image();
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
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/** @var null|File $File */
	private $File = null;
	/** @var int $Width */
	private $Width = 0;
	/** @var int $Height */
	private $Height = 0;

	/**
	 * @param File $Image
	 *
	 * @return File|Image|null
	 */
	public function File( File $Image = null ) {
		if( null !== $Image ) {
			$this->File = $Image;
			$this->Width = 0;
			$this->Height = 0;
			return $this;
		} return $this->File;
	}

	/**
	 * @param null $Value
	 *
	 * @return int|Image
	 */
	public function Width( $Value = null ) {
		if( null !== $Value ) {
			$this->Width = $Value;
			return $this;
		} return $this->Width;
	}

	/**
	 * @param null $Value
	 *
	 * @return int|Image
	 */
	public function Height( $Value = null ) {
		if( null !== $Value ) {
			$this->Height = $Value;
			return $this;
		} return $this->Height;
	}

	public function Apply() {
		Api::Extension()->Pdf()->Current()->Image(
			$this->File->GetLocation(),
			Api::Extension()->Pdf()->Current()->GetX(),
			Api::Extension()->Pdf()->Current()->GetY(),
			$this->Width,
			$this->Height
		);
	}
}
