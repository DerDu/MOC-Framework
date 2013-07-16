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
 * Message
 * 02.07.2013 13:59
 */
namespace MOC\Module\Network\Soap\Api;
use MOC\Api;
use MOC\Core\Xml\Node;
use MOC\Generic\Device\Module;

/**
 *
 */
class Message implements Module {
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
	 * @return Message
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Message();
	}

	/** @var string $Name */
	private $Name = '';
	/** @var string $Action */
	private $Action = '';
	/** @var Part[] $Part */
	private $Part = '';

	/**
	 * @param Node $Message
	 * @param Node $Wsdl
	 *
	 * @return Message
	 */
	public function Definition( Node $Message, Node $Wsdl ) {

		$this->Name = preg_replace( '!^([^:]*:)!is', '', $Message->GetAttribute('message') );
		$this->Action = $Message->GetAttribute('Action');

		$MessageIndex = 0;
		while( false != ( $Node = $Wsdl->GetChild('!:?message!is', null, $MessageIndex++, false, true ) ) ) {

			if( $this->Name == $Node->GetAttribute('name') ) {
				$PartIndex = 0;
				while( false != ( $Part = $Node->GetChild('!:?part!is', null, $PartIndex++, false, true ) ) ) {
					$Instance = new Part();
					$Instance->Definition( $Part, $Wsdl );
					$this->Part[] = $Instance;
				}

				$Node->GetParent()->RemoveChild( $Node );
				break;
			}
		}


		return $this;
	}

	/**
	 * @return string
	 */
	public function GetName() {
		return $this->Name;
	}

	/**
	 * @return string
	 */
	public function GetAction() {
		return $this->Action;
	}
}
