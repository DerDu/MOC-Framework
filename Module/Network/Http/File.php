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
 * File
 * 17.10.2013 16:00
 */
namespace MOC\Module\Network\Http;
use MOC\Api;
use MOC\Generic\Device\Module;
/**
 *
 */
class File implements Module {
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
	 * @return File
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new File();
	}

	private $FILE = array();

	function __construct() {
		$this->FILE = $this->ReOrderFILE();
	}

	public function GetRawData() {
		return $this->FILE;
	}

	private function ReOrderFILE( $isFirstChild = true ) {
		$Result = array();
		foreach( $_FILES as $Identifier => $File ) {
			if( $isFirstChild ) {
				$ChildIdentifier = $File['name'];
			} else {
				$ChildIdentifier = $Identifier;
			}
			if( is_array( $ChildIdentifier ) ){
				foreach( array_keys( $ChildIdentifier ) as $Key ) {
					$Result[$Identifier][$Key] = array(
						'name' => $File['name'][$Key],
						'type' => $File['type'][$Key],
						'tmp_name' => $File['tmp_name'][$Key],
						'error' => $File['error'][$Key],
						'size' => $File['size'][$Key]
					);
					$Result[$Identifier] = $this->ReOrderFILE( $Result[$Identifier], false );
				}
			}else{
				$Result[$Identifier] = $File;
			}
		}
		return $Result;
	}
}
