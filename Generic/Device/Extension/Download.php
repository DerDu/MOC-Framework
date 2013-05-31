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
use MOC\Module\Drive\File;

/**
 *
 */
abstract class Download {

	/** @var string $ProjectPage */
	private $ProjectPage = '';
	/** @var string $ProjectLocation */
	private $ProjectLocation = '';
	/** @var array $ProjectFilter */
	private $ProjectFilter = array();

	/**
	 * Remote Project-Page
	 *
	 * @param $Url
	 */
	final protected function DownloadProjectPage( $Url ) {
		$this->ProjectPage = Api::Module()->Network()->Http()->Get()->Request( $Url );
	}

	/**
	 * Local Project-Path
	 *
	 * @param $Path
	 */
	final protected function DownloadProjectLocation( $Path ) {
		$this->ProjectLocation = $Path;
	}

	/**
	 * RegExp
	 *
	 * @param string $Filter
	 */
	final protected function DownloadProjectFilter( $Filter ) {
		$this->ProjectFilter[] = $Filter;
	}

	/**
	 * @throws \Exception
	 */
	final protected function DownloadProject() {

		foreach( (array)$this->ProjectFilter as $Filter ) {
			if( preg_match( $Filter, $this->ProjectPage, $Match ) ) {
				$this->DownloadProjectPage( $Match[0] );
			} else {
				throw new \Exception( 'Unable to determine download location' );
			}
		}

		$this->ProjectPage = Api::Module()->Drive()->File()->Open(
			Api::Core()->Cache()->Identifier( $this->ProjectPage )->Timeout(3600)->Set('')->Location()
		)->Write( $this->ProjectPage );

		$this->ProjectPage = Api::Module()->Packer()->Zip()->Decoder()->Open( $this->ProjectPage );

		$RootIndex = 0;
		for( $Run = ( count( $this->ProjectPage ) -1 ); $Run > 1; $Run-- ) {
			$DifferenceAt = strspn(
				$this->ProjectPage[$Run]->GetLocation() ^ $this->ProjectPage[$Run -1]->GetLocation(), "\0"
			);
			if( $DifferenceAt < $RootIndex || $RootIndex == 0 ) {
				$RootIndex = $DifferenceAt;
			}
		}

		foreach( $this->ProjectPage as $Object ) {
			if( $Object instanceof File ) {
				$Path = $this->ProjectLocation.DIRECTORY_SEPARATOR.substr( $Object->GetLocation(), $RootIndex );
				Api::Module()->Drive()->Directory()->Open( dirname( $Path ) );
				$Object->CopyTo( $Path );
			}
		}
	}
}
