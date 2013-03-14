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
 * Text
 * 14.09.2012 08:30
 */
namespace MOC\Module\Office\Document\Pdf;#
use \MOC\Api;
/**
 *
 */
class Text1 implements \MOC\Generic\Device\Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Module\Office\Document\Pdf\Text
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Office\Document\Pdf\Text();
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
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/** @var null|\MOC\Module\Office\Document\Pdf\Font $Font */
	private $Font = null;

	private $Align = 'L';

	/**
	 * @param Font $Font
	 *
	 * @return Font|Text|null
	 */
	public function Font( \MOC\Module\Office\Document\Pdf\Font $Font = null ) {
		if( null !== $Font ) {
			$this->Font = $Font;
			return $this;
		} return $this->Font;
	}

	const ALIGN_LEFT = 'L';
	const ALIGN_RIGHT = 'R';
	const ALIGN_CENTER = 'C';

	/**
	 * @param null $Value
	 *
	 * @return Text|string
	 */
	public function Align( $Value = null ) {
		if( null !== $Value ) {
			$this->Align = $Value;
			return $this;
		} return $this->Align;
	}

	/**
	 * @param null $Content
	 *
	 * @return Text
	 */
	public function Apply( $Content = null ) {
		$this->Font->Apply();
		if( $Content != null ) {
			Api::Extension()->Pdf()->Current()->Cell(
				$this->GetWidth( $Content ), 0, $Content
			);

		}
		return $this;
	}

	/**
	 * @param $Content
	 *
	 * @return float
	 */
	public function GetWidth( $Content ) {
		$this->Font->Apply();
		return Api::Extension()->Pdf()->Current()->GetStringWidth( $Content );
	}
}
