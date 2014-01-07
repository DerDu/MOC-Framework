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
 * Box
 * 12.12.2013 08:14
 */
namespace MOC\Module\Office\Document\Pdf\Table;
/**
 *
 */
class Box extends Adapter {

	private $Style = null;

	private $Text = null;

	function __construct( Text $Text, Style $Style ) {
		$this->Text = $Text;
		$this->Style = $Style;
	}

	public function Style( Style $Style = null ) {
		if( null !== $Style ) {
			$this->Style = $Style;
		}
		return $this->Style;
	}

	public function Valid() {
		$WordList = $this->Text->GetWordList();
		/** @var Word $Word */
		foreach( (array)$WordList as $Word ) {
			if( $Word->GetWidth() > $this->Style()->Box()->Width() ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param $Index
	 *
	 * @return Text|false
	 */
	public function Text( $Index ) {
		$TextList = $this->TextList();
		return (isset($TextList[$Index])?$TextList[$Index]:false);
	}

	public function Height() {
		return ( $this->GetStringHeight() * count( $this->TextList() ) )
			+ ( $this->Style()->Box()->Padding()->Top() + $this->Style()->Box()->Padding()->Bottom() );
	}

	private function TextList() {
		$WordList = $this->Text->GetWordList();
		$TextWidth = 0;
		$TextList = array();
		$TextContent = '';
		/** @var Word $Word */
		foreach( (array)$WordList as $Word ) {
			// Fit into Box
			if( ( $TextWidth += $Word->GetWidth() )
				<= (
					$this->Style()->Box()->Width()
					- ( $this->Style()->Box()->Padding()->Left()
						+ $this->Style()->Box()->Padding()->Right()
					)
			)) {
				$TextContent .= $Word->GetContent();
			} else {
				$TextList[] = new Text( $TextContent );
				$TextContent = $Word->GetContent();
				$TextWidth = $Word->GetWidth();
			}
		}
		$TextList[] = new Text( $TextContent );
		return $TextList;
	}
}
