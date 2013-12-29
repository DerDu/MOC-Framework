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
 * PKEPackage
 * 29.12.2013 00:52
 */
namespace MOC\Module\Network\Pke;
/**
 *
 */
class PKEPackage extends PKETransport {

	private $Payload = null;
	private $LifeTime = 0;
	private $LifeSpan = 0;

	/**
	 * @param mixed    $Payload
	 * @param null|int $LifeSpan seconds
	 */
	function __construct( $Payload, $LifeSpan = null ) {
		$this->Payload = $this->CompressionEncode( $Payload );
		// Restrict Package to e.g. 24h by default
		$this->LifeSpan = ( $LifeSpan === null ? self::PKE_PACKAGE_LIFESPAN : $LifeSpan );
		$this->LifeTime = ( $LifeSpan === null ? time() + self::PKE_PACKAGE_LIFESPAN : time() + $LifeSpan );
	}

	/**
	 * Dead-Package Detection
	 *
	 * @return bool
	 */
	public function IsValid() {
		return ( $this->LifeTime - time() >= 0 ? true : false );
	}

	public function GetLifeSpan() {
		return $this->LifeSpan;
	}
	public function GetLifeTime() {
		return $this->LifeTime;
	}

	public function GetPayload() {
		return $this->CompressionDecode( $this->Payload );
	}

	public function SetTransportSimplex() {
		$this->SetSimplexTransport();
	}
	public function SetTransportDuplex() {
		$this->SetDuplexTransport();
	}
	public function GetTransport() {
		return $this->PKE_TRANSPORT;
	}

	function __toString() {
		return serialize( $this );
	}
}

