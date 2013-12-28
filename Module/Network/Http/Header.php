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
 * Header
 * 28.12.2013 19:06
 */
namespace MOC\Module\Network\Http;
use MOC\Api;
use MOC\Generic\Device\Module;
/**
 *
 */
class Header implements Module {

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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
	 * @return Header
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Header();
	}

	/**
	 * Get Request-Headers (HTTP_...)
	 *
	 * @return array
	 *
	 * Based on: http://www.php.net/manual/de/function.apache-request-headers.php#70810
	 * @link http://www.php.net/manual/de/function.apache-request-headers.php#70810
	 */
	public function GetList() {
		if( function_exists( 'apache_request_headers' ) ) {
			return apache_request_headers();
		} else {
			$HeaderList = array();
			$HeaderPattern = '/\AHTTP_/';
			foreach( $_SERVER as $Key => $Value ) {
				if( preg_match( $HeaderPattern, $Key ) ) {
					$KeyList = preg_replace( $HeaderPattern, '', $Key );
					// do some nasty string manipulations to restore the original letter case
					// this should work in most cases
					$HeaderMatchList = explode( '_', $KeyList );
					foreach( (array)$HeaderMatchList as $Index => $Part ) {
						$HeaderMatchList[$Index] = ucfirst( strtolower( $Part ) );
					}
					$KeyList = implode( '-', $HeaderMatchList );

					$HeaderList[$KeyList] = $Value;
				}
			}
			return( $HeaderList );
		}
	}

	/**
	 * Sets http response headers "last-modified" and "etag"
	 *
	 * This function also checks if the client cached version of the requested site
	 * is still up to date by comparing last-modified and/or etag headers of server
	 * and client for a given last modification timestamp and identifier (optional).
	 * If this client cached version is up to date the status header 304 (not modified)
	 * will be set.
	 *
	 * @link http://ansas-meyer.de/programmierung/php/http-response-header-last-modified-und-etag-mit-php-fuer-caching-setzen
	 *
	 * @param int $Timestamp
	 * @param string $Identifier
	 *
	 * @return bool|null
	 */
	public function SetLastModified( $Timestamp, $Identifier = '' ) {
		// Unable to do something
		if( headers_sent() ) {
			return null;
		}
		// Get Request-Headers
		$HeaderList = $this->GetList();
		// Get ETag (Client)
		$ClientETag = ( isset( $HeaderList['If-None-Match'] ) ? trim( $HeaderList['If-None-Match'] ) : null );
		// Get LastModified (Client)
		$ClientLastModified = ( isset( $HeaderList['If-Modified-Since'] ) ? trim( $HeaderList['If-Modified-Since'] ) : null );
		// Calculate (possible) new Header-Values
		$ServerLastModified = gmdate('D, d M Y H:i:s', $Timestamp ) . ' GMT';
		$ServerETag = '"' . md5( $Timestamp . $Identifier ) . '"';
		// Get Client vs. Server
		$MatchLastModified = $ClientLastModified == $ServerLastModified;
		$MatchETag = $ClientETag == $ServerETag;
		// Set new Headers
		header('Last-Modified: ' . $ServerLastModified);
		header('ETag: ' . $ServerETag);
		// Check: Server vs. Client Headers
		if( ( $ClientLastModified && $ClientETag ) ? $MatchLastModified && $MatchETag : $MatchLastModified || $MatchETag ) {
			// Same
			header('HTTP/1.1 304 Not Modified');
			return true;
		} else {
			// Different
			return false;
		}
	}
}
