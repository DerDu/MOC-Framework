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
 * Checkbox
 * 16.07.2013 10:12
 */
namespace MOC\Module\Html\Element\Input;
use MOC\Api;
/**
 *
 */
class Checkbox extends Input {

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Checkbox
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		$InputCheckbox = new Checkbox();
		$InputCheckbox->Attribute()->SetType( 'checkbox' );
		return $InputCheckbox;
	}

	/**
	 * @param $Value
	 *
	 * @return Checkbox
	 */
	public function SetName( $Value ) {
		return parent::SetName( $Value );
	}

	/**
	 * @param $Value
	 *
	 * @return Checkbox
	 */
	public function SetClass( $Value ) {
		return parent::SetClass( $Value );
	}

	/**
	 * @param $Value
	 *
	 * @return Checkbox
	 */
	public function SetValue( $Value ) {
		return parent::SetValue( $Value );
	}

	public function __toString() {
		return  '<input '
			.( $this->Attribute()->GetId()?'id="'.$this->Attribute()->GetId().'"':'' )
			.( $this->Attribute()->GetType()?'type="'.$this->Attribute()->GetType().'"':'' )
			.( $this->Attribute()->GetName()?'name="'.$this->Attribute()->GetName().'"':'' )
			.( $this->Attribute()->GetClass()?'class="'.$this->Attribute()->GetClass().'"':'' )
			.( $this->Attribute()->GetValue()?'value="'.$this->Attribute()->GetValue().'"':'' )
		.' />';
	}

}
