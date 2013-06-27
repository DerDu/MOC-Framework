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
 * Status
 * 30.01.2013 13:44
 */
namespace MOC\Module\Network\ParcelTracker;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Status implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return object
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		// TODO: Implement InterfaceInstance() method.
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


	private $TrackingNumber = '';
	private $TrackingLocation = '';
	private $TrackingTimestamp = false;
	private $TrackingMessage = '';

	/**
	 * @param        $TrackingNumber
	 * @param        $TrackingTimestamp
	 * @param string $TrackingMessage
	 * @param string $TrackingLocation
	 */
	function __construct( $TrackingNumber, $TrackingTimestamp, $TrackingMessage = '', $TrackingLocation = '' ) {
		$this->TrackingNumber = $TrackingNumber;
		$this->TrackingLocation = $TrackingLocation;
		$this->TrackingTimestamp = $TrackingTimestamp;
		$this->TrackingMessage = $TrackingMessage;
	}

	/**
	 * @return string
	 */
	public function TrackingNumber() {
		return $this->TrackingNumber;
	}

	/**
	 * @return string
	 */
	public function TrackingLocation() {
		return $this->TrackingLocation;
	}

	/**
	 * @return bool
	 */
	public function TrackingTimestamp() {
		return $this->TrackingTimestamp;
	}

	/**
	 * @return string
	 */
	public function TrackingMessage() {
		return $this->TrackingMessage;
	}
}
