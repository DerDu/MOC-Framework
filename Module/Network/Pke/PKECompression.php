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
 * PKECompression
 * 29.12.2013 00:50
 */
namespace MOC\Module\Network\Pke;
/**
 *
 */
abstract class PKECompression {
	/**
	 * PKE-Compression
	 *
	 * Payload compression
	 *
	 * Client & Server MUST provide the same compression method in order to function
	 * Server MUST respond with SAME type of compression as incoming PKE-Package
	 *
	 */
	protected $PKE_COMPRESSION = self::PKE_COMPRESSION_NONE;

	const PKE_COMPRESSION_NONE = 0;
	const PKE_COMPRESSION_GZDEFLATE = 1;
	const PKE_COMPRESSION_GZCOMPRESS = 2;
	const PKE_COMPRESSION_BZCOMPRESS = 3;

	protected function CompressionEncode( $Payload, $Force = null ) {
		// Server Response
		if( null !== $Force ) {
			switch( $Force ) {
				case self::PKE_COMPRESSION_GZDEFLATE: {
					return gzdeflate( $Payload, 9 );
				}
				case self::PKE_COMPRESSION_GZCOMPRESS: {
					return gzcompress( $Payload, 9 );
				}
				case self::PKE_COMPRESSION_BZCOMPRESS: {
					return bzcompress( $Payload, 9 );
				}
				case self::PKE_COMPRESSION_NONE: {
					return $Payload;
				}
				default: {
					return $Payload;
				}
			}
		}
		// Automatic Compression
		$Compressed = $Payload;
		if( function_exists( 'gzdeflate' ) ) {
			$this->PKE_COMPRESSION = self::PKE_COMPRESSION_GZDEFLATE;
			$Compressed = gzdeflate( $Payload, 9 );
		} else if( function_exists( 'gzcompress' ) ) {
			$this->PKE_COMPRESSION = self::PKE_COMPRESSION_GZCOMPRESS;
			$Compressed = gzcompress( $Payload, 9 );
		} else if( function_exists( 'bzcompress' ) ) {
			$this->PKE_COMPRESSION = self::PKE_COMPRESSION_BZCOMPRESS;
			$Compressed = bzcompress( $Payload, 9 );
		}
		if( strlen( $Compressed ) > strlen( $Payload ) ) {
			$this->PKE_COMPRESSION = self::PKE_COMPRESSION_NONE;
			return $Payload;
		}
		return $Compressed;
	}
	protected function CompressionDecode( $Payload ) {
		switch( $this->PKE_COMPRESSION ) {
			case self::PKE_COMPRESSION_GZDEFLATE: {
				return gzinflate( $Payload );
			}
			case self::PKE_COMPRESSION_GZCOMPRESS: {
				return gzuncompress( $Payload );
			}
			case self::PKE_COMPRESSION_BZCOMPRESS: {
				return bzdecompress( $Payload );
			}
			case self::PKE_COMPRESSION_NONE: {
				return $Payload;
			}
			default: {
				return $Payload;
			}
		}
	}
}

