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
 * Padding
 * 12.12.2013 09:16
 */
namespace MOC\Module\Office\Document\Pdf\Table\Style;
/**
 *
 */
class Padding extends Constant {

	private $Top = 0;
	private $Right = 0;
	private $Bottom = 0;
	private $Left = 0;

	public function All( $Value = null ) {
		if( null !== $Value ) {
			$this->Top = $Value;
			$this->Right = $Value;
			$this->Bottom = $Value;
			$this->Left = $Value;
		}
		return $this->Top;
	}
	public function Top( $Value = null ) {
		if( null !== $Value ) {
			$this->Top = $Value;
		}
		return $this->Top;
	}
	public function Right( $Value = null ) {
		if( null !== $Value ) {
			$this->Right = $Value;
		}
		return $this->Right;
	}
	public function Bottom( $Value = null ) {
		if( null !== $Value ) {
			$this->Bottom = $Value;
		}
		return $this->Bottom;
	}
	public function Left( $Value = null ) {
		if( null !== $Value ) {
			$this->Left = $Value;
		}
		return $this->Left;
	}

}
