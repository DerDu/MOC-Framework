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
 * Post
 * 18.02.2013 20:56
 */
namespace MOC\Module\Network\Http;
use \MOC\Api;
/**
 *
 */
class Post implements \MOC\Generic\Device\Module {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '19.02.2013 14:13', 'Dev' )
		;
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
	 * @return Post
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Post();
	}

	/**
	 * @param string $Url
	 * @param array $Data
	 *
	 * @return bool|string
	 */
	public function Request( $Url, $Data = array() ) {
		set_time_limit(5);
		$Uri = Uri::InterfaceInstance()->Load( $Url );

		foreach( (array)$Data as $Key => $Value ) {
			$Uri->UriQuery()->Set( $Key, $Value );
		}

		$Header =  "POST ".$Uri->GetPath()." HTTP/1.1\r\n";
		$Header .= "Host: ".$Uri->GetHost()."\r\n";
		$Header .= "Content-Type: ".'application/x-www-form-urlencoded'."\r\n";
		$Header .= "Content-Length: ".strlen( $Uri->UriQuery()->GetQueryString() )."\r\n";
		$Header .= "Connection: close"."\r\n\r\n";

		$Body = $Uri->UriQuery()->GetQueryString();


		$ErrorId = 0;
		$ErrorMessage = '';
		$Buffer = '';

		Api::Core()->Error()->Reporting()->Display(false)->Debug(false)->Apply();

		if( ( $Socket = fsockopen( $Uri->GetHost(), $Uri->GetPort(), $ErrorId, $ErrorMessage, 15 ) ) ) {
			fwrite( $Socket, $Header.$Body );
			while( !feof( $Socket ) ) {
				stream_set_timeout( $Socket, 15 );
				if( false === ( $Stream = fread( $Socket, 128 ) ) ) {
					$Buffer = '';
					break;
				} else {
					$Buffer .= $Stream;
				}
			}
			fclose( $Socket );
		}


		$Buffer = explode( "\r\n\r\n", $Buffer );
		$ResponseHeader = explode( "\r\n", array_shift( $Buffer ) );
		$ResponseBody = implode( "\r\n\r\n", $Buffer );
		if( in_array( 'Transfer-Encoding: chunked', $ResponseHeader ) ) {
			$ResponseBody = $this->http_chunked_decode( $ResponseBody );
		}
		return $ResponseBody;
	}

	/**
	 * @param string $Encoded
	 *
	 * @return bool|string
	 */
	private function http_chunked_decode( $Encoded ) {
		if( function_exists( 'http_chunked_decode' ) ) {
			return http_chunked_decode( $Encoded );
		} else {
			$Position = 0;
			$Length = strlen( $Encoded );
			$Decoded = null;

			while(
				( $Position < $Length )
				&& ( $ChunkLength = substr(
					$Encoded, $Position,
						( $PositionNewLine = strpos( $Encoded, "\n", $Position +1 ) )
						- $Position )
				)
			) {
				$Position = $PositionNewLine + 1;
				$ChunkWidth = hexdec( rtrim( $ChunkLength, "\r\n" ) );
				$Decoded .= substr( $Encoded, $Position, $ChunkWidth );
				$Position = strpos( $Encoded, "\n", $Position + $ChunkWidth ) +1;
			}
			return $Decoded;
		}
	}
}

/**
 *
 */
class UriQuery implements \MOC\Generic\Device\Module {
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
	 * @return UriQuery
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new UriQuery();
	}

	private $QueryContent = array();

	public function Load( $QueryString ) {
		$QueryPartList = explode( '&', $QueryString );
		foreach( (array)$QueryPartList as $QueryPart ) {
			$Key = substr( $QueryPart, 0, strpos( $QueryPart, '=' ) );
			$Value = substr( $QueryPart, strpos( $QueryPart, '=' ) +1 );
			$this->QueryContent[$Key] = $Value;
		}
		return $this;
	}

	public function Get( $Key ) {
		return $this->QueryContent[$Key];
	}

	public function Set( $Key, $Value ) {
		$this->QueryContent[$Key] = $Value;
		return $this;
	}

	public function GetQueryString() {
		$QueryString = '';
		foreach( (array)$this->QueryContent as $Key => $Value ) {
			$QueryString .= '&'.$Key.'='.$Value;
		}
		return substr( $QueryString, 1 );
	}

	/**
	 * @return string
	 */
	function __toString() {
		return $this->GetQueryString();
	}
}

/**
 *
 */
class UriScheme implements \MOC\Generic\Device\Module {
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
	 * @return UriScheme
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new UriScheme();
	}

	private $UriScheme = '';

	public function Load( $UriScheme ) {
		$this->UriScheme = $UriScheme;
		return $this;
	}

	public function Http() {
		$this->UriScheme = 'http';
		return $this;
	}
	public function Https() {
		$this->UriScheme = 'https';
		return $this;
	}
	public function Ftp() {
		$this->UriScheme = 'ftp';
		return $this;
	}

	public function GetSchemeString() {
		return $this->UriScheme;
	}

	/**
	 * @return string
	 */
	function __toString() {
		return $this->GetSchemeString();
	}
}

/**
 *
 */
class Uri implements \MOC\Generic\Device\Module {

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
	 * @return Uri
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		$Instance = new Uri();
		$Instance->Scheme = UriScheme::InterfaceInstance()->Http();
		$Instance->Query = UriQuery::InterfaceInstance();
		return $Instance;
	}

	/** @var UriScheme $Scheme */
	private $Scheme = null;
	private $Host = 'localhost';
	private $Port = 80;
	private $Path = '/';
	/** @var UriQuery $Query */
	private $Query = null;
	private $Fragment = '';

	/**
	 * @param string $UriString Example: "foo://example.com:8042/over/there?name=ferret#nose"
	 *
	 * @return \MOC\Module\Network\Http\Uri
	 */
	public function Load( $UriString ) {
		$UriComponentList = parse_url( $UriString );
		// Read components
		if( isset( $UriComponentList['scheme'] ) ) { $this->Scheme = UriScheme::InterfaceInstance()->Load( $UriComponentList['scheme'] ); }
		if( isset( $UriComponentList['host'] ) ) { $this->Host = $UriComponentList['host']; }
		if( isset( $UriComponentList['port'] ) ) { $this->Port = $UriComponentList['port']; }
		if( isset( $UriComponentList['path'] ) ) { $this->Path = $UriComponentList['path']; }
		if( isset( $UriComponentList['query'] ) ) { $this->Query = UriQuery::InterfaceInstance()->Load( $UriComponentList['query'] ); }
		if( isset( $UriComponentList['fragment'] ) ) { $this->Fragment = $UriComponentList['fragment']; }

		return $this;
	}

	public function UriScheme() {
		return  $this->Scheme;
	}
	public function UriQuery() {
		return  $this->Query;
	}

	public function GetPath() {
		return $this->Path;
	}
	public function GetHost() {
		return $this->Host;
	}
	public function GetPort() {
		return $this->Port;
	}

	/**
	 * @return string
	 */
	public function GetUriString() {
		return $this->Scheme->GetSchemeString().'://'
			.$this->Host
			.(!empty($this->Port)?':'.$this->Port:'')
			.$this->Path
			.(!empty($this->Query)?'?'.$this->Query->GetQueryString():'')
			.(!empty($this->Fragment)?'#'.$this->Fragment:'');
	}

	/**
	 * @return string
	 */
	function __toString() {
		return $this->GetUriString();
	}
}
