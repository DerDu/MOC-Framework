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
namespace MOC\Module\Office\Document\Pdf\Text;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Value implements Module {
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
	 * @return Value
	 */
	public static function InterfaceInstance() {
		return new Value();
	}

	/**
	 * @param string $Text
	 *
	 * @return int mm
	 */
	public function GetWidth( $Text ) {
		$Text = explode( "\n", $Text );
		$Length = 0;
		foreach( (array)$Text as $Line ) {
			$Value = Api::Extension()->Pdf()->Current()->GetStringWidth( $Line );
			if( $Value > $Length ) {
				$Length = $Value;
			}
		}
		return $Length;
	}

	/**
	 * @param string $Text
	 * @param int $MaxWidth mm
	 *
	 * @return int mm
	 */
	public function GetHeight( $Text, $MaxWidth ) {
		return ceil( ( Api::Extension()->Pdf()->Current()->FontSizePt / 2.5 ) * $this->GetLineCount( $Text, $MaxWidth ) );
	}

	/**
	 * @param string $Text
	 * @param int $MaxWidth mm
	 *
	 * @return int
	 */
	public function GetLineCount( $Text, $MaxWidth ) {
		$RowCount = substr_count( $Text, "\n" );
		$RowCount += ceil(
			( $this->GetWidth( $Text )
				+ Api::Extension()->Pdf()->Current()->FontSizePt
			) / $MaxWidth
		);
		return $RowCount;
	}

	/**
	 * @param string $Text
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function String( $Text ) {
		Api::Extension()->Pdf()->Current()
			->Cell(
				$this->GetWidth( $Text ), 0, $Text
			);
		return Api::Module()->Office()->Document()->Pdf();
	}

	/**
	 * @param string $Text
	 * @param int    $MaxWidth mm
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function Block( $Text, $MaxWidth ) {
		Api::Extension()->Pdf()->Current()
			->MultiCell(
				$MaxWidth, ( Api::Extension()->Pdf()->Current()->FontSizePt / 2 ),
				$Text, 0, Api::Module()->Office()->Document()->Pdf()->Text()->Align()->Current()
		);
		return Api::Module()->Office()->Document()->Pdf();
	}
}
