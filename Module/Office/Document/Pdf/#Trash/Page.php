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
 * Page
 * 14.09.2012 08:33
 */
namespace MOC\Module\Office\Document\Pdf;
use \MOC\Api;
/**
 *
 */
class Page1 implements \MOC\Generic\Device\Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */
	public static function InterfaceInstance() {
		return new \MOC\Module\Office\Document\Pdf\Page();
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

	/** @var string $Orientation */
	private $Orientation = 'P';
	/** @var string $Size */
	private $Size = 'A4';

	/** @var int $MarginTop */
	private $MarginTop = 10;
	/** @var int $MarginLeft */
	private $MarginLeft = 10;
	/** @var int $MarginRight */
	private $MarginRight = 10;

	const PAGE_ORIENTATION_PORTRAIT = 'P';
	const PAGE_ORIENTATION_LANDSCAPE = 'L';

	/**
	 * @param null $Value
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page|string
	 */
	public function Orientation( $Value = null ) {
		if( null !== $Value ) {
			$this->Orientation = $Value;
			return $this;
		} return $this->Orientation;
	}

	const PAGE_SIZE_A3 = 'A3';
	const PAGE_SIZE_A4 = 'A4';
	const PAGE_SIZE_A5 = 'A5';
	const PAGE_SIZE_LETTER = 'Letter';
	const PAGE_SIZE_LEGAL = 'Legal';

	/**
	 * @param null $Value
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page|string
	 */
	public function Size( $Value = null ) {
		if( null !== $Value ) {
			$this->Size = $Value;
			return $this;
		} return $this->Size;
	}

	/**
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */
	public function Create() {
		Api::Extension()->Pdf()->Current()->SetMargins( $this->MarginLeft, $this->MarginTop, $this->MarginRight );
		Api::Extension()->Pdf()->Current()->AddPage( $this->Orientation(), $this->Size() );
		return $this;
	}

	/**
	 * @param null $Value
	 *
	 * @return int|\MOC\Module\Office\Document\Pdf\Page
	 */
	public function MarginTop( $Value = null ) {
		if( null !== $Value ) {
			$this->MarginTop = $Value;
			return $this;
		} return $this->MarginTop;
	}

	/**
	 * @param null $Value
	 *
	 * @return int|\MOC\Module\Office\Document\Pdf\Page
	 */
	public function MarginRight( $Value = null ) {
		if( null !== $Value ) {
			$this->MarginRight = $Value;
			return $this;
		} return $this->MarginRight;
	}

	/**
	 * @param null $Value
	 *
	 * @return int|\MOC\Module\Office\Document\Pdf\Page
	 */
	public function MarginLeft( $Value = null ) {
		if( null !== $Value ) {
			$this->MarginLeft = $Value;
			return $this;
		} return $this->MarginLeft;
	}

	/**
	 * @param null $Value
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */
	public function PositionX( $Value = null ) {
		if( null !== $Value ) {
			Api::Extension()->Pdf()->Current()->SetX( $Value );
			return $this;
		} return Api::Extension()->Pdf()->Current()->GetX();
	}

	/**
	 * @param null $Value
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */public function PositionY( $Value = null ) {
		if( null !== $Value ) {
			Api::Extension()->Pdf()->Current()->SetY( $Value );
			return $this;
		} return Api::Extension()->Pdf()->Current()->GetY();
	}

	/**
	 * @param $Value
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */
	public function LineFeed( $Value ) {
		Api::Extension()->Pdf()->Current()->Ln( $Value );
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function GetWidth() {
		return Api::Extension()->Pdf()->Current()->CurPageSize[0];
	}

	/**
	 * @return mixed
	 */
	public function GetHeight() {
		return Api::Extension()->Pdf()->Current()->CurPageSize[1];
	}

	/**
	 * @return mixed
	 */
	public function GetX() {
		return Api::Extension()->Pdf()->Current()->GetX();
	}

	/**
	 * @return mixed
	 */
	public function GetY() {
		return Api::Extension()->Pdf()->Current()->GetY();
	}

	/**
	 * @param      $Value
	 * @param bool $Force
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */
	public function SetX( $Value, $Force = false ) {
		if( $Value < $this->MarginLeft() ) {
			if( ! $Force ) {
				$Value = $this->MarginLeft();
			}
		}
		if( $Value > ( $this->GetWidth() - $this->MarginRight() ) ) {
			if( ! $Force ) {
				$Value = ( $this->GetWidth() - $this->MarginRight() );
			}
		}
		Api::Extension()->Pdf()->Current()->SetXY( $Value, $this->GetY() );
		return $this;
	}

	/**
	 * @param      $Value
	 * @param bool $Force
	 *
	 * @return \MOC\Module\Office\Document\Pdf\Page
	 */
	public function SetY( $Value, $Force = false ) {
		if( $Value < $this->MarginTop() ) {
			if( ! $Force ) {
				$Value = $this->MarginTop();
			}
		}
		Api::Extension()->Pdf()->Current()->SetXY( $this->GetX(), $Value );
		return $this;
	}
}
