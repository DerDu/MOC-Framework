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
 * Width
 * 19.06.2013 09:17
 */
namespace MOC\Module\Office\Document\Excel\Cell\Style;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Width implements Module {
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
	 * @return Width
	 */
	public static function InterfaceInstance() {
		return new Width();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Auto() {
		$this->getWidth()->setWidth()->setAutoSize( true );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param float $Value in Pixel
	 *
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Set( $Value ) {
		// FIX: Pixel to Excel-Non-Pixel-Whatever-Value
		$Value = 100 / 705 * $Value;
		$this->getWidth()->setAutoSize( false )->setWidth( $Value );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \PHPExcel_Worksheet_ColumnDimension
	 */
	private function getWidth() {
		return Api::Extension()->Excel()->Current()
			->getActiveSheet()
			->getColumnDimensionByColumn(
				\PHPExcel_Cell::columnIndexFromString(
					preg_replace( '![0-9]+$!is', '', Api::Module()->Office()->Document()->Excel()
					->Cell()->Select()->Current() )
				) -1
			);
	}
}
