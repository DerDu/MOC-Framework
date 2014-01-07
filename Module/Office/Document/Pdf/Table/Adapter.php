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
 * Adapter
 * 12.12.2013 08:13
 */
namespace MOC\Module\Office\Document\Pdf\Table;
use MOC\Api;

/**
 *
 */
class Adapter extends Style\Constant {

	public function CreatePage() {
		$this->getPdf()->AddPage();
	}

	/**
	 * @param $String
	 *
	 * String length with current Font-Settings (single line)
	 *
	 * @return float mm
	 */
	public function GetStringWidth( $String ) {
		return $this->getPdf()->GetStringWidth( $String );
	}
	/**
	 * String height with current Font-Settings (single line)
	 *
	 * @return float mm
	 */
	public function GetStringHeight() {
		return $this->getPdf()->FontSize;
	}

	public function DrawRectangle( $X, $Y, $Width, $Height ) {
		$this->getPdf()->Rect( $X, $Y, $Width, $Height );
	}
	public function DrawText( $X, $Y, $String ) {
		$this->getPdf()->Text( $X, $Y, $String );
	}
	public function DrawCell( $Width, $Height, $String ) {
		$this->getPdf()->Cell( $Width, $Height, $String );
	}

	public function SetFontFamily( $Name ){
		$this->getPdf()->SetFont( $Name );
	}
	public function SetFontSize( $Value ){
		$this->getPdf()->SetFontSize( $Value );
	}

	public function SetPosition( $X, $Y ) {
		$this->getPdf()->SetY( $Y );
		$this->getPdf()->SetX( $X );
	}

	public function GetFontFamily(){
		return $this->getPdf()->FontFamily;
	}
	public function GetFontSize(){
		return $this->getPdf()->FontSizePt;
	}


	public function GetPositionX() {
		return $this->getPdf()->GetX();
	}
	public function GetPositionY() {
		return $this->getPdf()->GetY();
	}

	public function GetPageSpaceRight() {
		return $this->GetPositionX() - $this->GetPageMarginRight();
	}
	public function GetPageSpaceBottom() {
		return $this->GetPageHeight() - $this->GetPositionY() - $this->GetPageMarginBottom();
	}

	// Passe-Partout
	public function GetPageWidthFrame() {
		return $this->GetPageWidth() - ( $this->GetPageMarginLeft() + $this->GetPageMarginRight() );
	}
	public function GetPageHeightFrame() {
		return $this->GetPageHeight() - ( $this->GetPageMarginTop() + $this->GetPageMarginBottom() );
	}

	// Full size
	public function GetPageHeight() {
		return $this->getPdf()->CurPageSize[1];
	}
	public function GetPageWidth() {
		return $this->getPdf()->CurPageSize[0];
	}

	// Margin
	public function GetPageMarginTop() {
		return $this->getPdf()->tMargin;
	}
	public function GetPageMarginRight() {
		return $this->getPdf()->rMargin;
	}
	public function GetPageMarginBottom() {
		return $this->getPdf()->bMargin;
	}
	public function GetPageMarginLeft() {
		return $this->getPdf()->lMargin;
	}

	// Adapter
	private function getPdf() {
		return Api::Extension()->Pdf()->Current();
	}
}
