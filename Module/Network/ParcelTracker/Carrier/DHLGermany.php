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
 * DHLGermany
 * 30.01.2013 10:25
 */
namespace MOC\Module\Network\ParcelTracker\Carrier;
use \MOC\Api;
/**
 *
 */
class DHLGermany extends \MOC\Module\Network\ParcelTracker\Carrier implements \MOC\Generic\Device\Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return object
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Network\ParcelTracker\Carrier\DHLGermany();
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
	 * @param int $TrackingNumber
	 *
	 * @return \MOC\Module\Network\ParcelTracker\Parcel
	 */
	function GetStatus( $TrackingNumber ) {
		$this->SetTarget( 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc='.$TrackingNumber );

		$DOM = $this->GetData();

		// Find Status-Table
		$StatusTableObject = null;
		$StatusTable = $DOM->getElementsByTagName('table');
		$StatusTableLength = $StatusTable->length;
		for( $Run = 0; $Run < $StatusTableLength; $Run++ ) {
			$TableItem = $StatusTable->item( $Run );
			$TableAttributeList = $TableItem->attributes;
			if( ($TableClassString = $TableAttributeList->getNamedItem('class')) ) {
				if( false !== strpos( $TableClassString->nodeValue, 'full eventList' ) ) {
					$StatusTableObject = $TableItem;
					break;
				}
			}
		}

		$Parcel = new \MOC\Module\Network\ParcelTracker\Parcel( $TrackingNumber, $this->GetTarget() );

		if( $StatusTableObject ) {
			// Find Status-Row
			/** @var \DOMDocument $StatusTableObject */
			$StatusRow = $StatusTableObject->getElementsByTagName('tr');
			$StatusRowLength = $StatusTableObject->getElementsByTagName('tr')->length;
			for( $Run = 1; $Run < $StatusRowLength; $Run++ ) {
				/** @var \DOMDocument $Row */
				$Row = $StatusRow->item( $Run );

				/** @var \DOMDocument $StatusRow */
				$TrackingLocation = $this->NodeValue( $Row->getElementsByTagName('td')->item(1)->nodeValue );
				$TrackingTimestamp = strtotime( preg_replace( '![^\d\.\: ]!is', '', $this->NodeValue( $Row->getElementsByTagName('td')->item(0)->nodeValue ) ) );
				$TrackingMessage = $this->NodeValue( $Row->getElementsByTagName('td')->item(2)->nodeValue );

				$Parcel->AddStatus( $TrackingTimestamp, $TrackingMessage, $TrackingLocation );
			}
		}

		return $Parcel;
	}
}
