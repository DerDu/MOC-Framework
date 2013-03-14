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
 * Write
 * 16.10.2012 11:23
 */
namespace MOC\Module\Network\Ftp\File;
use \MOC\Api;
/**
 * File-Write
 */
class Write extends \MOC\Module\Network\Ftp\File\Property implements \MOC\Generic\Device\Module {
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
	 * @return Write
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Write();
	}

	/**
	 * File-Copy
	 *
	 * @param string $Path
	 *
	 * @return bool
	 */
	public function Copy( $Path ) {
		if( ftp_mdtm( $this->Connection()->Handler(), $this->Location() ) > -1 ) {
			$Source = \MOC\Module\Network\Ftp\File::InterfaceInstance();
			$Source->Connection( $this->Connection() );
			$Source->Handle( $this->Location() );
			$Target = \MOC\Module\Network\Ftp\File::InterfaceInstance();
			$Target->Connection( $this->Connection() );
			$Target->Handle( $Path.'/'.basename($this->Location()) );
			$Target->Content( $Source->Content() );
			$Target->Save();
			$this->Location( $Target->Location() );
			$this->UpdateProperties();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * File-Save
	 *
	 * @return bool
	 */
	public function Save() {
		$LocalFile = Api::Core()->Cache()->Group( __METHOD__ )->Identifier( $this->Location() )->Timeout( 9999999999 )->Set( $this->Content );
		ftp_put( $this->Connection()->Handler(), $this->Location(), $LocalFile->Location(), FTP_BINARY );
		$LocalFile->Remove();
		$this->UpdateProperties();
		$this->Changed( false );
		return true;
	}

	/**
	 * File-Save-As
	 *
	 * @param string $Location
	 *
	 * @return bool
	 */
	public function SaveAs( $Location ) {
		$LocalFile = Api::Core()->Cache()->Group( __METHOD__ )->Identifier( $this->Location() )->Timeout( 9999999999 )->Set( $this->Content );
		ftp_put( $this->Connection()->Handler(), $this->UpdateSyntax( $Location ), $LocalFile->Location(), FTP_BINARY );
		$LocalFile->Remove();
		$this->Location( $Location );
		$this->UpdateProperties();
		$this->Changed( false );
		return true;
	}

	/**
	 * File-Move
	 *
	 * @param string $Location
	 *
	 * @return bool
	 */
	public function Move( $Location ) {
		if( ftp_mdtm( $this->Connection()->Handler(), $this->Location() ) > -1 ) {
			$Result = ftp_rename( $this->Connection()->Handler(), $this->Location(), $this->UpdateSyntax( $Location ) );
			$this->UpdateProperties();
			return $Result;
		} else {
			return false;
		}
	}

	/**
	 * File-Remove
	 *
	 * @return bool
	 */
	public function Remove() {
		if( ftp_mdtm( $this->Connection()->Handler(), $this->Location() ) > -1 ) {
			return ftp_delete( $this->Connection()->Handler(), $this->Location() );
		} else {
			return false;
		}
	}

	/**
	 * File-Touch
	 *
	 * @return bool
	 */
	public function Touch() {
		if( ftp_mdtm( $this->Connection()->Handler(), $this->Location() ) > -1 ) {
			$Result = ftp_rename( $this->Connection()->Handler(), $this->Location(), $this->Location() );
			$this->UpdateProperties();
			return $Result;
		} else {
			return false;
		}
	}
}
