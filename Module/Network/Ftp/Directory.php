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
 * Directory
 * 15.10.2012 15:38
 */
namespace MOC\Module\Network\Ftp;
use \MOC\Api;
/**
 *
 */
class Directory extends \MOC\Module\Network\Ftp\Directory\Read implements \MOC\Generic\Device\Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Module\Network\Ftp\Directory
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Network\Ftp\Directory();
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

	/**
	 * Directory-to-Handle
	 *
	 * @param string $Location
	 *
	 * @return \MOC\Module\Network\Ftp\Directory
	 */
	public function Handle( $Location ) {
		$this->Location( $Location );
		$this->UpdateProperties();
		return $this;
	}

	/**
	 * Directory-Exists
	 *
	 * @return bool
	 */
	public function Exists() {
		$Current = ftp_pwd( $this->Connection()->Handler() );
		if( ftp_chdir( $this->Connection()->Handler(), $this->Location() ) ) {
			ftp_chdir( $this->Connection()->Handler(), $Current );
			return true;
		} else {
			ftp_chdir( $this->Connection()->Handler(), $Current );
			return false;
		}
	}

	/**
	 * Directory-Empty
	 *
	 * @return bool
	 */
	public function IsEmpty() {
		if( !count( ftp_nlist( $this->Connection()->Handler(), '' ) ) ) {
			return true;
		} return false;
	}

	/**
	 * Directory-Hash
	 *
	 * @return null|string
	 */
	public function Hash() {
		//return sha1( $this->Location() );
	}


/*
	public function DirectoryList( $Recursive = true ) {
		/*
		$this->setCurrent();
		/** @var \MOC\Module\Network\Ftp\Directory[] $Result */
		/*$Result = array();
		if( false !== ( $ItemList = @ftp_rawlist( $this->Connection()->Handler(), $this->RawDataLocation() ) ) ) {
			foreach( (array)$ItemList as $Item ) {
				array_push( $Result, new \MOC\Module\Network\Ftp\Directory() );
				end( $Result )->Connection( $this->Connection() );
				end( $Result )->Determine( $Item );
				end( $Result )->RawDataLocation( end( $Result )->RawDataLocation().$this->RawDataName() );
				if( end( $Result )->RawDataType() != $this::RAW_DATA_TYPE_DIRECTORY ) {
					array_pop( $Result );
				} else if( $Recursive ) {
					//var_dump( end( $Result )->DirectoryList( $Recursive ) );
				}
			}
		}
		return $Result;
		*/
/*	}

	private function setCurrent() {
		// Walk to Root
		while( $this->getCurrent() != '/' ) {
			@ftp_cdup( $this->Connection()->Handler() );
		}
		// Walk to THIS Directory
		@ftp_chdir( $this->Connection()->Handler(), $this->RawDataLocation() );
	}
	private function getCurrent() {
		return @ftp_pwd( $this->Connection()->Handler() );
	}


	/*

	public function Name() {
		return @ftp_pwd( $this->Connection()->Handler() );
	}
	public function Change( $Name ) {
		return @ftp_chdir( $this->Connection()->Handler(), $Name );
	}
	public function Create( $Name ) {
		return @ftp_mkdir( $this->Connection()->Handler(), $Name );
	}
	public function Remove() {
		return @ftp_rmdir( $this->Connection()->Handler(), $this->Name() );
	}
	public function Rename( $Name ) {
		return @ftp_rename( $this->Connection()->Handler(), $this->Name(), $Name );
	}
	public function DirectoryList( $Recursive = false ) {
		$ResultList = array();
		if( false !== ( $ItemList = @ftp_rawlist( $this->Connection()->Handler(), $this->Name(), $Recursive ) ) ) {
			var_dump( $ItemList );
			foreach( (array)$ItemList as $Item ) {
				$Data = new \MOC\Module\Network\Ftp\RawData();
				$Data->ListItem( $Item );
				if( $Data->Type() == $Data::RAW_DATA_TYPE_DIRECTORY ) {
					array_push( $ResultList, new \MOC\Module\Network\Ftp\Directory() );
					end( $ResultList )->Connection( $this->Connection() )->Change( $Data->Name() );
				}
			}
		}
		return $ResultList;
	}
	public function FileList() {
		return @ftp_rawlist( $this->Connection()->Handler(), $this->Name() );
	}
	*/
}
