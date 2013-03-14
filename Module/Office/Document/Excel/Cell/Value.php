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
 * Value
 * 25.02.2013 16:06
 */
namespace MOC\Module\Office\Document\Excel\Cell;
use MOC\Api;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;

/**
 *
 */
class Value implements Module {
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
	 * @return Value
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Value();
	}

	/**
	 * @param mixed $Value
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Set( $Value ) {
		// Reset Cell-Format
		$this->getNumberFormat()->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
		$this->getCell()->setValue( $Value );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param mixed $Value
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function String( $Value ) {
		// Set Cell-Format: TEXT
		$this->getNumberFormat()->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_TEXT );
		$this->getCell()->setValueExplicit( $Value, \PHPExcel_Cell_DataType::TYPE_STRING );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param mixed $Value
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Formular( $Value ) {
		// Reset Cell-Format
		$this->getNumberFormat()->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
		$this->getCell()->setValueExplicit( $Value, \PHPExcel_Cell_DataType::TYPE_FORMULA );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param mixed $Value
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Bool( $Value ) {
		// Reset Cell-Format
		$this->getNumberFormat()->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
		$this->getCell()->setValueExplicit( $Value, \PHPExcel_Cell_DataType::TYPE_BOOL );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Null() {
		// Reset Cell-Format
		$this->getNumberFormat()->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
		$this->getCell()->setValueExplicit( null, \PHPExcel_Cell_DataType::TYPE_NULL );
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @param File $Image
	 * @param int                    $Width
	 * @param int                    $Height
	 * @param int                    $OffsetX
	 * @param int                    $OffsetY
	 * @return \MOC\Module\Office\Document\Excel
	 */
	public function Image( File $Image, $Width = 100, $Height = 0, $OffsetX = 0, $OffsetY = 0 ) {
		// Reset Cell-Format
		$this->getNumberFormat()->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_GENERAL );

		$Draw = new \PHPExcel_Worksheet_Drawing();
		$Draw->setPath( $Image->GetLocation() );
		$Draw->setResizeProportional( true );
		if( $Width > 0 ) $Draw->setWidth( $Width );
		if( $Height > 0 ) $Draw->setHeight( $Height );
		if( $OffsetX > 0 ) $Draw->setOffsetX( $OffsetX );
		if( $OffsetY > 0 ) $Draw->setOffsetY( $OffsetY );
		$Draw->setCoordinates(
			Api::Module()->Office()->Document()->Excel()
				->Cell()->Select()->Current()
		);
		$Draw->setWorksheet(
			Api::Extension()->Excel()->Current()
				->getActiveSheet()
		);
		return Api::Module()->Office()->Document()->Excel();
	}

	/**
	 * @return mixed
	 */
	public function Get() {
		return $this->getCell()->getCalculatedValue();
	}

	/**
	 * @return \PHPExcel_Style_NumberFormat
	 */
	private function getNumberFormat() {
		return Api::Extension()->Excel()->Current()
			->getActiveSheet()
			->getStyle(
				Api::Module()->Office()->Document()->Excel()
					->Cell()->Select()->Current()
			)
			->getNumberFormat();
	}

	/**
	 * @return \PHPExcel_Cell
	 */
	private function getCell() {
		return Api::Extension()->Excel()->Current()
			->getActiveSheet()
			->getCell(
				Api::Module()->Office()->Document()->Excel()
					->Cell()->Select()->Current()
			);
	}
}
