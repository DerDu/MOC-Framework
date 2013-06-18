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
 * Url
 * 08.09.2012 14:51
 */
namespace MOC\Core\Proxy;
use MOC\Api;
use MOC\Core\Drive\File;
use MOC\Core\Drive;
use MOC\Generic\Common;

/**
 *
 */
class Seo implements Common {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Seo;
	 */
	public static function InterfaceInstance() {
		return new Seo();
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
		return Api::Core()->Changelog();
	}

	/** @var File $SeoFile */
	private $SeoFile = null;

	/**
	 * @param File $File
	 *
	 * @return string
	 */
	public function Url( File $File ) {
		$this->SeoFile = $File;
		return $this->UrlScheme().$this->UrlHost().( $this->UrlPort()?':'.$this->UrlPort():'' ).'/'.$this->UrlPath().'/'.basename( $File->Location() );
	}


	/**
	 * @return mixed
	 */
	private function UrlPath() {
		$Directory = Drive::InterfaceInstance()->Directory()->Handle( $this->SeoFile->Path() );
//		$Directory->Handle( trim( trim( str_replace( $Directory->DirectoryRoot(), '', $Directory->Location() ), '\\'), '/' ) );
//		return str_replace( '\\', '/', $Directory->Location() );
		return str_replace( '\\', '/', trim( trim( str_replace( $Directory->DirectoryRoot(), '', $Directory->Location() ), '\\'), '/' ) );
	}

	private function UrlHost() {
		return $_SERVER['SERVER_NAME'];
	}

	/**
	 * @return string
	 */
	private function UrlScheme() {
		switch( $this->UrlPort() ) {
			case '80': return 'http://';
			case '21': return 'ftp://';
			case '443': return 'https://';
			default: return 'http://';
		}
	}

	/**
	 * @return bool
	 */
	private function UrlPort() {
		if( !isset( $_SERVER['SERVER_PORT'] ) ) {
			return false;
		}
		return $_SERVER['SERVER_PORT'];
	}
}
