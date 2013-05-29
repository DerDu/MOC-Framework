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
 * Close
 * 25.02.2013 16:06
 */
namespace MOC\Module\Office\Image;
use MOC\Api;
/**
 *
 */
class Close implements \MOC\Generic\Device\Module {
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
	 * @return Close
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Close();
	}

	/**
	 * @param int $Width
	 * @param int $Height
	 *
	 * @return \MOC\Module\Office\Image
	 */
	public function Blank( $Width, $Height ) {
		$Resource = imagecreatetruecolor( $Width, $Height );
		imagealphablending( $Resource, false );
		imagesavealpha( $Resource, true );
		imagefill( $Resource, 0, 0, imagecolorallocatealpha( $Resource, 0, 0, 0, 127 ) );
		Api::Module()->Office()->Image()->_openResource( $Resource );
		return Api::Module()->Office()->Image();
	}

	/**
	 * @param \MOC\Module\Drive\File $File
	 *
	 * @return \MOC\Module\Office\Image
	 */
	public function File( \MOC\Module\Drive\File $File ){
		$Factory = $this->GetSaveFactory( $File );
		$Type = $this->GetTypeFactory( $File );
		switch( $Type ) {
			case 'jpeg': {
				$Factory( Api::Module()->Office()->Image()->_getResource(), $File->GetLocation(), 100 );
				break;
			}
			default: {
				imagesavealpha( Api::Module()->Office()->Image()->_getResource(), true );
				$Factory( Api::Module()->Office()->Image()->_getResource(), $File->GetLocation() );
			}
		}
		Api::Module()->Office()->Image()->_closeResource();
		Api::Module()->Office()->Image()->Open()->File( $File );
		return Api::Module()->Office()->Image();
	}

	/**
	 * @param \MOC\Module\Drive\File $File
	 *
	 * @return mixed
	 */
	private function GetTypeFactory( \MOC\Module\Drive\File $File ) {
		return str_replace( 'jpg', 'jpeg', strtolower( $File->GetExtension() ) );
	}

	/**
	 * @param \MOC\Module\Drive\File $File
	 *
	 * @return string
	 */
	private function GetSaveFactory( \MOC\Module\Drive\File $File ) {
		return 'image'.$this->GetTypeFactory( $File );
	}
}