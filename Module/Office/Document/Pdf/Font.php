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
 * Font
 * 27.02.2013 16:06
 */
namespace MOC\Module\Office\Document\Pdf;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Font implements Module {
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
	 * @return Font
	 */
	public static function InterfaceInstance() {
		return new Font();
	}

	/**
	 * @param File $File
	 * @param null|string $Name
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function Register( File $File = null, $Name = null ) {
		Api::Extension()->Pdf()->Current()->fontpath = realpath( $File->GetPath() ).DIRECTORY_SEPARATOR;
		if( !file_exists( $File->GetPath().DIRECTORY_SEPARATOR.$File->GetName().'.php' ) ) {
			MakeFont( $File->GetLocation() );
		}
		$Name = ( null === $Name ? $File->GetName() : $Name );
		Api::Extension()->Pdf()->Current()->AddFont( $Name, '', $File->GetName().'.php' );
		Api::Extension()->Pdf()->Current()->SetFont( $Name );
		return Api::Module()->Office()->Document()->Pdf();
	}

	/**
	 * @param string $Name
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function Select( $Name ) {
		Api::Extension()->Pdf()->Current()->SetFont( $Name );
		return Api::Module()->Office()->Document()->Pdf();
	}

	/**
	 * @return Font\Color
	 */
	public function Color() {
		return Font\Color::InterfaceInstance();
	}

	/**
	 * @param int $Size pt
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function Size( $Size = 10 ) {
		Api::Extension()->Pdf()->Current()->SetFontSize( $Size );
		return Api::Module()->Office()->Document()->Pdf();
	}
}
