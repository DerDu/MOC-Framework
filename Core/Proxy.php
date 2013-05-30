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
 * Proxy
 * 02.09.2012 16:20
 */
namespace MOC\Core;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 *
 */
class Proxy implements Core {
	/** @var Proxy $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Proxy
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Proxy();
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
		return Api::Core()->Depending()
			->Package( '\MOC\Core\Drive', Version::InterfaceInstance()->Build(1)->Update(2)->Fix(1) );
	}

	const PROXY_NONE = 0;
	const PROXY_RELAY = 1;
	const PROXY_BASIC = 2;

	/** @var null|Proxy\Server $Server */
	private $Server = null;
	/** @var null|Proxy\Credentials $Credentials */
	private $Credentials = null;

	private $Timeout = 5;
	private $ErrorNumber = null;
	private $ErrorString = null;
	private $ProxyType = self::PROXY_NONE;

	/**
	 * @param Drive\File $File
	 * @return string
	 */
	public function Url( Drive\File $File ) {
		return Proxy\Seo::InterfaceInstance()->Url( $File );
	}

	/**
	 * @param Proxy\Server      $Server
	 * @param Proxy\Credentials $Credentials
	 *
	 * @return Proxy
	 */
	public function Open( Proxy\Server $Server, Proxy\Credentials $Credentials = null ) {

		$this->Server = $Server;
		$this->Credentials = $Credentials;

		if( $this->Credentials == null ) {
			$this->ProxyType = self::PROXY_RELAY;
		} else {
			$this->ProxyType = self::PROXY_BASIC;
		}

		return $this;
	}

	/**
	 * @return Proxy
	 */
	public function Close() {

		$this->Server = null;
		$this->Credentials = null;

		$this->ProxyType = self::PROXY_NONE;

		return $this;
	}

	/**
	 * @return Proxy\Server
	 */
	public function Server() {
		return Proxy\Server::InterfaceInstance();
	}

	/**
	 * @return Proxy\Credentials
	 */
	public function Credentials() {
		return Proxy\Credentials::InterfaceInstance();
	}
	/**
	 * @param string $Url
	 * @param bool $Status
	 *
	 * @return null|string
	 * @throws \Exception
	 */
	public function GetFile( $Url, $Status = false ) {
		switch( $this->IsProxy() ) {
			case self::PROXY_NONE: return $this->ProxyNone( $Url, $Status );
			case self::PROXY_RELAY: return $this->ProxyRelay( $Url, $Status );
			case self::PROXY_BASIC: return $this->ProxyBasic( $Url, $Status );
			default: throw new \Exception('Proxy not available!');
		}
	}

	/**
	 * @param string $Url
	 *
	 * @return string
	 */
	public function GetContent( $Url ) {
		return $this->cURL( $Url );
	}

	/**
	 * @return bool|int
	 */
	private function IsProxy() {
		if( $this->ProxyType !== self::PROXY_NONE ) {
			return $this->ProxyType;
		} else {
			return false;
		}
	}

	/**
	 * @param $Url
	 * @param $Status
	 *
	 * @return null|string
	 */
	private function ProxyNone( $Url, $Status ) {
		$this->Server = Proxy\Server::InterfaceInstance();
		$this->Server->Host( parse_url( $Url, PHP_URL_HOST ) );
		if( parse_url( $Url, PHP_URL_PORT ) === null ) {
			switch( strtoupper( parse_url( $Url, PHP_URL_SCHEME ) ) ) {
				case 'HTTP': { $this->Server->Port( '80' ); break; }
				case 'HTTPS': { $this->Server->Port( '443' ); break; }
			}
		} else {
			$this->Server->Port( parse_url( $Url, PHP_URL_PORT ) );
		}
		if( $this->Server->Port() == '443' ) {
			return file_get_contents( $Url );
		}
		if( ($Socket = fsockopen( $this->Server->Host(), $this->Server->Port(), $this->ErrorNumber, $this->ErrorString, $this->Timeout )) ) {
			$Content = '';
			fputs( $Socket, "GET ".$Url." HTTP/1.0\r\nHost: ".parse_url( $Url, PHP_URL_HOST )."\r\n\r\n");
			while( !feof( $Socket ) ) {
				$Content .= fread( $Socket, 4096 );
				if( $Status ) {
					$Match = array();
					preg_match( '![0-9]{3}!', $Content, $Match );
					return $Match[0];
				}
			}
			// Check Status e.g 302 -> Redirect
			$ContentToCheck = $this->StatusCode( $Content, $Url );
			fclose( $Socket );
			if( $Content == $ContentToCheck ) {
				// Not Modified -> Care for Header
				$Content = substr( $Content, strpos( $Content, "\r\n\r\n" ) +4 );
			} else {
				// Already Modified -> Nothing to do
				$Content = $ContentToCheck;
			}
		} else {
			trigger_error( '['.$this->ErrorNumber.'] '.$this->ErrorString );
			$Content = null;
		}
		return $Content;
	}

	/**
	 * @param $Url
	 * @param $Status
	 *
	 * @return null|string
	 */
	private function ProxyRelay( $Url, $Status ) {
		if( ($Socket = fsockopen( $this->Server->Host(), $this->Server->Port(), $this->ErrorNumber, $this->ErrorString, $this->Timeout )) ) {
			$Content = '';
			fputs( $Socket, "GET ".$Url." HTTP/1.0\r\nHost: ".$this->Server->Host()."\r\n\r\n");
			while( !feof( $Socket ) ) {
				$Content .= fread( $Socket, 4096 );
				if( $Status ) {
					$Match = array();
					preg_match( '![0-9]{3}!', $Content, $Match );
					return $Match[0];
				}
			}
			// Check Status e.g 302 -> Redirect
			$ContentToCheck = $this->StatusCode( $Content );
			fclose( $Socket );
			if( $Content == $ContentToCheck ) {
				// Not Modified -> Care for Header
				$Content = substr( $Content, strpos( $Content, "\r\n\r\n" ) +4 );
			} else {
				// Already Modified -> Nothing to do
				$Content = $ContentToCheck;
			}
		} else {
			trigger_error( '['.$this->ErrorNumber.'] '.$this->ErrorString );
			$Content = null;
		}
		return $Content;
	}

	/**
	 * @param $Url
	 * @param $Status
	 *
	 * @return null|string
	 */
	private function ProxyBasic( $Url, $Status ) {
		if( ($Socket = fsockopen( $this->Server->Host(), $this->Server->Port(), $this->ErrorNumber, $this->ErrorString, $this->Timeout )) ) {
			$Content = '';
			fputs( $Socket, "GET ".$Url." HTTP/1.0\r\nHost: ".$this->Server->Host()."\r\n");
			fputs( $Socket, "Proxy-Authorization: Basic ".base64_encode( $this->Credentials->Username().':'.$this->Credentials->Password() ) . "\r\n\r\n");
			while( !feof( $Socket ) ) {
				$Content .= fread( $Socket, 4096 );
				if( $Status ) {
					$Match = array();
					preg_match( '![0-9]{3}!', $Content, $Match );
					return $Match[0];
				}
			}
			// Check Status e.g 302 -> Redirect
			$ContentToCheck = $this->StatusCode( $Content );
			fclose( $Socket );
			if( $Content == $ContentToCheck ) {
				// Not Modified -> Care for Header
				$Content = substr( $Content, strpos( $Content, "\r\n\r\n" ) +4 );
			} else {
				// Already Modified -> Nothing to do
				$Content = $ContentToCheck;
			}
		} else {
			trigger_error( '['.$this->ErrorNumber.'] '.$this->ErrorString );
			$Content = null;
		}
		return $Content;
	}

	/**
	 * @param      $Content
	 * @param null $Url
	 *
	 * @return null|string
	 */
	private function StatusCode( $Content, $Url = null ) {
		preg_match( '![0-9]{3}!', $Content, $Match );
		switch( $Match[0] ) {
			case '302': {
				preg_match( '!(?<=Location: )([^\s\n]+)!', $Content, $Match );
				if( parse_url( $Match[0] ) ) {
					// If Location is not correct
					if( null !== $Url ) {
						$Match[0] = str_replace( $Url, '', $Match[0] ).'?'.parse_url( $Url, PHP_URL_QUERY );
					}
					$Content = $this->GetFile( $Match[0] );
				}
				return $Content;
				break;
			}
			case '301': {
				preg_match( '!(?<=Location: )([^\s\n]+)!', $Content, $Match );
				if( parse_url( $Match[0] ) ) {
					$Content = $this->GetFile( $Match[0] );
				}
				return $Content;
				break;
			}
			case '200': {
				return $Content;
			}
			default: {
				trigger_error( __CLASS__.': Status-Code '.$Match[0] );
				return $Content;
			}
		}
	}

	private static $cURL = null;

	/**
	 * @param $Url
	 *
	 * @return string
	 */
	private function cURL( $Url ) {
		self::$cURL = curl_init();
		curl_setopt ( self::$cURL, CURLOPT_URL, $Url );
		curl_setopt ( self::$cURL, CURLOPT_HEADER, 0 );
		ob_start();
		curl_exec ( self::$cURL );
		curl_close ( self::$cURL );
		$Content = ob_get_contents();
		ob_end_clean();
		return $Content;
	}

	/**
	 * @return string
	 */
	function __toString() {
		return '';
	}
}
