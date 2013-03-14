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
 * Script
 * 13.03.2013 10:54
 */
namespace MOC\Module\Packer\Yui;

use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Script implements Module {

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Script
	 */
	public static function InterfaceInstance() {
		return new Script();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/** @var string $Content */
	private $Content = '';

	/**
	 * @param string $Content
	 * @param bool $asRaw
	 *
	 * @return $this
	 */
	public function AddContent( $Content, $asRaw = false ) {
		if( $asRaw ) {
			$this->Content .= $Content;
		} else {
			$this->Content .= $this->Compress( $Content );
		}
		return $this;
	}

	/**
	 * @param File $File
	 * @param bool $asRaw
	 *
	 * @return $this
	 */
	public function AddFile( File $File, $asRaw = false ) {
		if( $asRaw ) {
			$this->Content .= $File->Read();
		} else {
			$this->Content .= $this->Compress( $File->Read() );
		}
		return $this;
	}

	/**
	 * @param File $File
	 */
	public function SaveAs( File $File ) {
		$File->Write( $this->Content );
	}

	/**
	 * @param string $String
	 *
	 * @return string
	 */
	private function Compress( $String ) {
		$Yui = Api::Extension()->YUICompressor()->Create()->Current();
		$Yui->setOption( 'type', 'js' );
		$Yui->setOption( 'linebreak', false );
		$Yui->setOption( 'verbose', false );
		$Yui->setOption( 'nomunge', false );
		$Yui->setOption( 'semi', false );
		$Yui->setOption( 'nooptimize', false );
		$Yui->addString( $String );
		return implode( "\n", $Yui->compress() );
	}
}
