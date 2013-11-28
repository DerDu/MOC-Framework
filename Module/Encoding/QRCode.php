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
 * QRCode
 * 28.11.2013 11:20
 */
namespace MOC\Module\Encoding;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;
use MOC\Module\Encoding\QRCode\BusinessCard;
use MOC\Module\Encoding\QRCode\Email;
use MOC\Module\Encoding\QRCode\Phone;
use MOC\Module\Encoding\QRCode\Skype;

/**
 *
 */
class QRCode implements Module {

	/** @var QRCode $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return QRCode
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new QRCode();
		} return self::$Singleton;
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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

	const ERROR_CORRECTION_LEVEL_LOW = 'L';
	const ERROR_CORRECTION_LEVEL_MEDIUM = 'M';
	const ERROR_CORRECTION_LEVEL_QUARTILE = 'Q';
	const ERROR_CORRECTION_LEVEL_HIGH = 'H';

	/**
	 * @param string $Message
	 * @param File   $File      (PNG)
	 * @param string $ErrorCorrectionLevel
	 * @param int    $PointSize [1..10]
	 * @param int    $PointMargin
	 *
	 * @return \MOC\Module\Drive\File
	 */
	public function EncodeText( $Message, File $File, $ErrorCorrectionLevel = self::ERROR_CORRECTION_LEVEL_LOW, $PointSize = 4, $PointMargin = 2 ) {
		Api::Extension()->QrCode()->Current()->png( $Message, $File->GetLocation(), $ErrorCorrectionLevel, $PointSize, $PointMargin );
		return $File;
	}

	/**
	 * @return Phone
	 */
	public function Phone() {
		return Phone::InterfaceInstance();
	}

	/**
	 * @return Email
	 */
	public function Email() {
		return Email::InterfaceInstance();
	}

	/**
	 * @return Skype
	 */
	public function Skype() {
		return Skype::InterfaceInstance();
	}

	/**
	 * @return BusinessCard
	 */
	public function BusinessCard() {
		return BusinessCard::InterfaceInstance();
	}

}
