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
 * ParcelTracker
 * 30.01.2013 11:13
 */
namespace MOC\Module\Network;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class ParcelTracker implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return ParcelTracker
	 */
	public static function InterfaceInstance() {
		return new ParcelTracker();
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
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * @return ParcelTracker\Carrier\DHLGermany
	 */
	public function DHLGermany() {
		return ParcelTracker\Carrier\DHLGermany::InterfaceInstance();
	}
	/**
	 * @return ParcelTracker\Carrier\UPSGermany
	 */
	public function UPSGermany() {
		return ParcelTracker\Carrier\UPSGermany::InterfaceInstance();
	}
	/**
	 * @return ParcelTracker\Carrier\DPDGermany
	 */
	public function DPDGermany() {
		return ParcelTracker\Carrier\DPDGermany::InterfaceInstance();
	}
	/**
	 * @return ParcelTracker\Carrier\GLSGermany
	 */
	public function GLSGermany() {
		return ParcelTracker\Carrier\GLSGermany::InterfaceInstance();
	}
	/**
	 * @return ParcelTracker\Carrier\TOFGermany
	 */
	public function TOFGermany() {
		return ParcelTracker\Carrier\TOFGermany::InterfaceInstance();
	}
	/**
	 * @return ParcelTracker\Carrier\HermesGermany
	 */
	public function HermesGermany() {
		return ParcelTracker\Carrier\HermesGermany::InterfaceInstance();
	}
}
