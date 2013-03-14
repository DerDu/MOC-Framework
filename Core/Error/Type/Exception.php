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
 * Exception
 * 31.07.2012 17:00
 */
namespace MOC\Core\Error\Type;
use MOC\Api;
use MOC\Core\Error\Reporting;
use MOC\Core\Journal;
use MOC\Generic\Common;

/**
 *
 */
class Exception implements Common {
	/** @var null|Exception $Singleton */
	private static $Singleton = null;

	/**
	 * @return Exception|null
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Exception();
		} return self::$Singleton;
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
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '19.02.2013 10:37', 'Alpha' )
		;
	}

	/**
	 * @param null  $Message
	 * @param null  $File
	 * @param null  $Line
	 * @param array $Trace
	 *
	 * @return void
	 */
	public function Trigger( $Message = null, $File = null, $Line = null, $Trace = array() ) {
		$this->Journal( trim(strip_tags(str_replace(array('<br />','<br/>','<br>'),"\n",$Message)))."\n\n".nl2br($Trace)."\n\n".'Trigger in '.$File.' at line '.$Line );
		if( Reporting::$Display ) {
			die( str_replace( array(
					'{Message}'
				), array(
					nl2br($Message)
				), $this->TemplateTrigger() )
			);
		} else {
			die();
		}
	}

	/**
	 * @param $Code
	 * @param $Message
	 * @param $Trace
	 * @param $File
	 * @param $Line
	 */
	public function Handler( $Code, $Message, $Trace, $File, $Line ) {
		$this->Journal( trim(strip_tags(str_replace(array('<br />','<br/>','<br>'),"\n",$Message)))."\n\n".nl2br($Trace)."\n\n".'Code ['.$Code.'] thrown in '.$File.' at line '.$Line );
		if( Reporting::$Display ) {
			print str_replace( array(
				'{Code}', '{Message}', '{Trace}', '{File}', '{Position}',
			), array(
				$Code, $Message, nl2br($Trace), $File, $Line
			), $this->TemplateHandler() );
		}
	}

	/**
	 * @return string
	 */
	private function TemplateHandler() {
		return '<div style="color: #F00; border: 1px dotted #F00; padding: 15px; margin-top: 1px; font-family: monospace; background-color: #FFEEAA;">'.
			'<div style="margin: 5px; margin-left: 0;">Exception</div>'.
			'<div style="margin: 5px; margin-left: 0; font-weight: bold;">{Message}</div>'.
			'<div style="margin: 5px;">{Trace}</div>'.
			'<div style="margin: 5px; margin-left: 0;">Code {Code} in {File} at line {Position}</div>'.
			'</div>';
	}

	/**
	 * @return string
	 */
	private function TemplateTrigger() {
		return '<div style="color: #F00; border: 1px dotted #F00; padding: 15px; margin-top: 1px; font-family: monospace; background-color: #FFEEAA;">'.
			'<div style="margin: 5px; margin-left: 0; font-weight: bold;">Exception</div>'.
			'<div style="margin: 5px;">{Message}</div>'.
			'<div style="margin: 5px; margin-left: 0;">Expected - Execution has been stopped!</div>'.
			'</div>';
	}

	/**
	 * @param $Content
	 */
	private function Journal( $Content ) {
		Journal::InterfaceInstance()->Write()->Name( __CLASS__ )->Content( $Content );
	}
}
