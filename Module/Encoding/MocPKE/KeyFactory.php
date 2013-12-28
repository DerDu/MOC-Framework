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
 * KeyFactory
 * 26.10.2012 14:48
 */
namespace MOC\Module\Encoding\MocPKE;
/**
 *
 */
class KeyFactory {

	/**
	 * @param     $n
	 * @param int $k
	 *
	 * @return bool
	 */
	private function IsPrime( $n, $k = 10 ) {
		if ($n == 2) return true;
		if ($n < 2 || $n % 2 == 0) return false;
		$d = $n - 1; $s = 0;
		while ($d % 2 == 0) { $d /= 2; $s++; }
		for ($i = 0; $i < $k; $i++) {
			$a = rand(2, $n-1);
			$x = bcpowmod($a, $d, $n);
			if ($x == 1 || $x == $n-1) continue;
			for ($j = 1; $j < $s; $j++) {
				$x = bcmod(bcmul($x, $x), $n);
				if ($x == 1) return false;
				if ($x == $n-1) continue 2;
			}
			return false;
		}
		return true;
	}

	/**
	 * @param $PublicKey
	 * @param $Phi
	 *
	 * @return mixed
	 */
	private function IsGreatestCommonDivisor( $PublicKey, $Phi ) {
		while( bccomp( $PublicKey, 0 ) != 0 ) {
			$Modulus = bcsub( $Phi, bcmul( $PublicKey, bcdiv( $Phi, $PublicKey, 0 ) ) );
			$Phi = $PublicKey;
			$PublicKey = $Modulus;
		}
		return $Phi;
	}

	/**
	 * @param $Phi
	 *
	 * @return int
	 */
	private function PublicKey( $Phi ) {
		$PublicKey = 3;
		if( bccomp( $this->IsGreatestCommonDivisor( $PublicKey, $Phi ), 1 ) != 0 ) {
			$PublicKey = 5;
			$PhiRange = 2;
			while( bccomp( $this->IsGreatestCommonDivisor( $PublicKey, $Phi ), 1 ) != 0 ) {
				$PublicKey = bcadd( $PublicKey, $PhiRange );
				if( $PhiRange == 2 ) {
					$PhiRange = 4;
				} else {
					$PhiRange = 2;
				}
			}
		}
		return $PublicKey;
	}

	/**
	 * @param $PublicKey
	 * @param $Phi
	 *
	 * @return string
	 */
	private function PrivateKey( $PublicKey, $Phi ) {

		$Phi1 = '1';
		$Phi2 = '0';
		$Phi3 = $Phi;
		$PublicKey1 = '0';
		$PublicKey2 = '1';
		$PublicKey3 = $PublicKey;
		while( bccomp( $PublicKey3, 0 ) != 0 ) {
			$Factor = bcdiv( $Phi3, $PublicKey3, 0 );
			$Phi1Sub = bcsub( $Phi1, bcmul( $Factor, $PublicKey1 ) );
			$Phi2Sub = bcsub( $Phi2, bcmul( $Factor, $PublicKey2 ) );
			$Phi3Sub = bcsub( $Phi3, bcmul( $Factor, $PublicKey3 ) );
			$Phi1 = $PublicKey1;
			$Phi2 = $PublicKey2;
			$Phi3 = $PublicKey3;
			$PublicKey1 = $Phi1Sub;
			$PublicKey2 = $Phi2Sub;
			$PublicKey3 = $Phi3Sub;
		}

		if( bccomp( $Phi2, 0 ) == -1 ) {
			return bcadd( $Phi2, $Phi );
		} else {
			return $Phi2;
		}
	}

	/**
	 * @param int $Secure
	 *
	 * @return bool
	 */
	private function GeneratePrime( $Secure = 10 ) {
		$Start = floor( time() / 3 );
		$Stop = ceil( time() * 3 );
		$List = array();
		for( $Run = $Start; $Run < $Stop; $Run++ ) {
			if( count( $List ) > 30 ) {
				break;
			}
			if( $this->IsPrime( $Run, $Secure ) ) {
				array_push( $List, $Run );
			}
		}
		if( empty( $List ) ) {
			return false;
		} else {
			return $List[array_rand( $List, 1 )];
		}
	}

	/**
	 * @param $PrimeA
	 * @param $PrimeB
	 *
	 * @return KeyPair
	 */
	private function GenerateKeyPair( $PrimeA, $PrimeB ) {
		$Modulo = bcmul( $PrimeA, $PrimeB );
		$Phi = bcmul( bcsub( $PrimeA, 1 ), bcsub( $PrimeB, 1 ) );
		$PublicKey = new Key( $Modulo, $this->PublicKey( $Phi ) );
		$PrivateKey = new Key( $Modulo, $this->PrivateKey( $PublicKey->Key(), $Phi ) );
		return new KeyPair( $PublicKey, $PrivateKey );
	}

	/**
	 * @param int $Secure
	 *
	 * @return KeyPair
	 */
	public function KeyPair( $Secure = 10 ) {
		$PrimeA = $this->GeneratePrime( $Secure );
		while( $PrimeA == ( $PrimeB = $this->GeneratePrime() ) );
		return $this->GenerateKeyPair( $PrimeA, $PrimeB );
	}
}
