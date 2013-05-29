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
 * Set
 * 27.02.2013 16:06
 */
namespace MOC\Module\Office\Document\Pdf\Page\Position;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Set implements Module {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Set
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Set();
	}

	/**
	 * Free Value on Page-Size
	 *
	 * @param int $Value mm
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function X( $Value ) {
		Api::Extension()->Pdf()->Current()->SetX( $Value );
		return Api::Module()->Office()->Document()->Pdf();
	}

	/**
	 * @param int $Value mm
	 *
	 * @return \MOC\Module\Office\Document\Pdf|void
	 */
	public function Y( $Value ) {
		$X = Api::Extension()->Pdf()->Current()->GetX();
		Api::Extension()->Pdf()->Current()->SetY( $Value );
		$this->X( $X );
		return Api::Module()->Office()->Document()->Pdf();
	}


	/*
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
	 	public function SetY( $Value, $Force = false ) {
	 		if( $Value < $this->MarginTop() ) {
	 			if( ! $Force ) {
	 				$Value = $this->MarginTop();
	 			}
	 		}
	 		Api::Extension()->Pdf()->Current()->SetXY( $this->GetX(), $Value );
	 		return $this;
	 	}
	 */
}
