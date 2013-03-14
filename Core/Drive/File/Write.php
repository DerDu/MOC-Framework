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
 * 31.07.2012 16:49
 */
namespace MOC\Core\Drive\File;
use MOC\Api;
use MOC\Core\Drive;
use MOC\Core\Error;
/**
 * File-Write
 */
class Write extends Property {

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
		return new Write();
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

	const MODE_APPEND = 'a';
	const MODE_WRITE = 'w';
	const MODE_WRITE_BINARY = 'wb';

	/**
	 * File-Copy
	 *
	 * @param string $Location
	 *
	 * @return bool
	 */
	public function Copy( $Location ) {
		if( file_exists( $this->Location() ) ) {
			if( copy( $this->Location(), $Location ) ) {
				return true;
			}
		}
		return false;
	}
	/**
	 * File-Save
	 *
	 * @param int|string $Mode
	 *
	 * @return bool
	 */
	public function Save( $Mode = self::MODE_WRITE_BINARY ) {
		$Mode = $this->WriteMode( $Mode );
		if( is_array( $this->Content ) ) {
			if( !$this->lockWrite( $this->Location(), implode( PHP_EOL, $this->Content ), $Mode ) ) {
				return false;
			}
		} else {
			if( !$this->lockWrite( $this->Location(), $this->Content, $Mode ) ) {
				return false;
			}
		}
		$this->UpdateProperties();
		$this->Changed( false );
		return true;
	}

	/**
	 * File-Save-As
	 *
	 * @param string $Location
	 * @param string $Mode
	 *
	 * @return bool
	 */
	public function SaveAs( $Location, $Mode = self::MODE_WRITE_BINARY ) {
		$Mode = $this->WriteMode( $Mode );
		if( is_array( $this->Content ) ) {
			if( !$this->lockWrite( $Location, implode( PHP_EOL, $this->Content ), $Mode ) ) {
				return false;
			}
		} else {
			if( !$this->lockWrite( $Location, $this->Content, $Mode ) ) {
				return false;
			}
		}
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
		if( file_exists( $this->Location() ) ) {
			if( ($Return = $this->lockRename( $Location )) ) {
				$this->Location( $Location );
				$this->UpdateProperties();
			}
			return $Return;
		}
		return false;
	}

	/**
	 * File-Remove
	 *
	 * @return bool
	 */
	public function Remove() {
		if( file_exists( $this->Location() ) ) {
			return $this->lockRemove();
		}
		return false;
	}

	/**
	 * File-Touch
	 *
	 * @return bool
	 */
	public function Touch() {
		if( strlen( $this->Location() ) > 0 ) {
			fclose( fopen( $this->Location(), 'a' ) );
			$this->UpdateProperties();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param null|string $Location
	 * @param null|string $Content
	 * @param string      $Mode
	 *
	 * @return bool
	 * @throws \Exception
	 */
	private function lockWrite( $Location = null, $Content = null, $Mode = null ) {
		$Mode = $this->WriteMode( $Mode );
		switch( strtoupper( $Mode ) ) {
			case 'A': {
			if( ( $Handler = fopen( $this->Location(), 'a' ) ) !== false	) {
				if( fwrite( $Handler, $Content ) === false ) {
					return false;
				}
				if( fclose( $Handler ) === false ) {
					return false;
				}
				return true;
			}
			return false;
			break;
			}
			default: {

			// OPEN CACHE
			$CacheFile = Drive::InterfaceInstance()->File()->Handle( $this->Cache() );
			if( ( $CacheHandler = fopen( $CacheFile->Location(), $Mode ) ) === false	) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Cache-Access failed!' );
			}
			// LOCK / WRITE TO CACHE
			$CacheTimeout = 15;
			while( flock( $CacheHandler, LOCK_EX | LOCK_NB ) === false && $CacheTimeout > 0 ) {
				usleep( round( rand( 1,1000 )*1000 ) );
				$CacheTimeout--;
			}
			if( ! $CacheTimeout > 0 ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Cache-Lock failed!' );
			}
			if( fwrite( $CacheHandler, $Content ) === false ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Cache-Write failed!' );
			}
			// UNLOCK / CLOSE CACHE
			if( flock( $CacheHandler, LOCK_UN ) === false ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Cache-UnLock failed!' );
			}
			if( fclose( $CacheHandler ) === false ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Cache-Close failed!' );
			}
			$Timeout = 15;
			while( ( $Check = $this->lockRemove() ) === false && $Timeout > 0 ) {
				usleep( round( rand( 1,1000 )*1000 ) );
				$Timeout--;
			}
			if( $Check === false ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'File-UnLink failed!' );
			}

			$Timeout = 15;
			while( ( $Check = $CacheFile->lockRename( $Location ) ) === false && $Timeout > 0 ) {
				usleep( round( rand( 1,1000 )*1000 ) );
				$Timeout--;
			}
			if( $Check === false ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'File-Write failed!' );
			}
			return true;
			}
		}
	}

	/**
	 * @param string $Location
	 * @param int    $Timeout
	 *
	 * @return bool
	 */
	private function lockRename( $Location, $Timeout = 15 ) {
		if( is_file( $this->Location() ) ) {
			if(
				( false !== ( $HandlerA = fopen( $this->Location(), "r" ) ) )
				&& ( false !== ( $HandlerB = fopen( $Location, "w" ) ) )
			){
				$TimeoutA = $TimeoutB = $Timeout;
				while( flock( $HandlerA, LOCK_EX | LOCK_NB ) === false && $TimeoutA > 0 ) {
					usleep( round( rand( 1,1000 )*1000 ) );
					$TimeoutA--;
				}
				if( $TimeoutA > 0 ) {
					while( flock( $HandlerB, LOCK_EX | LOCK_NB ) === false && $TimeoutB > 0 ) {
						usleep( round( rand( 1,1000 )*1000 ) );
						$TimeoutB--;
					}
					if( $TimeoutB > 0 ) {
						flock( $HandlerA, LOCK_UN );
						fclose( $HandlerA );
						flock( $HandlerB, LOCK_UN );
						fclose( $HandlerB );
						return rename( $this->Location(), $Location );
					}
				}
				flock( $HandlerA, LOCK_UN );
				fclose( $HandlerA );
				fclose( $HandlerB );
			}
		}
		return false;
	}

	/**
	 * @param int $Timeout
	 *
	 * @return bool
	 */
	private function lockRemove( $Timeout = 15 ) {
		if( is_file( $this->Location() ) ) {
			if( false !== ( $Handler = fopen( $this->Location(), "w" ) ) ) {
				while( flock( $Handler, LOCK_EX | LOCK_NB ) === false && $Timeout > 0 ) {
					usleep( round( rand( 1,1000 )*1000 ) );
					$Timeout--;
				}
				if( $Timeout > 0 ) {
					flock( $Handler, LOCK_UN );
					fclose( $Handler );
					return unlink( $this->Location() );
				}
				fclose( $Handler );
			}
		} else {
			return true;
		}
		return false;
	}

	/**
	 * File-Write-Cache
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function Cache() {
		if( ( $CacheFile = tempnam( ini_get('upload_tmp_dir'), 'write' ) ) === false ) {
			Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Cache-Access failed!' );
		}
		return $CacheFile;
	}

	/**
	 * File-Write-Mode
	 *
	 * @param int|string $Mode
	 *
	 * @return string
	 */
	private function WriteMode( $Mode ) {
		switch( $Mode ) {
			case 1: { return self::MODE_APPEND; }
			case 2: { return self::MODE_WRITE; }
			case 3: { return self::MODE_WRITE_BINARY; }
			case self::MODE_APPEND: { return self::MODE_APPEND; }
			case self::MODE_WRITE: { return self::MODE_WRITE; }
			case self::MODE_WRITE_BINARY: { return self::MODE_WRITE_BINARY; }
			default: { return self::MODE_WRITE_BINARY; }
		}
	}
}
