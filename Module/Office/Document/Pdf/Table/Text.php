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
 * Text
 * 12.12.2013 08:13
 */
namespace MOC\Module\Office\Document\Pdf\Table;
use MOC\Api;

/**
 *
 */
class Text extends Adapter {

	private $Content = '';

	private $WordSeparator = ' ';
	private $WordList = array();

	function __construct( $Content ) {
		$this->Content = Api::Module()->Encoding()->Text()->ToLatin1( trim( $Content ) );
		// Parse Text to Words
		$WordList = explode( $this->WordSeparator, $this->Content );
		foreach( (array)$WordList as $Index => $Word ) {
			if( $Index != 0 ) {
				array_push( $this->WordList, new Word( $this->WordSeparator ) );
			}
			array_push( $this->WordList, new Word( $Word ) );
		}
	}

	public function GetWordList() {
		return $this->WordList;
	}
	public function GetContent() {
		return $this->Content;
	}
	public function GetWidth() {
		$Result = 0;
		/** @var Word $Word */
		foreach( (array)$this->WordList as $Word ) {
			$Result += $Word->GetWidth();
		}
		return $Result;
	}
	public function GetHeight() {
		return $this->GetStringHeight();
	}
}
