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
 * Page
 * 27.02.2013 16:06
 */
namespace MOC\Module\Office\Document\Pdf;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Page implements Module {
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
	 * @return Page
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Page();
	}

	/**
	 * Define Page-Orientation
	 *
	 * - Must be specified BEFORE calling Create()
	 *
	 * @return Page\Orientation
	 */
	public function Orientation(){
		return Page\Orientation::InterfaceInstance();
	}

	/**
	 * Define Page-Size
	 *
	 * - Must be specified BEFORE calling Create()
	 *
	 * @return Page\Size
	 */
	public function Size(){
		return Page\Size::InterfaceInstance();
	}

	/**
	 * Define Page-Margin
	 *
	 * - Must be specified BEFORE calling Create()
	 *
	 * @return Page\Margin
	 */
	public function Margin() {
		return Page\Margin::InterfaceInstance();
	}

	/**
	 * Create new Page
	 *
	 * with Page-Orientation, Page-Size, Page-Margin
	 *
	 * @return \MOC\Module\Office\Document\Pdf
	 */
	public function Create() {
		Api::Extension()->Pdf()->Current()->AddPage(
			$this->Orientation()->Current(),
			$this->Size()->Current()
		);
		return Api::Module()->Office()->Document()->Pdf();
	}

	/**
	 * @return Page\Position
	 */
	public function Position() {
		return Page\Position::InterfaceInstance();
	}
}
