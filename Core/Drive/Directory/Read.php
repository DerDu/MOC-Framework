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
 * 31.07.2012 16:48
 */
namespace MOC\Core\Drive\Directory;
use \MOC\Api;
/**
 * Directory-Read
 */
class Read extends Write {

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
	 * @return object
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Read();
	}

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
	 * @param $Name
	 *
	 * @return \MOC\Core\Drive\File
	 */
	public function File( $Name ) {
		return \MOC\Core\Drive::InterfaceInstance()->File()->Handle( $this->Location().DIRECTORY_SEPARATOR.$Name );
	}

	/**
	 * @return \MOC\Core\Drive\File[]
	 */
	public function FileList() {
		if( is_dir( $this->Location() ) ) {
			$List = array();
			if( ($Directory = $this->DirectoryHandler()) ) {
				while ( false !== ( $Item = $Directory->read() ) ) {
					if( $Item != '.' && $Item != '..' ) {
						$Item = $this->UpdateSyntax( $this->Location() . DIRECTORY_SEPARATOR . $Item );
						if( !is_dir( $Item ) ) {
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
	}

	/**
	 * @return \MOC\Core\Drive\File[]
	 */
	public function FileListRecursive() {
		if( is_dir( $this->Location() ) ) {
			$List = array();
			if( ($Directory = $this->DirectoryHandler()) ) {
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
	}

	/**
	 * @return \MOC\Core\Drive\Directory[]
	 */
	public function DirectoryList() {
		if( is_dir( $this->Location() ) ) {
			$List = array();
			if( ($Directory = $this->DirectoryHandler()) ) {
				while ( false !== ( $Item = $Directory->read() ) ) {
					if( $Item != '.' && $Item != '..' ) {
						$Item = $this->UpdateSyntax( $this->Location() . DIRECTORY_SEPARATOR . $Item );
						if( is_dir( $Item ) ) {
							array_push( $List, \MOC\Core\Drive::InterfaceInstance()->Directory()->Handle( $Item ) );
						}
					}
				}
			}
			$Directory->close();
			return $List;
		} else {
			return array();
		}
	}

	/**
	 * @return \MOC\Core\Drive\Directory[]
	 */
	public function DirectoryListRecursive() {
		if( is_dir( $this->Location() ) ) {
			$List = array();
			if( ($Directory = $this->DirectoryHandler()) ) {
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
	}

	/**
	 * @return string
	 */
	public function DirectoryRoot() {
		$this->UpdateDocumentRootOnIIS();
		return $this->UpdateSyntax( $_SERVER['DOCUMENT_ROOT'] );
	}

	/**
	 * @return string
	 */
	public function DirectoryCurrent() {
		return $this->UpdateSyntax( $this->DirectoryRoot().dirname($_SERVER['SCRIPT_NAME']) );
	}

	/**
	 * @param \MOC\Core\Drive\Directory $Target
	 *
	 * @return \MOC\Core\Drive\Directory
	 */
/*	public function DirectoryRelative( \MOC\Core\Drive\Directory $Target ) {
		$Result = \MOC\Core\Drive\Directory::InterfaceInstance();

		$WorkBase = explode( DIRECTORY_SEPARATOR, $this->Location() );
		$WorkTarget = explode( DIRECTORY_SEPARATOR, $Target->Location() );
		$WorkRelative = '';

		$WorkCommon = array_intersect_assoc( $WorkBase, $WorkTarget );
		$WorkGetOut = array_diff_assoc( $WorkBase, $WorkCommon );
		$WorkGetIn = array_diff_assoc( $WorkTarget, $WorkCommon );

		$RelativeBase = implode( DIRECTORY_SEPARATOR, $WorkCommon ).DIRECTORY_SEPARATOR;
		$RelativeTarget = implode( DIRECTORY_SEPARATOR, $WorkGetIn ).DIRECTORY_SEPARATOR;
		$RelativePath = $RelativeBase;
		foreach( (array)$WorkGetOut as $Index => $Directory ) {
			$RelativePath .= $Directory.'/../';
		}
		$RelativePath .= $RelativeTarget;

		$Result->Handle( $RelativePath );
		return $Result;
	}
*/
	/**
	 * @return bool|\Directory
	 */
	private function DirectoryHandler() {
		if( !is_object( $DirectoryHandler = dir( $this->Location() ) ) ) {
			return false;
		}
		return  $DirectoryHandler;
	}

}
