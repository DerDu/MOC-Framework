<?php
/**
 * LICENSE (BSD)
 *
 * Copyright (c) 2012, Gerd Christian Kunze
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
 * Template
 * 28.12.2012 15:11
 */
namespace MOC\Core;
use \MOC\Api;
/**
 *
 */
class Template implements \MOC\Generic\Device\Core {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '18.02.2013 16:48', 'Dev', __CLASS__ )
		;
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
	 * @return \MOC\Core\Template
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new \MOC\Core\Template();
	}

	/** @var null|\MOC\Core\Template\Template $Template */
	private $Template = null;
	/** @var string $Content */
	private $Content = '';

	/**
	 * @param \MOC\Core\Template\Template $Template
	 *
	 * @return Template
	 */
	public function ApplyTemplate( \MOC\Core\Template\Template $Template ) {
		$this->Template = $Template;
		$this->Content = $this->Template->GetPayload();
		return $this;
	}

	/**
	 * @return \MOC\Core\Template\Template
	 */
	public function CreateTemplate() {
		return \MOC\Core\Template\Template::InterfaceInstance();
	}

	/**
	 * @param \MOC\Core\Template\Variable $Variable
	 * @param $Limit
	 *
	 * @return Template
	 */
	public function ApplyVariable( \MOC\Core\Template\Variable $Variable, $Limit = -1 ) {
		$this->Content = preg_replace(
			$Variable::REGEX_PATTERN_LEFT
				.$Variable->GetIdentifier()
			.$Variable::REGEX_PATTERN_RIGHT,
			$Variable->GetPayload(),
			$this->Content,
			$Limit
		);
		return $this;
	}

	/**
	 * @return \MOC\Core\Template\Variable
	 */
	public function CreateVariable() {
		return \MOC\Core\Template\Variable::InterfaceInstance();
	}

	/**
	 * @param \MOC\Core\Template\Import $Include
	 * @param $Limit
	 *
	 * @return Template
	 */
	public function ApplyImport( \MOC\Core\Template\Import $Include, $Limit = -1 ) {
		$this->Content = preg_replace(
			$Include::REGEX_PATTERN_LEFT
				.$Include->GetIdentifier()
				.$Include::REGEX_PATTERN_RIGHT,
			$Include->GetPayload(),
			$this->Content,
			$Limit
		);
		return $this;
	}

	/**
	 * @return \MOC\Core\Template\Import
	 */
	public function CreateImport() {
		return \MOC\Core\Template\Import::InterfaceInstance();
	}

	/**
	 * @param \MOC\Core\Template\Complex $Complex
	 * @param $Limit
	 *
	 * @return Template
	 */
	public function ApplyComplex( \MOC\Core\Template\Complex $Complex, $Limit = -1 ) {
		$this->Content = preg_replace(
			$Complex::REGEX_PATTERN_LEFT
				.$Complex->GetIdentifier()
				.$Complex::REGEX_PATTERN_RIGHT,
			$Complex->GetPayload(),
			$this->Content,
			$Limit
		);
		return $this;
	}

	/**
	 * @param bool $doCleanUp
	 *
	 * @return mixed|string
	 */
	public function GetPayload( $doCleanUp = false ) {
		if( $doCleanUp ) {
			$this->Content = preg_replace(
				\MOC\Core\Template\Complex::REGEX_PATTERN_LEFT.'.*?'
					.\MOC\Core\Template\Complex::REGEX_PATTERN_RIGHT, '',
				$this->Content
			);
			$this->Content = preg_replace(
				\MOC\Core\Template\Variable::REGEX_PATTERN_LEFT.'.*?'
					.\MOC\Core\Template\Variable::REGEX_PATTERN_RIGHT, '',
				$this->Content
			);
			$this->Content = preg_replace(
				\MOC\Core\Template\Import::REGEX_PATTERN_LEFT.'.*?'
					.\MOC\Core\Template\Import::REGEX_PATTERN_RIGHT, '',
				$this->Content
			);
		}
		return $this->Content;
	}
}
