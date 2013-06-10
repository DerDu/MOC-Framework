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
 * VideoPlayer
 * 10.06.2013 12:49
 */
namespace MOC\Plugin\Shared;

use MOC\Plugin\Shared;

/**
 *
 */
class VideoPlayer extends Shared {

	/** @var int $VideoWidth */
	private $VideoWidth = 640;
	/** @var int $VideoHeight */
	private $VideoHeight = 480;
	/** @var string $VideoSource */
	private $VideoSource = '';

	/**
	 * @param null|int $Value
	 *
	 * @return int|VideoPlayer
	 */
	public function VideoWidth( $Value = null ) {
		if( $Value !== null ) {
			$this->VideoWidth = $Value;
			return $this;
		} return $this->VideoWidth;
	}

	/**
	 * @param null|int $Value
	 *
	 * @return int|VideoPlayer
	 */
	public function VideoHeight( $Value = null ) {
		if( $Value !== null ) {
			$this->VideoHeight = $Value;
			return $this;
		} return $this->VideoHeight;
	}

	/**
	 * @param null|string $Url
	 *
	 * @return string|VideoPlayer
	 */
	public function VideoSource( $Url = null ) {
		if( $Url !== null ) {
			$this->VideoSource = $Url;
			return $this;
		} return $this->VideoSource;
	}

}
