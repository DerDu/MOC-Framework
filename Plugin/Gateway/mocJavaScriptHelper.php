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
 * mocJavaScriptHelper
 * 12.06.2013 12:55
 */
namespace MOC\Plugin\Gateway;
use MOC\Api;
use MOC\Module\Drive\Directory;
use MOC\Module\Drive\File;

/**
 *
 */
abstract class mocJavaScriptHelper extends \MOC\Plugin\Shared\mocJavaScriptHelper {

	/** @var File[] $FileList */
	private static $FileList = array();

	/**
	 * Register a JavaScript file to be loaded
	 *
	 * @param File $File
	 * @param bool $asRaw
	 *
	 * @return mocJavaScriptHelper
	 */
	final public function Register( File $File, $asRaw = true ) {
		self::$FileList[] = array( $File, $asRaw );
		return $this;
	}

	/**
	 * Create combined/compressed JavaScript file and return an URI-Location
	 *
	 * @param Directory $Directory
	 *
	 * @return string
	 */
	final public function Build( Directory $Directory ) {
		$Hash = $this->HashStack();
		$Build = Api::Module()->Drive()->File()->Open( $Directory->GetLocation().DIRECTORY_SEPARATOR.$Hash.'.js' );
		if( !$Build->Exists() ) {
			$Yui = Api::Module()->Packer()->Yui()->Script();
			foreach( self::$FileList as $Job ) {
				$Yui->AddFile( $Job[0], $Job[1] );
			}
			$Yui->SaveAs( $Build );
		}
		self::$FileList = array();
		return $Build->GetUrl();
	}

	/**
	 * Create JavaScript Library-Hash
	 *
	 * @return string
	 */
	private function HashStack() {
		$HashStack = array();
		/** @var File[] $File */
		foreach( self::$FileList as $File ) {
			array_push( $HashStack, $File[0]->GetHash() );
		}
		return sha1( implode( '-', $HashStack ) );
	}
}
