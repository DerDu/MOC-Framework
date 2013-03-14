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
 * Filter
 * 13.09.2012 23:20
 */
namespace MOC\Module\Image;
use MOC\Api;
/**
 *
 */
class Filter implements \MOC\Generic\Device\Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Module\Image\Filter
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Image\Filter();
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

	/**
	 * Get Version
	 *
	 * @static
	 * @return \MOC\Core\Version
	 */
	public static function InterfaceVersion() {
		return Api::Core()->Version();
	}

	/** @var null|\MOC\Module\Image\Resource $Resource */
	private $Resource = null;

	/**
	 * @param \MOC\Module\Image\Resource|Resource $Resource $Resource
	 *
	 * @return Filter
	 */
	public function UseResource( \MOC\Module\Image\Resource $Resource ) {
		$this->Resource = $Resource;
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function Negative() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_NEGATE );
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function GrayScale() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_GRAYSCALE );
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function EdgeDetect() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_EDGEDETECT );
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function Emboss() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_EMBOSS );
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function GaussianBlur() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_GAUSSIAN_BLUR );
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function SelectiveBlur() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_SELECTIVE_BLUR );
		return $this;
	}

	/**
	 * @return Filter
	 */
	public function MeanRemoval() {
		imagefilter( $this->Resource->Get(), IMG_FILTER_MEAN_REMOVAL );
		return $this;
	}

	/**
	 * @param $Level
	 *
	 * @return Filter
	 */public function Brightness( $Level ) {
		imagefilter( $this->Resource->Get(), IMG_FILTER_BRIGHTNESS, $Level );
		return $this;
	}

	/**
	 * @param $Level
	 *
	 * @return Filter
	 */public function Contrast( $Level ) {
		imagefilter( $this->Resource->Get(), IMG_FILTER_CONTRAST, $Level );
		return $this;
	}

	/**
	 * @param $Level
	 *
	 * @return Filter
	 */public function Smooth( $Level ) {
		imagefilter( $this->Resource->Get(), IMG_FILTER_SMOOTH, $Level );
		return $this;
	}
}
