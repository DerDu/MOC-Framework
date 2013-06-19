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
 * Size
 * 19.06.2013 09:30
 */
namespace MOC\Module\Office\Document\Excel\Page;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Size implements Module {
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
	 * @return Size
	 */
	public static function InterfaceInstance() {
		return new Size();
	}

	public function Letter() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER );
	}
	public function LetterSmall() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_SMALL );
	}
	public function Tabloid() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_TABLOID );
	}
	public function Ledger() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEDGER );
	}
	public function Legal() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL );
	}
	public function Statement() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_STATEMENT );
	}
	public function Executive() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_EXECUTIVE );
	}
	public function A3() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3 );
	}
	public function A4() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
	}
	public function A4Small() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_SMALL );
	}
	public function A5() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5 );
	}
	public function B4() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_B4 );
	}
	public function B5() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5 );
	}
	public function Folio() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO );
	}
	public function Quarto() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_QUARTO );
	}
	public function Standard1() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_1 );
	}
	public function Standard2() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_2 );
	}
	public function Note() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_NOTE );
	}
	public function No9Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO9_ENVELOPE );
	}
	public function No10Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO10_ENVELOPE );
	}
	public function No11Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO11_ENVELOPE );
	}
	public function No12Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO12_ENVELOPE );
	}
	public function No14Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO14_ENVELOPE );
	}
	public function C() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_C );
	}
	public function D() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_D );
	}
	public function E() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_E );
	}
	public function DlEnvelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_DL_ENVELOPE );
	}
	public function C5Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_C5_ENVELOPE );
	}
	public function C3Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_C3_ENVELOPE );
	}
	public function C4Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_C4_ENVELOPE );
	}
	public function C6Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_C6_ENVELOPE );
	}
	public function C65Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_C65_ENVELOPE );
	}
	public function B4Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_B4_ENVELOPE );
	}
	public function B5Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5_ENVELOPE );
	}
	public function B6Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_B6_ENVELOPE );
	}
	public function ItalyEnvelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_ITALY_ENVELOPE );
	}
	public function MonarchEnvelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_MONARCH_ENVELOPE );
	}
	public function No634Envelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_6_3_4_ENVELOPE );
	}
	public function UsStandardFanfold() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_US_STANDARD_FANFOLD );
	}
	public function GermanStandardFanfold() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_GERMAN_STANDARD_FANFOLD );
	}
	public function GermanLegalFanfold() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_GERMAN_LEGAL_FANFOLD );
	}
	public function IsoB4() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B4 );
	}
	public function JapaneseDoublePostcard() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_JAPANESE_DOUBLE_POSTCARD );
	}
	public function StandardPaper1() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_1 );
	}
	public function StandardPaper2() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_2 );
	}
	public function StandardPaper3() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_3 );
	}
	public function InviteEnvelope() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_INVITE_ENVELOPE );
	}
	public function LetterExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_EXTRA_PAPER );
	}
	public function LegalExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL_EXTRA_PAPER );
	}
	public function LabloidExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_TABLOID_EXTRA_PAPER );
	}
	public function A4ExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_EXTRA_PAPER );
	}
	public function LetterTransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_TRANSVERSE_PAPER );
	}
	public function A4TransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER );
	}
	public function LetterExtraTransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_EXTRA_TRANSVERSE_PAPER );
	}
	public function SuperaSuperaA4Paper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_SUPERA_SUPERA_A4_PAPER );
	}
	public function SuperbSuperbA3Paper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_SUPERB_SUPERB_A3_PAPER );
	}
	public function LetterPlusPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_PLUS_PAPER );
	}
	public function A4PlusPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_PLUS_PAPER );
	}
	public function A5TransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5_TRANSVERSE_PAPER );
	}
	public function JisB5TransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_JIS_B5_TRANSVERSE_PAPER );
	}
	public function A3ExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_PAPER );
	}
	public function A5ExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5_EXTRA_PAPER );
	}
	public function IsoB5ExtraPaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B5_EXTRA_PAPER );
	}
	public function A2Paper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A2_PAPER );
	}
	public function A3TransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_TRANSVERSE_PAPER );
	}
	public function A3ExtraTransversePaper() {
		$this->getActiveSheet()->getPageSetup()->setPaperSize( \PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER );
	}

	/**
	 * @return \PHPExcel_Worksheet
	 */
	private function getActiveSheet() {
		return Api::Extension()->Excel()->Current()->getActiveSheet();
	}
}
