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
 * Worksheet
 * 25.02.2013 16:06
 */
namespace MOC\Module\Office\Document\Excel;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Worksheet implements Module {
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
	 * @return Worksheet
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Worksheet();
	}

	/**
	 * @param string $Name
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Create( $Name ){
		Api::Extension()->Excel()->Current()->createSheet();
		$Max = Api::Extension()->Excel()->Current()->getSheetCount();

		Api::Extension()->Excel()->Current()->getSheet( $Max -1 )->setTitle( $Name );
		$this->Select( $Name );

		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \PHPExcel_Worksheet
	 */
	public function Current() {
		return $this->getActiveSheet();
	}

	/**
	 * @param string $Name
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Rename( $Name ) {
		$this->getActiveSheet()->setTitle( $Name );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param string $Name
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Select( $Name ) {
		Api::Extension()->Excel()->Current()->setActiveSheetIndex(
			Api::Extension()->Excel()->Current()->getIndex(
				Api::Extension()->Excel()->Current()->getSheetByName( $Name )
			)
		);
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Remove() {
		Api::Extension()->Excel()->Current()->removeSheetByIndex(
			Api::Extension()->Excel()->Current()->getIndex( $this->getActiveSheet() )
		);
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return int
	 */
	public function GetDataWidth() {
		return \PHPExcel_Cell::columnIndexFromString(
			$this->getActiveSheet()->getHighestDataColumn()
		);
	}

	/**
	 * @return int
	 */
	public function GetDataHeight() {
		return $this->getActiveSheet()->getHighestDataRow();
	}

	/**
	 * @return \PHPExcel_Worksheet
	 */
	private function getActiveSheet() {
		return Api::Extension()->Excel()->Current()->getActiveSheet();
	}
}
