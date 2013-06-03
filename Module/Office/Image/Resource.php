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
 * Resource
 * 13.09.2012 22:44
 */
namespace MOC\Module\Office\Image;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Resource implements Module {

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Resource
	 */
	public static function InterfaceInstance() {
		return new Resource();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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

	/** @var null|\resource $Resource */
	private $Resource = null;
	/** @var null|File $File */
	private $File = null;

	/**
	 * @param File $File
	 *
	 * @return Resource
	 */
	public function Load( File $File ) {
		$this->File = $File;
		if( $this->File->Exists() ) {
			$Factory = $this->GetLoadFactory( $this->File );
			$this->Resource = $Factory( $this->File->GetLocation() );
		}
		return $this;
	}

	/**
	 * @param File $File
	 *
	 * @return Resource
	 */
	public function Save( File $File = null ) {
		if( null === $File ) {
			$File = $this->File;
		}
		$Factory = $this->GetSaveFactory( $File );
		$Type = $this->GetTypeFactory( $File );
		switch( $Type ) {
			case 'jpeg': {
				$Factory( $this->Resource, $File->GetLocation(), 100 );
				break;
			}
			default: {
				imagesavealpha( $this->Resource, true );
				$Factory( $this->Resource, $File->GetLocation() );
			}
		}
		$this->Load( $File );
		return $this;
	}

	/**
	 * @param $Width
	 * @param $Height
	 *
	 * @return Resource
	 */
	public function Create( $Width, $Height ) {
		$this->Resource = imagecreatetruecolor( $Width, $Height );
		imagealphablending( $this->Resource, false );
		imagesavealpha( $this->Resource, true );
		imagefill( $this->Resource, 0, 0, imagecolorallocatealpha( $this->Resource, 0, 0, 0, 127 ) );
		return $this;
	}

	/**
	 * @return null|resource
	 */
	public function Get() {
		return $this->Resource;
	}

	/**
	 * @param File $File
	 *
	 * @return mixed
	 */
	private function GetTypeFactory( File $File ) {
		return str_replace( 'jpg', 'jpeg', strtolower( $File->GetExtension() ) );
	}

	/**
	 * @param File $File
	 *
	 * @return string
	 */
	private function GetLoadFactory( File $File ) {
		return 'imagecreatefrom'.$this->GetTypeFactory( $File );
	}

	/**
	 * @param File $File
	 *
	 * @return string
	 */
	private function GetSaveFactory( File $File ) {
		return 'image'.$this->GetTypeFactory( $File );
	}
}
