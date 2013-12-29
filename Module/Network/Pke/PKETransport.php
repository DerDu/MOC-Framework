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
 * PKETransport
 * 29.12.2013 00:51
 */
namespace MOC\Module\Network\Pke;
/**
 *
 */
abstract class PKETransport extends PKECompression {
	/**
	 * PKE-Transport
	 */
	protected $PKE_TRANSPORT = self::PKE_TRANSPORT_DUPLEX;

	/**
	 * Client PKE-Request - Server PKE-Response
	 */
	const PKE_TRANSPORT_DUPLEX = 1;
	/**
	 * Client PKE-Request - Server NONE-PKE-Response (Answer not encrypted, just plain payload !!!)
	 */
	const PKE_TRANSPORT_SIMPLEX = 2;

	/**
	 * Default PKE-Package LifeSpan (24h)
	 */
	const PKE_PACKAGE_LIFESPAN = 86400;

	protected function SetSimplexTransport() {
		$this->PKE_TRANSPORT = self::PKE_TRANSPORT_SIMPLEX;
	}
	protected function SetDuplexTransport() {
		$this->PKE_TRANSPORT = self::PKE_TRANSPORT_DUPLEX;
	}
}
