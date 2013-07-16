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
 * Attribute
 * 16.07.2013 11:20
 */
namespace MOC\Module\Html\Element\Input;
/**
 *
 */
class Attribute {

	private $Parent;

	/** @var null|string $Id */
	private $Id = null;
	/** @var null|string $Type */
	private $Type = null;
	/** @var null|string $Name */
	private $Name = null;
	/** @var null|string $Class */
	private $Class = null;
	/** @var null|string $Value */
	private $Value = null;

	/**
	 * @param $Parent
	 */
	function __construct( $Parent ) {
		$this->Parent = $Parent;
	}

	final public function SetId( $Value ) {
		$this->Id = $Value;
		return $this->Parent;
	}
	final public function GetId() {
		if( null === $this->Id ) {
			$this->Id = preg_replace( '![^\w]!is', '-', ucwords( __CLASS__.' '.$this->Type.' '.$this->Name ) );
		}
		return $this->Id;
	}

	final public function SetType( $Value ) {
		$this->Type = $Value;
		return $this->Parent;
	}
	final public function GetType() {
		return $this->Type;
	}

	final public function SetName( $Value ) {
		$this->Name = $Value;
		return $this->Parent;
	}
	final public function GetName() {
		return $this->Name;
	}

	final public function SetClass( $Value ) {
		$this->Class = $Value;
		return $this->Parent;
	}
	final public function GetClass() {
		return $this->Class;
	}

	final public function SetValue( $Value ) {
		$this->Value = $Value;
		return $this->Parent;
	}
	final public function GetValue() {
		return $this->Value;
	}

}
