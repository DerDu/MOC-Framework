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
 * Record
 * 18.02.2013 08:42
 */
namespace MOC\Core\Changelog;
use \MOC\Api;
/**
 *
 */
class Record implements \MOC\Generic\Device\Core {
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
	 * @return Record
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Record();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '18.02.2013 13:18', 'Alpha' )
		;
	}

	private $Timestamp = '';
	private $Method = '';
	private $Message = '';
	private $Location = '';

	/**
	 * @param null $Timestamp
	 *
	 * @return null|string
	 */
	public function Timestamp( $Timestamp = null ) {
		if( null !== $Timestamp ) {
			if( is_int( $Timestamp ) ) {
				$this->Timestamp = $Timestamp;
			} else {
				$this->Timestamp = strtotime( $Timestamp );
			}
		} return $this->Timestamp;
	}

	/**
	 * @param null $__METHOD__
	 *
	 * @return null|string
	 */
	public function Method( $__METHOD__ = null ) {
		if( null !== $__METHOD__ ) {
			$this->Method = $__METHOD__;
		} return $this->Method;
	}

	/**
	 * @param null $Message
	 *
	 * @return null|string
	 */
	public function Message( $Message = null ) {
		if( null !== $Message ) {
			$this->Message = $Message;
		} return $this->Message;
	}

	/**
	 * @param null $Location
	 *
	 * @return null|string
	 */
	public function Location( $Location = null ) {
		if( null !== $Location ) {
			$this->Location = $Location;
		} return $this->Location;
	}
}
