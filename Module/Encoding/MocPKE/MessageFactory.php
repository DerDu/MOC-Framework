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
 * MessageFactory
 * 26.10.2012 14:50
 */
namespace MOC\Module\Encoding\MocPKE;
/**
 *
 */
class MessageFactory extends CryptEngine {
	/** @var Message $Message */
	private $Message;
	/** @var SignatureFactory $SignatureFactory */
	private $SignatureFactory;

	/**
	 *
	 */
	function __construct() {
		$this->SignatureFactory = new SignatureFactory();
	}

	private function CreateToken() {
		$this->Message->Token( base64_encode( md5( uniqid( rand() ) ) ) );
	}

	/**
	 * @param Key $PublicKeyRecipient
	 */
	private function EncodeToken( Key $PublicKeyRecipient ) {
		$this->Message->Token( $this->Encode( $this->Message->Token(), $PublicKeyRecipient ) );
	}

	/**
	 * @param Key $PrivateKeyRecipient
	 */
	private function DecodeToken( Key $PrivateKeyRecipient ) {
		$this->Message->Token( $this->Decode( $this->Message->Token(), $PrivateKeyRecipient ) );
	}

	/**
	 * @param Key $PrivateKeySender
	 */
	private function SignToken( Key $PrivateKeySender ) {
		$this->Message->Signature( $this->SignatureFactory->Create( $this->Message->Token(), $PrivateKeySender ) );
	}

	/**
	 * @param $Value
	 */
	private function ArrayWalkOrd( &$Value ) {
		$Value = str_pad( ord( $Value ), 3, '0', STR_PAD_LEFT );
	}

	/**
	 * @param $Value
	 */
	private function ArrayWalkChr( &$Value ) {
		$Value = chr( $Value );
	}

	/**
	 * @param $Value
	 * @param $Key
	 * @param $Dictionary
	 */
	private function ArrayWalkDictionary( &$Value, $Key, $Dictionary ) {
		$Value = $Dictionary[$Value];
	}

	/**
	 * @param $Value
	 * @param $Key
	 * @param $Token
	 */
	private function ArrayWalkToken( &$Value, $Key, $Token ) {
		$Value = str_pad( $Value ^ ord( $Token[$Key] ), 3, '0', STR_PAD_LEFT );
	}

	/**
	 * @param $Message
	 */
	private function PackMessage( &$Message ) {
		array_walk( $Message, array( $this, 'ArrayWalkChr' ) );
	}

	/**
	 * @param $Message
	 *
	 * @return string
	 */
	private function UnpackMessage( $Message ) {
		$Payload = str_split( $Message, 1 );
		array_walk( $Payload, array( $this, 'ArrayWalkOrd' ) );
		return implode( $Payload );
	}

	/**
	 * @param     $Message
	 * @param Key $PrivateKeySender
	 * @param Key $PublicKeyRecipient
	 *
	 * @return Message
	 */
	public function Create( $Message, Key $PrivateKeySender, Key $PublicKeyRecipient ) {
		$this->Message = new Message();

		$AsciiList = $CharacterList = array_unique( $Message = str_split( base64_encode( $Message ), 1 ) );

		array_walk( $AsciiList, array( $this, 'ArrayWalkOrd' ) );
		$Dictionary = array_combine( $CharacterList, $AsciiList );
		array_walk( $Message, array( $this, 'ArrayWalkDictionary' ), $Dictionary );

		$this->CreateToken();
		$Token = $this->Message->Token();
		while( strlen( $Token ) < count( $Message ) ) { $Token .= $Token; }
		array_walk( $Message, array( $this, 'ArrayWalkToken' ), $Token );

		$this->PackMessage( $Message );

		$this->Message->Payload( implode( $Message ) );
		$this->EncodeToken( $PublicKeyRecipient );
		$this->SignToken( $PrivateKeySender );

		return $this->Message;
	}

	/**
	 * @param Message $Message
	 * @param Key     $PublicKeySender
	 *
	 * @return bool
	 */public function Check( Message $Message, Key $PublicKeySender ) {
		return $this->SignatureFactory->Check( $Message->Token(), $Message->Signature(), $PublicKeySender );
	}

	/**
	 * @param Message $Message
	 * @param Key     $PrivateKeyRecipient
	 *
	 * @return Message|string
	 */public function Open( Message $Message, Key $PrivateKeyRecipient ) {
		$this->Message = $Message;

		$this->DecodeToken( $PrivateKeyRecipient );

		$Payload = $this->UnpackMessage( $this->Message->Payload() );

		$Payload = str_split( $Payload, 3 );
		$Token = $this->Message->Token();
		while( strlen( $Token ) < count( $Payload ) ) { $Token .= $Token; }
		array_walk( $Payload, array( $this, 'ArrayWalkToken' ), $Token );
		array_walk( $Payload, array( $this, 'ArrayWalkChr' ) );

		return base64_decode( implode( $Payload ) );
	}

	/**
	 * @param $Message
	 *
	 * @return mixed|string
	 */public function Wrapper( $Message ) {
		if( gettype( $Message ) == 'object' ) {
			return base64_encode( serialize( $Message ) );
		} else {
			new Message();
			return unserialize( base64_decode( $Message ) );
		}
	}
}
