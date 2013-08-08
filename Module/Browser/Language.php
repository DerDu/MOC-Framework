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
 * Language
 * 08.08.2013 12:37
 */
namespace MOC\Module\Browser;
use MOC\Api;
use MOC\Generic\Device\Module;
/**
 *
 */
class Language implements Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Language
	 */
	public static function InterfaceInstance() {
		return new Language();
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

	private $LanguageList = array();
	private $LanguageCount = 0;

	/**
	 * @return int
	 */
	public function GetOptionCount() {
		$this->DetectLanguages();
		return $this->LanguageCount;
	}

	/**
	 * @param int $Priority Option
	 *
	 * @return string
	 */
	public function GetLanguage( $Priority = 0 ) {
		$this->DetectLanguages();
		return strtoupper( $this->LanguageList[$Priority][0] );
	}

	/**
	 * @param int $Priority Option
	 *
	 * @return string
	 */
	public function GetRegion( $Priority = 0 ) {
		$this->DetectLanguages();
		return strtoupper( $this->LanguageList[$Priority][1] );
	}

	/**
	 *
	 */
	private function DetectLanguages() {
		$AcceptedLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$AcceptedLanguageList = explode(',', $AcceptedLanguage );
		$AcceptedLanguageListLength = count( $AcceptedLanguageList );
		for( $Run = 0; $Run < $AcceptedLanguageListLength; $Run++ ) {
			$Qualifier = strchr( $AcceptedLanguageList[$Run], ";" );
			if( $Qualifier === false ) {
				$this->LanguageList += array( $AcceptedLanguageList[$Run] => 100 );
			} else {
				$Qualifier = explode( ';', $AcceptedLanguageList[$Run] );
				$Local = $Qualifier[0];
				$Qualifier = explode( '=', $Qualifier[1] );
				$this->LanguageList += array( $Local => ( $Qualifier[1] * 100 ) );
			}
			$this->LanguageCount++;
		}
		arsort( $this->LanguageList );
		$LanguagePriority = array();
		foreach( $this->LanguageList as $Language => $Qualifier ) {
			$LanguageDefinition = explode( '-', $Language );
			$LanguagePriority[] = array( $LanguageDefinition[0], ( isset( $LanguageDefinition[1] ) ? $LanguageDefinition[1] : false ) );
		}
		$this->LanguageList = $LanguagePriority;
	}
}
