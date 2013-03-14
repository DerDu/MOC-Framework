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
 * TOFGermany
 * 30.01.2013 10:25
 */
namespace MOC\Module\Network\ParcelTracker\Carrier;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Network\ParcelTracker\Carrier;
use MOC\Module\Network\ParcelTracker\Parcel;

/**
 *
 */
class TOFGermany extends Carrier implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return object
	 */
	public static function InterfaceInstance() {
		return new TOFGermany();
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
	 * @return Parcel
	 */
	function GetStatus( $TrackingNumber ) {

		$this->SetTarget( 'http://track.tof.de/trace/tracking.cgi?barcode='.$TrackingNumber );
		$DOM = $this->GetData();

		// Need to accept: Terms of Service ?
		if( false !== strpos( $DOM->getElementsByTagName('title')->item(0)->nodeValue, 'Nutzungsbedingungen' ) ) {
			// Find Submit-Form
			$SubmitForm = $DOM->getElementsByTagName('form')->item(0);

			$Target = '';
			/** @var \DOMDocument $SubmitForm */
			$FormElementList = $SubmitForm->getElementsByTagName('input');
			$FormElementListLength = $FormElementList->length;
			for( $Run = 0; $Run < $FormElementListLength; $Run++ ) {
				$FormElementAttributeList = $FormElementList->item( $Run )->attributes;
				$FieldName = $FormElementAttributeList->getNamedItem('name')->nodeValue;
				$FieldValue = $FormElementAttributeList->getNamedItem('value')->nodeValue;

				if( $FieldName == 'zustimmungNEIN' ) {
					continue;
				}

				if( !empty( $Target ) ) {
					$Target .= '&';
				}

				$Target .= $FieldName.'='.urlencode( $FieldValue );
			}

			$this->SetTarget( 'http://track.tof.de/trace/tracking.cgi?'.$Target );
			$DOM = $this->GetData();

			// Set Target back to original location
			$this->SetTarget( 'http://track.tof.de/trace/tracking.cgi?barcode='.$TrackingNumber );
		}

		// Find Status-Information
		$TrackingMessage = '';
		$TrackingTimestamp = 0;
		$StatusTableObject = null;
		$StatusTable = $DOM->getElementsByTagName('table');
		$StatusTableLength = $StatusTable->length;
		for( $Run = 0; $Run < $StatusTableLength; $Run++ ) {
			/** @var \DOMElement $TableItem */
			$TableItem = $StatusTable->item( $Run );
			$CellList = $TableItem->getElementsByTagName('td');
			$CellListLength = $CellList->length;

			for( $RunCell = 0; $RunCell < $CellListLength; $RunCell++ ) {
				$CheckStatus = preg_replace( '![^\w]!is', '', $this->NodeValue( $CellList->item( $RunCell )->nodeValue ) );
				if( $CheckStatus == 'Status' ) {
					$TrackingMessage = $this->NodeValue( $CellList->item( $RunCell +1 )->nodeValue );

					$Date = current( preg_grep( '![0-9]{2}\.[0-9]{2}\.[0-9]{4}!', explode( ' ', $TrackingMessage ) ) );
					$Time = current( preg_grep( '![0-9]{2}\:[0-9]{2}!', explode( ' ', $TrackingMessage ) ) );

					$TrackingTimestamp = strtotime( $Date.' '.$Time );
					break 2;
				}
			}
		}

		$Parcel = new Parcel( $TrackingNumber, $this->GetTarget() );

		$Parcel->AddStatus( $TrackingTimestamp, $TrackingMessage, '' );

		return $Parcel;
	}
}
