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
 * Connection
 * 15.10.2012 15:38
 */
namespace MOC\Module\Network\Ftp\Transport;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Connection implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Connection
	 */
	public static function InterfaceInstance() {
		return new Connection();
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

	/** @var null|\resource */
	private $Resource = null;

	/**
	 * @param     $ServerName
	 * @param int $Port
	 * @param int $TimeOut
	 *
	 * @return Connection
	 */
	public function Open( $ServerName, $Port = 21, $TimeOut = 15 ){
		if( $this->Handler() === null ) {
			$this->Resource = ftp_connect( $ServerName, $Port, $TimeOut );
		} else {
			$this->Close();
			return $this->Open( $ServerName, $Port, $TimeOut );
		}
		return $this;
	}

	/**
	 * @param $UserName
	 * @param $Password
	 *
	 * @return Connection
	 */
	public function Login( $UserName, $Password ){
		ftp_login( $this->Handler(), $UserName, $Password );
		return $this;
	}

	public function Close(){
		ftp_close( $this->Handler() );
		$this->Resource = null;
	}

	/**
	 * @param bool $Bool
	 */
	public function TogglePassive( $Bool = true ) {
		ftp_pasv( $this->Resource, $Bool );
	}

	/**
	 * @param bool $Bool
	 */
	public function ToggleAutoSeek( $Bool = true ) {
		ftp_set_option( $this->Resource, FTP_AUTOSEEK, $Bool );
	}

	/**
	 * @param bool $Bool
	 */
	public function ToggleContinue( $Bool = true ) {
		ftp_set_option( $this->Resource, FTP_AUTORESUME, $Bool );
	}

	/**
	 * @return null|resource
	 */
	public function Handler() {
		return $this->Resource;
	}
}
