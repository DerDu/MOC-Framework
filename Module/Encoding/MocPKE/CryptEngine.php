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
 * CryptEngine
 * 26.10.2012 14:49
 */
namespace MOC\Module\Encoding\MocPKE;
/**
 * Based on: http://www.phpclasses.org/package/4121-PHP-Encrypt-and-decrypt-data-with-RSA-public-keys.html
 * @link http://www.phpclasses.org/package/4121-PHP-Encrypt-and-decrypt-data-with-RSA-public-keys.html
 */
class CryptEngine {
	/**
	 * @param     $Digest
	 * @param     $PublicKey
	 * @param     $Modulo
	 * @param int $Size
	 *
	 * @return string
	 */
	private function Encrypt( $Digest, $PublicKey, $Modulo, $Size = 3 ) {
		$Encrypt = array();
		$MaxSize = strlen( $Digest );

		$Packages = ceil( $MaxSize / $Size );
		for( $Run = 0; $Run < $Packages; $Run++ ) {
			$Package = substr( $Digest, $Run * $Size, $Size );
			$Code = 0;
			for( $RunSize = 0; $RunSize < $Size; $RunSize++ ) {
				$Code = bcadd( $Code, bcmul( ord( (isset($Package[$RunSize])?$Package[$RunSize]:'') ), bcpow( 256, $RunSize ) ) );
			}
			$Code = bcpowmod( $Code, $PublicKey, $Modulo );
			array_push( $Encrypt, $Code );
		}

		return implode( ' ', $Encrypt );
	}

	/**
	 * @param $Encrypt
	 * @param $PrivateKey
	 * @param $Modulo
	 *
	 * @return string
	 */
	private function Decrypt( $Encrypt, $PrivateKey, $Modulo ) {
		$Decrypt = '';
		$Encrypt = explode( ' ', $Encrypt );
		$MaxSize = count( $Encrypt );

		for( $RunSize = 0; $RunSize < $MaxSize; $RunSize++ ) {
			$Code = bcpowmod( $Encrypt[$RunSize], $PrivateKey, $Modulo );

			while( bccomp( $Code, 0 ) != 0 ) {
				$Decrypt .= chr( bcmod( $Code, 256 ) );
				$Code = bcdiv( $Code, 256, 0 );
			}
		}
		return $Decrypt;
	}

	/**
	 * @param     $Message
	 * @param Key $PublicKey
	 *
	 * @return string
	 */
	protected function Encode( $Message, Key $PublicKey ) {
		return $this->Encrypt( $Message, $PublicKey->Key(), $PublicKey->Modulo() );
	}

	/**
	 * @param     $Message
	 * @param Key $PrivateKey
	 *
	 * @return string
	 */protected function Decode( $Message, Key $PrivateKey ) {
		return $this->Decrypt( $Message, $PrivateKey->Key(), $PrivateKey->Modulo() );
	}
}
