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
 * Read
 * 16.10.2012 10:42
 */
namespace MOC\Module\Network\Ftp\Directory;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Network\Ftp\Directory;
use MOC\Module\Network\Ftp\File;
use MOC\Module\Network\Ftp\Transport\RawData;

/**
 * Directory-Read
 */
class Read extends Write implements Module {

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
	 * @return Read
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Read();
	}

	/**
	 * @param $Name
	 *
	 * @return File
	 */
/*	public function File( $Name ) {
		return \MOC\Core\Drive::InterfaceInstance()->File()->Handle( $this->Location().DIRECTORY_SEPARATOR.$Name );
	}
*/
	/**
	 * @return File[]
	 */
	public function FileList() {
		// Select Current-Directory as Work-Directory
		$this->DirectoryWorking();
		// Get Raw-File-List
		if( false !== ( $FileList = ftp_rawlist( $this->Connection()->Handler(), $this->Location() ) ) ) {
			$List = array();
			$RawData = RawData::InterfaceInstance();
			foreach( (array)$FileList as $File ) {
				$RawData->Determine( $File );
				var_dump( $File );
				if( $RawData->RawDataType() == $RawData::RAW_DATA_TYPE_FILE ) {
					array_push( $List, File::InterfaceInstance() );
					/** @var File $Current */
					$Current = end( $List );
					$Current->Connection( $this->Connection() );
					$Current->Handle( $this->Location().'/'.$RawData->RawDataName() );
					$Current->Size( ftp_size( $this->Connection()->Handler(), $RawData->RawDataName() ) );
					$Current->Time( ftp_mdtm( $this->Connection()->Handler(), $RawData->RawDataName() ) );
				}
			}
			return $List;
		}
		return array();
	}

	/**
	 * @return \MOC\Module\Drive\File[]
	 */
	public function FileListRecursive() {
/*		if( is_dir( $this->Location() ) ) {
			$List = array();
			if( $Directory = $this->DirectoryHandler() ) {
				while ( false !== ( $Item = $Directory->read() ) ) {
					if( $Item != '.' && $Item != '..' ) {
						$Item = $this->UpdateSyntax($this->Location() . DIRECTORY_SEPARATOR . $Item );
						if( is_dir( $Item ) ) {
							$Recursive = \MOC\Core\Drive::InterfaceInstance()->Directory()->Handle( $Item );
							$List = array_merge( $List, $Recursive->FileListRecursive() );
						} else {
							array_push( $List, \MOC\Core\Drive::InterfaceInstance()->File()->Handle( $Item ) );
						}
					}
				}
			}
			$Directory->close();
			return $List;
		} else {
			return array();
		}
*/	}

	/**
	 * @return \MOC\Core\Drive\Directory[]
	 */
	public function DirectoryList() {
		// Select Current-Directory as Work-Directory
		$this->DirectoryWorking();
		// Get Raw-Directory-List
		if( false !== ( $DirectoryList = ftp_rawlist( $this->Connection()->Handler(), $this->Location() ) ) ) {
			$List = array();
			$RawData = RawData::InterfaceInstance();
			foreach( (array)$DirectoryList as $Directory ) {
				$RawData->Determine( $Directory );
				if( $RawData->RawDataType() == $RawData::RAW_DATA_TYPE_DIRECTORY ) {
					array_push( $List, Directory::InterfaceInstance() );
					/** @var Directory $Current */
					$Current = end( $List );
					$Current->Connection( $this->Connection() );
					$Current->Handle( $this->Location().'/'.$RawData->RawDataName() );
				}
			}
			return $List;
		}
		return array();
	}

	/**
	 * @return \MOC\Core\Drive\Directory[]
	 */
	public function DirectoryListRecursive() {
		/*
		if( is_dir( $this->Location() ) ) {
			$List = array();
			if( $Directory = $this->DirectoryHandler() ) {
				while ( false !== ( $Item = $Directory->read() ) ) {
					if( $Item != '.' && $Item != '..' ) {
						$Item = $this->UpdateSyntax( $this->Location() . DIRECTORY_SEPARATOR . $Item );
						if( is_dir( $Item ) ) {
							$Recursive = \MOC\Core\Drive::InterfaceInstance()->Directory()->Handle( $Item );
							array_push( $List, \MOC\Core\Drive::InterfaceInstance()->Directory()->Handle( $Item ) );
							$List = array_merge( $List, $Recursive->DirectoryListRecursive() );
						}
					}
				}
			}
			$Directory->close();
			return $List;
		} else {
			return array();
		}
		*/
	}

	/**
	 * @return string
	 */
	public function DirectoryRoot() {
		return '/';
	}

	/**
	 * @return string
	 */
	public function DirectoryCurrent() {
		return ftp_pwd( $this->Connection()->Handler() );
	}

	/**
	 * @return bool
	 */
	public function DirectoryWorking() {
		return ftp_chdir( $this->Connection()->Handler(), $this->Location() );
	}
}
