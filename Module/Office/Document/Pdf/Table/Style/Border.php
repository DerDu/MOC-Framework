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
 * Border
 * 13.12.2013 12:32
 */
namespace MOC\Module\Office\Document\Pdf\Table\Style;
/**
 *
 */
class Border {

	private $Top = null;
	private $Right = null;
	private $Bottom = null;
	private $Left = null;

	function __construct() {
		$this->Top = new Border\Top();
		$this->Right = new Border\Right();
		$this->Bottom = new Border\Bottom();
		$this->Left = new Border\Left();
	}

	public function Top() {
		return $this->Top;
	}
	public function Right() {
		return $this->Right;
	}
	public function Bottom() {
		return $this->Bottom;
	}
	public function Left() {
		return $this->Left;
	}

	/**
	 * @param float $Size
	 */
	public function AllSize( $Size ) {
		$this->Top()->Size( $Size );
		$this->Right()->Size( $Size );
		$this->Bottom()->Size( $Size );
		$this->Left()->Size( $Size );
	}

	/**
	 * @param string $Color
	 */
	public function AllColor( $Color ) {
		$this->Top()->Color( $Color );
		$this->Right()->Color( $Color );
		$this->Bottom()->Color( $Color );
		$this->Left()->Color( $Color );
	}
}
