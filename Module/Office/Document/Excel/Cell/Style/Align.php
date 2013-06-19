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
 * Align
 * 19.06.2013 09:10
 */
namespace MOC\Module\Office\Document\Excel\Cell\Style;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Align implements Module {
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
	 * @return Align
	 */
	public static function InterfaceInstance() {
		return new Align();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function CenterHorizontal() {
		$this->getAlign()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function CenterHorizontalContinuous() {
		$this->getAlign()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function General() {
		$this->getAlign()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_GENERAL );
		return Api::Module()->Office()->Document()->Excel();
	}


	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function JustifyHorizontal() {
		$this->getAlign()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY );
		return Api::Module()->Office()->Document()->Excel();
	}


	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Left() {
		$this->getAlign()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		return Api::Module()->Office()->Document()->Excel();
	}


	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Right() {
		$this->getAlign()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Bottom() {
		$this->getAlign()->setVertical( \PHPExcel_Style_Alignment::VERTICAL_BOTTOM );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function CenterVertical() {
		$this->getAlign()->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function JustifyVertical() {
		$this->getAlign()->setVertical( \PHPExcel_Style_Alignment::VERTICAL_JUSTIFY );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Top() {
		$this->getAlign()->setVertical( \PHPExcel_Style_Alignment::VERTICAL_TOP );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \PHPExcel_Style_Alignment
	 */
	private function getAlign() {
		return Api::Extension()->Excel()->Current()
			->getActiveSheet()
			->getStyle(
				Api::Module()->Office()->Document()->Excel()
					->Cell()->Select()->Current()
			)
			->getAlignment();
	}
}
