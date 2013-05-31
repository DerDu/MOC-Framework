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
 * Download
 * 31.05.2013 09:05
 */
namespace MOC\Generic\Device\Extension;
use MOC\Api;
use MOC\Module\Drive\Directory;
use MOC\Module\Drive\File;

/**
 *
 */
abstract class Download {

	/** @var string $DownloadPage */
	private $DownloadPage = '';
	/** @var null|File|File[]|Directory[] $Library */
	private $LibraryContent = null;
	/** @var int $LibraryRoot */
	private $LibraryRoot = 0;
	/** @var string $LibraryLocation */
	private $LibraryLocation = '';

	/**
	 * @param string $DownloadPageUrl
	 * @param string $DownloadLinkFilter
	 * @param string $DownloadTargetPath
	 */
	final public function Download( $DownloadPageUrl, $DownloadLinkFilter = '!"[^"]+\.zip"!is', $DownloadTargetPath ) {
		$this->_getDownloadPage( $DownloadPageUrl );
		$this->_getDownloadLibrary( $DownloadLinkFilter );
		$this->_getLibraryContent();
		$this->_getLibraryRoot();
		$this->LibraryLocation = $DownloadTargetPath;
		$this->_installLibrary();
	}

	/**
	 * @param $Url
	 */
	private function _getDownloadPage( $Url ) {
		$this->DownloadPage = Api::Module()->Network()->Http()->Get()->Request( $Url );
	}

	/**
	 * @param $RegExFilter
	 */
	private function _getDownloadLibrary( $RegExFilter ) {
		preg_match( $RegExFilter, $this->DownloadPage, $Match );
		$LibraryContent = Api::Module()->Network()->Http()->Get()->Request( $Match[0] );
		$this->LibraryContent = Api::Module()->Drive()->File()->Open(
			Api::Core()->Cache()->Identifier( $LibraryContent )->Timeout(3600)->Set('')->Location()
		)->Write( $LibraryContent );
	}

	private function _getLibraryContent() {
		$this->LibraryContent = Api::Module()->Packer()->Zip()->Decoder()->Open( $this->LibraryContent );
	}

	private function _getLibraryRoot() {
		$LibrarySize = count( $this->LibraryContent ) -1;
		for( $Run = $LibrarySize; $Run > 1; $Run-- ) {
			$DifferenceAt = strspn( $this->LibraryContent[$Run]->GetLocation() ^ $this->LibraryContent[$Run -1]->GetLocation(), "\0" );
			if( $DifferenceAt < $this->LibraryRoot || $this->LibraryRoot == 0 ) {
				$this->LibraryRoot = $DifferenceAt;
			}
		}
	}

	private function _installLibrary() {
		foreach( $this->LibraryContent as $Object ) {
			if( $Object instanceof File ) {
				$Path = $this->LibraryLocation.DIRECTORY_SEPARATOR.substr( $Object->GetLocation(), $this->LibraryRoot );
				Api::Module()->Drive()->Directory()->Open( dirname( $Path ) );
				$Object->CopyTo( $Path );
			}
		}
	}

}
