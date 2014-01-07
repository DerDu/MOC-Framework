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
 * Table
 * 12.12.2013 08:15
 */
namespace MOC\Module\Office\Document\Pdf\Table;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Table extends Adapter implements Module {

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
	 * @return Table
	 */
	public static function InterfaceInstance() {
		return new Table();
	}

	private $Style = null;

	private $GridVertical = array();
	private $GridHorizontal = array();

	private $Data = array();

	private $BeforeFontFamily = '';
	private $BeforeFontSize = 12;

	function __construct() {
		$this->Style = new Style();
		$this->Style()->Box()->Width( $this->GetPageWidthFrame() - $this->GetPositionX() + $this->GetPageMarginLeft() );
		$this->Style()->Box()->Padding()->All( 2 );
		// Before:
		$this->BeforeFontName = $this->GetFontFamily();
		$this->BeforeFontSize = $this->GetFontSize();
	}
	public function Style( Style $Style = null ) {
		if( null !== $Style ) {
			$this->Style = $Style;
		}
		return $this->Style;
	}

	public function DataGrid( $Data = null ) {
		if( null !== $Data ) {
			$this->Data = $Data;
			$this->parseDataToBox();
		}
		return $this->Data;
	}
	public function DataCell( $X, $Y, $Content = null ) {
		if( null !== $Content ) {
			$this->Data[$Y][$X] = $Content;
			$this->parseDataToBox();
		}
		return $this->Data[$Y][$X];
	}

	/**
	 * @param int $X
	 * @param int $Y
	 * @param null|Style $Style
	 *
	 * @return Style
	 */
	public function DataCellStyle( $X, $Y, Style $Style = null ) {
		if( null !== $Style ) {
			$this->Data[$Y][$X]->Style( $Style );
			$this->parseDataToBox();
		}
		return $this->Data[$Y][$X]->Style();
	}

	private function dataWidth() {
		return max( array_map( 'count', $this->Data ) );
	}
	private function dataHeight() {
		return count( $this->Data );
	}

	private function defaultStyle() {
		$Result = new Style();

		$Result->Box()->Width( $this->Style()->Box()->Width() );

		$Result->Box()->Padding()->Top( $this->Style()->Box()->Padding()->Top() );
		$Result->Box()->Padding()->Right( $this->Style()->Box()->Padding()->Right() );
		$Result->Box()->Padding()->Bottom( $this->Style()->Box()->Padding()->Bottom() );
		$Result->Box()->Padding()->Left( $this->Style()->Box()->Padding()->Left() );

		$Result->Box()->Border()->Top()->Size( $this->Style()->Box()->Border()->Top()->Size() );
		$Result->Box()->Border()->Right()->Size( $this->Style()->Box()->Border()->Right()->Size() );
		$Result->Box()->Border()->Bottom()->Size( $this->Style()->Box()->Border()->Bottom()->Size() );
		$Result->Box()->Border()->Left()->Size( $this->Style()->Box()->Border()->Left()->Size() );

		$Result->Box()->Border()->Top()->Color( $this->Style()->Box()->Border()->Top()->Color() );
		$Result->Box()->Border()->Right()->Color( $this->Style()->Box()->Border()->Right()->Color() );
		$Result->Box()->Border()->Bottom()->Color( $this->Style()->Box()->Border()->Bottom()->Color() );
		$Result->Box()->Border()->Left()->Color( $this->Style()->Box()->Border()->Left()->Color() );

		$Result->Text()->Align()->Horizontal( $this->Style()->Text()->Align()->Horizontal() );
		$Result->Text()->Align()->Vertical( $this->Style()->Text()->Align()->Vertical() );

		$Result->Text()->Font()->FontFamily( $this->Style()->Text()->Font()->FontFamily() );
		$Result->Text()->Font()->FontSize( $this->Style()->Text()->Font()->FontSize() );

		return $Result;
	}

	public function parseDataToBox() {
		foreach( (array)$this->Data as $Y => $Row ) {
			foreach( (array)$Row as $X => $Content ) {
				if( ! $Content instanceof Box ) {
					// Create new Text
					$Text = new Text( $Content );
					// Use Table Style as Default
					$Style = $this->defaultStyle();
					// Set AutoWidth for Box
					$Style->Box()->Width( $this->Style()->Box()->Width() / $this->dataWidth() );
					// Create new Box
					$Box = new Box( $Text, $Style );
					// Replace
					$this->Data[$Y][$X] = $Box;
				}
			}
		}
	}
	public function parseDataToGrid() {
		foreach( (array)$this->Data as $Y => $Row ) {
			/** @var Box $Box */
			foreach( (array)$Row as $X => $Box ) {
				$this->SetFontFamily( $Box->Style()->Text()->Font()->FontFamily() );
				$this->SetFontSize( $Box->Style()->Text()->Font()->FontSize() );
				if( !isset( $this->GridVertical[$Y] ) || $Box->Height() > $this->GridVertical[$Y] ) {
					$this->GridVertical[$Y] = $Box->Height();
				}
				if( !isset( $this->GridHorizontal[$X] ) || $Box->Style()->Box()->Width() > $this->GridHorizontal[$X] ) {
					$this->GridHorizontal[$X] = $Box->Style()->Box()->Width();
				}
			}
		}
	}

	public function Draw() {

		$this->parseDataToGrid();

		$RunX = $TabX = $this->GetPositionX();
		$RunY = $this->GetPositionY();

		foreach( (array)$this->Data as $IndexY => $Row ) {

			$this->SetPosition( $RunX, $RunY );

			// Page-Break ?
			if( $this->GetPageSpaceBottom() < $this->GridVertical[$IndexY] ) {
				$this->CreatePage();
				$this->SetPosition( $TabX, $this->GetPageMarginTop() );
				$RunX = $this->GetPositionX();
				$RunY = $this->GetPositionY();
			}

			/** @var Box $Box */
			foreach( (array)$Row as $IndexX => $Box ) {

				$this->DrawRectangle( $RunX, $RunY, $this->GridHorizontal[$IndexX], $this->GridVertical[$IndexY] );

				$LineIndex = 0;
				$LineHeight = $RunY;
				while( false != ( $Line = $Box->Text( $LineIndex++ ) ) ) {

					$this->SetFontFamily( $Box->Style()->Text()->Font()->FontFamily() );
					$this->SetFontSize( $Box->Style()->Text()->Font()->FontSize() );

					/**
					 * Issue: Text-Position does not respect baseline
					 * Fix: Box-Height / 4 ... magically works..
					 */
					$BaseLine = ( $Line->GetHeight() / 4 );

					$DrawX = $RunX;
					switch( $Box->Style()->Text()->Align()->Horizontal() ) {
						case $Box::TEXT_ALIGN_LEFT: {
							// Default
							$DrawX += $Box->Style()->Box()->Padding()->Left();
							break;
						}
						case $Box::TEXT_ALIGN_CENTER: {
							$DrawX += ( $Box->Style()->Box()->Width() - $Line->GetWidth() - $Box->Style()->Box()->Padding()->Right() ) / 2;
							break;
						}
						case $Box::TEXT_ALIGN_RIGHT: {
							$DrawX += ( $Box->Style()->Box()->Width() - $Line->GetWidth() - $Box->Style()->Box()->Padding()->Right() );
							break;
						}
					}

					$DrawY = ( $LineHeight += $Line->GetHeight() ) - $BaseLine;
					switch( $Box->Style()->Text()->Align()->Vertical() ) {
						case $Box::TEXT_ALIGN_TOP: {
							// Default
							$DrawY += $Box->Style()->Box()->Padding()->Top();
							break;
						}
						case $Box::TEXT_ALIGN_MIDDLE: {
							$DrawY += ( $this->GridVertical[$IndexY] - $Box->Height() + $Box->Style()->Box()->Padding()->Top() + $Box->Style()->Box()->Padding()->Bottom() ) / 2;
							break;
						}
						case $Box::TEXT_ALIGN_BOTTOM: {
							$DrawY += ( $this->GridVertical[$IndexY] - $Box->Height() );
							break;
						}
					}

					$this->DrawText( $DrawX, $DrawY, $Line->GetContent() );
				}


				$RunX += $this->GridHorizontal[$IndexX];
			}

			$RunX = $this->GetPositionX();
			$RunY += $this->GridVertical[$IndexY];
		}

		// After:
		$this->SetPosition( $RunX, $RunY );
		$this->SetFontFamily( $this->BeforeFontName );
		$this->SetFontSize( $this->BeforeFontSize );
	}

/*
	private function ParseData() {
		foreach( (array)$this->Data as $Y => $Row ) {
			foreach( (array)$Row as $X => $Content ) {
				if( ! isset( $this->MatrixStyle[$Y] ) || !isset( $this->MatrixStyle[$Y][$X] ) ) {
					$Style = new Style();
					$Style->Box()->Width( $this->Style->Box()->Width() / count( current($this->Data) ) );
				} else {
					$Style = $this->MatrixStyle[$Y][$X];
				}
				$this->Cell( $X, $Y, new Text( $Content ), $Style );
			}
		}
	}
*/
/*
	public function Cell( $X, $Y, Text $Text, Style $Style ) {
		if( !isset( $this->Matrix[$Y] ) ) {
			$this->Matrix[$Y] = array();
		}
		$this->Matrix[$Y][$X] = new Box( $Text, $Style->Box()->Width() );
	}

	public function Draw( $PositionX, $PositionY ) {
		$this->ParseData();
		$this->Analyze();
		$RunX = $PositionX;
		$RunY = $PositionY;

		$Padding = 0;

		foreach( (array)$this->Matrix as $Y => $Row ) {
			/** @var Box $Box */
/*			foreach( (array)$Row as $X => $Box ) {
				$this->DrawRectangle( $RunX, $RunY, $this->DimensionWidth[$X] + $Padding, $this->DimensionHeight[$Y] + $Padding );
				$Index = 0;
				$LineHeight = $RunY;
				while( false != ( $Line = $Box->Text( $Index++ ) ) ) {
					/**
					 * Issue: Text-Position does not respect baseline
					 * Fix: Box-Height / 4 ... magically works..
					 */
/*					$BaseLine = ( $Line->GetHeight() / 4 );

					// L
					$DrawX = $RunX + ($Padding / 2);
					// T
					$DrawY = ( $LineHeight += $Line->GetHeight() ) - $BaseLine + ( $Padding / 2 );
					// M
					$DrawY += ( $this->DimensionHeight[$Y] - $Box->Height() ) / 2;
					// B
					//$DrawY += ( $this->DimensionHeight[$Y] - $Box->Height() );


					$this->DrawText( $DrawX, $DrawY, $Line->GetContent() );
				}
				$RunX += $this->DimensionWidth[$X] + $Padding;
			}
			$RunX = $PositionX;
			$RunY += $this->DimensionHeight[$Y] + $Padding;
		}
	}

	public function PageBreak() {
		$this->CreatePage();
		$this->SetPosition( 0, 0 );
	}
*/
}
