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
 * Style
 * 18.06.2013 13:40
 */
namespace MOC\Module\Office\Document\Excel\Cell\Style\Font;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Style implements Module {

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

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Style
	 */
	public static function InterfaceInstance() {
		return new Style();
	}

	/**
	 * @param bool $Toggle
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Bold( $Toggle = true ) {
		$this->getFont()->setBold( $Toggle );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param bool $Toggle
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Italic( $Toggle = true ) {
		$this->getFont()->setItalic( $Toggle );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param bool $Toggle
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Underline( $Toggle = true ) {
		$this->getFont()->setUnderline( $Toggle );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \PHPExcel_Style_Font
	 */
	private function getFont() {
		return Api::Extension()->Excel()->Current()
			->getActiveSheet()
			->getStyle(
				Api::Module()->Office()->Document()->Excel()
					->Cell()->Select()->Current()
			)
			->getFont();
	}
}
