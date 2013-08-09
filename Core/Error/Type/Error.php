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
 * Error
 * 31.07.2012 17:01
 */
namespace MOC\Core\Error\Type;
use MOC\Api;
use MOC\Core\Error\Reporting;
use MOC\Core\Journal;
use MOC\Generic\Common;

/**
 *
 */
class Error implements Common {
	/** @var null|Error $Singleton */
	private static $Singleton = null;

	/**
	 * @return Error|null
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Error();
		} return self::$Singleton;
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
		return Api::Core()->Changelog();
	}

	/**
	 * @param null $Message
	 * @param null $File
	 * @param null $Line
	 * @param bool $Silent false
	 *
	 * @return void
	 */
	public function Trigger( $Message = null, $File = null, $Line = null, $Silent = false ) {
		$this->Journal( trim(strip_tags(str_replace(array('<br />','<br/>','<br>'),"\n",$Message)))."\n\n".'Trigger in '.$File.' at line '.$Line );
		if( Reporting::$Display && $Silent == false ) {
			print str_replace( array(
				'{Message}',
			), array(
				$Message
			), $this->TemplateTrigger() );
		}
	}

	/**
	 * @param $Code
	 * @param $Message
	 * @param $File
	 * @param $Line
	 *
	 * Debug-Code: 8192, 2048, 8
	 *
	 * @return null
	 */
	public function Handler( $Code, $Message, $File, $Line ) {
		if( !Reporting::$Debug ) {
			if( in_array( $Code, array( 8192, 2048, 8 ) ) ) {
				return null;
			}
		}
		// FIX: Skip ODBC-'No tuples available at this result index'-Error. This is NOT an Error
		if( $Code == 2 && strpos( $Message, 'No tuples available at this result index' ) !== false ) {
			return null;
		}
		$this->Journal( trim(strip_tags(str_replace(array('<br />','<br/>','<br>'),"\n",$Message)))."\n\n".'Code ['.$Code.'] thrown in '.$File.' at line '.$Line );
		if( Reporting::$Display ) {
			print str_replace( array(
				'{Code}', '{Message}', '{File}', '{Line}',
			), array(
				$Code, $Message, $File, $Line
			), $this->TemplateHandler() );
		}
	}

	/**
	 * @return string
	 */
	private function TemplateHandler() {
		return '<div style="color: #F00; border: 1px dotted #F00; padding: 15px; margin-top: 1px; font-family: monospace; background-color: #FFEEAA;">'.
			'<div style="margin: 5px; margin-left: 0;">Runtime-Error</div>'.
			'<div style="margin: 5px; margin-left: 0; font-weight: bold;">{Message}</div>'.
			'<div style="margin: 5px; margin-left: 0;">Code {Code} in {File} at line {Line}</div>'.
			'</div>';
	}

	/**
	 * @return string
	 */
	private function TemplateTrigger() {
		return '<div style="color: #F00; border: 1px dotted #F00; padding: 15px; margin-top: 1px; font-family: monospace; background-color: #FFEEAA;">'.
			'<div style="margin: 5px; margin-left: 0; font-weight: bold;">Error</div>'.
			'<div style="margin: 5px;">{Message}</div>'.
			'<div style="margin: 5px; margin-left: 0;">Expected - Execution has been continued!</div>'.
			'</div>';
	}

	/**
	 * @param $Content
	 */
	private function Journal( $Content ) {
		Journal::InterfaceInstance()->Write()->Name( __CLASS__ )->Content( $Content );
	}
}
