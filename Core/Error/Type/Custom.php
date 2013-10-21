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
 * Custom
 * 14.10.2013 09:05
 */
namespace MOC\Core\Error\Type;
use MOC\Api;
use MOC\Core\Error\Reporting;
use MOC\Core\Journal;
use MOC\Generic\Common;

/**
 *
 */
class Custom implements Common {
	/** @var null|Custom $Singleton */
	private static $Singleton = null;

	/**
	 * @return Custom|null
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Custom();
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
	 * @param string $Message
	 * @param null $JournalName
	 * @param null $File
	 * @param null $Line
	 * @param bool $Silent
	 */
	public function Trigger( $Message, $JournalName = null, $File = null, $Line = null, $Silent = false ) {
		$this->Journal( trim(strip_tags(str_replace(array('<br />','<br/>','<br>'),"\n",$Message)))."\n\n".'Trigger in '.$File.' at line '.$Line, $JournalName );
		if( Reporting::$Display && $Silent == false ) {
			print str_replace( array(
				'{Message}',
			), array(
				'<pre>'.htmlspecialchars( $Message ).'</pre>'
			), $this->TemplateTrigger() );
		}
	}

	/**
	 * @return string
	 */
	private function TemplateTrigger() {
		return '<div style="color: silver; border: 1px dotted silver; padding: 15px; margin-top: 1px; font-family: monospace;">'.
			'<div style="margin: 5px;">{Message}</div>'.
			'</div>';
	}

	/**
	 * @param string $Content
	 * @param string $JournalName
	 */
	private function Journal( $Content, $JournalName = '' ) {
		Journal::InterfaceInstance()->Write()->Name( __CLASS__.(!empty($JournalName)?'-'.$JournalName:'') )->Content( $Content );
	}
}
