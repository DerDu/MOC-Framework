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
 * Encoder
 * 04.09.2012 14:20
 */
namespace MOC\Module\Packer\Zip;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Encoder implements Module {

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Encoder
	 */
	public static function InterfaceInstance() {
		return new Encoder();
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

	/** @var File $File */
	private $File = null;

	/**
	 * @param File $File
	 *
	 * @return Encoder
	 */
	public function Open( File $File ) {
		Api::Extension()->Zip()->Create( $File );
		$this->File = $File;
		return $this;
	}

	/**
	 * @param File $File
	 * @param null|string $Base
	 *
	 * @return Encoder
	 */
	public function Content( File $File, $Base = null ) {

		if( $this->File->Exists() ) {
			if( $Base === null ) {
				Api::Extension()->Zip()->Current()->add( $File->GetLocation() );
			} else if( $Base === false ) {
				Api::Extension()->Zip()->Current()->add( $File->GetLocation() , PCLZIP_OPT_REMOVE_ALL_PATH );
			} else {
				Api::Extension()->Zip()->Current()->add( $File->GetLocation() , PCLZIP_OPT_REMOVE_PATH, $Base );
			}
		} else {
			if( $Base === null ) {
				Api::Extension()->Zip()->Current()->create( $File->GetLocation() );
			} else if( $Base === false ) {
				Api::Extension()->Zip()->Current()->create( $File->GetLocation() , PCLZIP_OPT_REMOVE_ALL_PATH );
			} else {
				Api::Extension()->Zip()->Current()->create( $File->GetLocation() , PCLZIP_OPT_REMOVE_PATH, $Base );
			}
		}
		return $this;
	}
}
