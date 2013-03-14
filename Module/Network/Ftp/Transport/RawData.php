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
 * RawData
 * 16.10.2012 09:02
 */
namespace MOC\Module\Network\Ftp\Transport;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class RawData implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return RawData
	 */
	public static function InterfaceInstance() {
		return new RawData();
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
	 * @param $RawItem
	 */
	public function Determine( $RawItem ) {
		$RawData = preg_split( '![\s]+!', $RawItem );
		if( $RawData[8] != '.' && $RawData[8] != '..' ) {
			switch( $RawData[0][0] ) {
				case self::RAW_DATA_TYPE_FILE: { $this->RawDataType( self::RAW_DATA_TYPE_FILE ); break; }
				case self::RAW_DATA_TYPE_DIRECTORY: { $this->RawDataType( self::RAW_DATA_TYPE_DIRECTORY ); break; }
				case self::RAW_DATA_TYPE_LINK: { $this->RawDataType( self::RAW_DATA_TYPE_LINK ); break; }
				default: { $this->RawDataType( self::RAW_DATA_TYPE_FILE ); break; }
			}
			$this->RawDataChMod( $RawData[0] );
			$this->RawDataHardLink( $RawData[1] );
			$this->RawDataOwner( $RawData[2] );
			$this->RawDataGroup( $RawData[3] );
			$this->RawDataSize( $RawData[4] );
			$this->RawDataMonth( $RawData[5] );
			$this->RawDataDay( $RawData[6] );
			$this->RawDataModified( $RawData[7] );
			$this->RawDataName( $RawData[8] );
		}
	}

	const RAW_DATA_TYPE_FILE = '-';
	const RAW_DATA_TYPE_DIRECTORY = 'd';
	const RAW_DATA_TYPE_LINK = 'l';

	private $RawDataType = self::RAW_DATA_TYPE_DIRECTORY;
	private $RawDataChMod = '';
	private $RawDataHardLink = '';
	private $RawDataOwner = '';
	private $RawDataGroup = '';
	private $RawDataSize = '';
	private $RawDataMonth = '';
	private $RawDataDay = '';
	private $RawDataModified = '';
	private $RawDataName = '';
	private $RawDataLocation = '/';

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */
	public function RawDataType( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataType = $Value;
		} return $this->RawDataType;
	}

	/**
	 * @param null $Value
	 *
	 * @return string
	 */public function RawDataChMod( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataChMod = $this->ConvertToOctal( $Value );
		} return $this->RawDataChMod;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataHardLink( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataHardLink = $Value;
		} return $this->RawDataHardLink;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataOwner( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataOwner = $Value;
		} return $this->RawDataOwner;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataGroup( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataGroup = $Value;
		} return $this->RawDataGroup;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataSize( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataSize = $Value;
		} return $this->RawDataSize;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataMonth( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataMonth = $Value;
		} return $this->RawDataMonth;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataDay( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataDay = $Value;
		} return $this->RawDataDay;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataModified( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataModified = $Value;
		} return $this->RawDataModified;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataName( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataName = $Value;
		} return $this->RawDataName;
	}

	/**
	 * @param null $Value
	 *
	 * @return null|string
	 */public function RawDataLocation( $Value = null ) {
		if( null !== $Value ) {
			$this->RawDataLocation = $Value;
		} return $this->RawDataLocation;
	}

	/**
	 * @param $ChMod
	 *
	 * @return string
	 */
	private function ConvertToOctal( $ChMod ) {
		$Octal = 0;
		// Owner
		if ($ChMod[1]      == 'r') $Octal += 0400;
		if ($ChMod[2]      == 'w') $Octal += 0200;
		if ($ChMod[3]      == 'x') $Octal += 0100;
		else if ($ChMod[3] == 's') $Octal += 04100;
		else if ($ChMod[3] == 'S') $Octal += 04000;
		// Group
		if ($ChMod[4]      == 'r') $Octal += 040;
		if ($ChMod[5]      == 'w') $Octal += 020;
		if ($ChMod[6]      == 'x') $Octal += 010;
		else if ($ChMod[6] == 's') $Octal += 02010;
		else if ($ChMod[6] == 'S') $Octal += 02000;
		// Other
		if ($ChMod[7]      == 'r') $Octal += 04;
		if ($ChMod[8]      == 'w') $Octal += 02;
		if ($ChMod[9]      == 'x') $Octal += 01;
		else if ($ChMod[9] == 't') $Octal += 01001;
		else if ($ChMod[9] == 'T') $Octal += 01000;
		return sprintf( '%04o', $Octal );
	}
}
