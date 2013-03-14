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
 * Style
 * 13.03.2013 10:55
 */
namespace MOC\Module\Packer\Yui;
use \MOC\Api;
/**
 *
 */
class Style implements \MOC\Generic\Device\Module {

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Module\Packer\Yui\Style
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Packer\Yui\Style();
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

	public function AddContent( $Content ) {
		$this->Content .= $Content;
		return $this;
	}
	public function AddFile( \MOC\Module\Drive\File $File ) {
		$this->Content .= $File->Read();
		return $this;
	}
	public function SaveAs( \MOC\Module\Drive\File $File ) {
		$Yui = Api::Extension()->YUICompressor()->Create()->Current();
		$Yui->setOption( 'type', 'css' );
		$Yui->setOption( 'linebreak', false );
		$Yui->setOption( 'verbose', false );
		$Yui->setOption( 'nomunge', false );
		$Yui->setOption( 'semi', false );
		$Yui->setOption( 'nooptimize', false );
		$Yui->addString( $this->Content );
		$File->Write( implode( "\n", $Yui->compress() ) );
	}
}
