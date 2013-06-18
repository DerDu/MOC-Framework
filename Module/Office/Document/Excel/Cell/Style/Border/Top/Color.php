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
 * Color
 * 18.02.2013 13:55
 */
namespace MOC\Module\Office\Document\Excel\Cell\Style\Border\Top;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Color implements Module {
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
	 * @return Color
	 */
	public static function InterfaceInstance() {
		return new Color();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Black() {
		$this->getColor()->setARGB( \PHPExcel_Style_Color::COLOR_BLACK );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Blue() {
		$this->getColor()->setARGB( \PHPExcel_Style_Color::COLOR_BLUE );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Green() {
		$this->getColor()->setARGB( \PHPExcel_Style_Color::COLOR_GREEN );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Red() {
		$this->getColor()->setARGB( \PHPExcel_Style_Color::COLOR_RED );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Yellow() {
		$this->getColor()->setARGB( \PHPExcel_Style_Color::COLOR_YELLOW );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param int $Red
	 * @param int $Green
	 * @param int $Blue
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Custom( $Red = 0, $Green = 0, $Blue = 0 ) {
		$this->getColor()->setRGB( bin2hex( $Red ).bin2hex( $Green ).bin2hex( $Blue ) );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \PHPExcel_Style_Color
	 */
	private function getColor() {
		return Api::Extension()->Excel()->Current()
			->getActiveSheet()
			->getStyle(
				Api::Module()->Office()->Document()->Excel()
					->Cell()->Select()->Current()
			)
			->getBorders()
			->getTop()
			->getColor();
	}
}
