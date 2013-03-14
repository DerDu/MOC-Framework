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
 * 14.09.2012 08:30
 */
namespace MOC\Module\Office\Document\Pdf;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Font1 implements Module {
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
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/** @var null|File $Family */
	private $Family = null;
	private $Size = 10;
	private $Color = '000000';

	/**
	 * @param File $File
	 *
	 * @return File|Font|null
	 */
	public function Family( File $File = null ) {
		if( null !== $File ) {
			Api::Extension()->Pdf()->Current()->fontpath = realpath( $File->GetPath() ).DIRECTORY_SEPARATOR;
			if( !file_exists( $File->GetPath().DIRECTORY_SEPARATOR.$File->GetName().'.php' ) ) {
				MakeFont( $File->GetLocation() );
			}
			$this->Family = $File;
			return $this;
		} return $this->Family;
	}

	/**
	 * @param null $Value
	 *
	 * @return int|Font
	 */
	public function Size( $Value = null ) {
		if( null !== $Value ) {
			$this->Size = $Value;
			return $this;
		} return $this->Size;
	}

	/**
	 * @param null $Value
	 *
	 * @return Font|string
	 */
	public function Color( $Value = null ) {
		if( null !== $Value ) {
			$this->Color = $Value;
			return $this;
		} return $this->Color;
	}

	/**
	 * @return Font
	 */
	public function Apply() {
		$Instance = Api::Extension()->Pdf()->Current();
		$Instance->AddFont( $this->Family()->GetName(), '', $this->Family()->GetName().'.php' );
		$Instance->SetFont( $this->Family()->GetName() );
		$Instance->SetFontSize( $this->Size() );
		$Instance->SetTextColor(
			hexdec( substr( $this->Color(), 0, 2 ) ),
			hexdec( substr( $this->Color(), 2, 2 ) ),
			hexdec( substr( $this->Color(), 4, 2 ) )
		);
		return $this;
	}
}
