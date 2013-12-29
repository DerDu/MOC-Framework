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
 * PKEClient
 * 29.12.2013 00:55
 */
namespace MOC\Module\Network\Pke;
use MOC\Api;
use MOC\Module\Encoding\MocPKE\Key;
use MOC\Module\Encoding\MocPKE\KeyPair;
/**
 *
 */
abstract class PKEClient {
	/** @var KeyPair|null $KeyPair */
	private $KeyPair = null;
	/** @var Key $Server */
	private $Server = null;

	function __construct() {
		$this->KeyPair = Api::Module()->Encoding()->MocPKE()->CreateKeyPair();
	}
	public function Key() {
		return $this->KeyPair->PublicKey();
	}
	public function Register( Key $ServerPublicKey ) {
		$this->Server = $ServerPublicKey;
	}

	public function OutBound( PKEPackage $PKEPackage ) {
		$Cache = Api::Core()->Cache()->Group( __CLASS__ )->Identifier( $PKEPackage->GetPayload() )->Extension('pke')->Timeout( $PKEPackage->GetLifeSpan() );
		if( false == ( $File = $Cache->Get() ) ) {
			$Message = new PKEMessage( $PKEPackage, $this->Server, $this->KeyPair->PrivateKey() );
			$Cache->Set( serialize( $Message ) );
			return $Message;
		} else {
			return unserialize( $File->Content() );
		}
	}

	/** @var PKEMessage|null $InBoundMessage */
	protected $InBoundMessage = null;
	/** @var PKEPackage|null $InBoundPackage */
	protected $InBoundPackage = null;

	public function InBound( $PKEServerResponse ) {

		if( $this->IsBase64( $PKEServerResponse ) && $this->IsSerialized( base64_decode( $PKEServerResponse ) ) ) {
			$PKEMessage = unserialize( base64_decode( $PKEServerResponse ) );
		} else {
			return $PKEServerResponse;
		}

		if( Api::Module()->Encoding()->MocPKE()->Check( $PKEMessage->GetMessage(), $this->Server ) ) {
			$this->InBoundMessage = $PKEMessage;
			$this->InBoundPackage = unserialize( Api::Module()->Encoding()->MocPKE()->Decode( $PKEMessage->GetMessage(), $this->KeyPair->PrivateKey() ) );
			if( $this->InBoundPackage->IsValid() ) {
				return $this->InBoundPackage->GetPayload();
			}
		}
		$this->InBoundMessage = null;
		$this->InBoundPackage = null;
		return false;
	}

	abstract function Handler();

	private function IsSerialized( $String ) {
		return ( @unserialize( $String ) !== false || $String == 'b:0;' );
	}
	private function IsBase64( $String ) {
		return ( base64_decode( $String, true ) === false ? false : true );
	}
}
