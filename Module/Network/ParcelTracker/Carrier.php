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
 * Carrier
 * 30.01.2013 10:09
 */
namespace MOC\Module\Network\ParcelTracker;
use \MOC\Api;
/**
 *
 */
abstract class Carrier  implements \MOC\Generic\Package {
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
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	const TRANSPORT_DEFAULT = 1;
	const TRANSPORT_CURL = 2;

	private $DataTarget = 'http://';
	private $DataTransport = self::TRANSPORT_DEFAULT;

	/**
	 * @param int $TrackingNumber
	 *
	 * @return Parcel
	 */
	abstract function GetStatus( $TrackingNumber );

	/**
	 * @param int $TrackingNumber
	 * @param string $Status
	 * @param null|string $Recipient
	 * @param array $TrackingList
	 *
	 * @return Parcel
	 */
	protected function GetParcel( $TrackingNumber, $Status, $Recipient = null, $TrackingList = array() ) {
		return new Parcel( $TrackingNumber, $Status, $Recipient, $TrackingList );
	}

	/**
	 * @param $Target
	 */
	protected function SetTarget( $Target ) {
		$this->DataTarget = $Target;
	}

	/**
	 * @return string
	 */
	protected function GetTarget() {
		return $this->DataTarget;
	}

	/**
	 * @param int $Transport
	 */
	protected function SetTransport( $Transport = self::TRANSPORT_DEFAULT ) {
		$this->DataTransport = $Transport;
	}

	/**
	 * @param bool $RawPayload
	 *
	 * @return \DOMDocument|string
	 */
	protected function GetData( $RawPayload = false ) {
		switch( $this->DataTransport ) {
			case self::TRANSPORT_CURL: {
				$Transport = curl_init();
				curl_setopt( $Transport, CURLOPT_URL, $this->DataTarget );
				curl_setopt( $Transport, CURLOPT_HEADER, false );
				curl_setopt( $Transport, CURLOPT_RETURNTRANSFER, true );
				$Payload = curl_exec( $Transport );
				curl_close( $Transport );
				$Payload = utf8_decode( $Payload );
				break;
			}
			default: {
				$Payload = file_get_contents( $this->DataTarget );
			}
		}

		if( $RawPayload ) {
			return $Payload;
		}

		$DisplayErrors = ini_get('display_errors');
		Api::Core()->Error()->Reporting()->Display( false )->Apply();
		$DOM = new \DOMDocument();
		$DOM->loadHTML( $Payload );
		$DOM->preserveWhiteSpace = false;
		Api::Core()->Error()->Reporting()->Display( $DisplayErrors )->Apply();

		return $DOM;
	}

	/**
	 * @param $Value
	 *
	 * @return mixed
	 */
	protected function NodeValue( $Value ) {
		return preg_replace('/\s+/', ' ', trim( htmlspecialchars_decode( strip_tags( $Value ) ), chr(0xC2).chr(0xA0)."\xA0 " ) );
	}
}
