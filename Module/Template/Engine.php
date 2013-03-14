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
 * Engine
 * 26.02.2013 19:52
 */
namespace MOC\Module\Template;
use \MOC\Api;
/**
 *
 */
class Engine implements \MOC\Generic\Device\Module {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Engine
	 */
	public static function InterfaceInstance() {
		$Engine = new Engine();
		$Engine->Engine = Api::Core()->Template();
		return $Engine;
	}

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

	/** @var null|\MOC\Core\Template $Engine */
	private $Engine = null;

	public function Draft( Draft $Draft ) {
		$this->Engine->ApplyTemplate(
			Api::Core()->Template()->CreateTemplate()->AssignContent(
				$Draft->_getContent()
			)
		);
		return $this;
	}
	public function Variable( Variable $Variable, $Limit = -1 ) {
		$this->Engine->ApplyVariable(
			Api::Core()->Template()->CreateVariable()->SetData(
				$Variable->_getIdentifier(), $Variable->_getContent()
			)
			, $Limit
		);
		return $this;
	}
	public function Import( Import $Import, $Limit = -1 ) {
		$this->Engine->ApplyImport(
			Api::Core()->Template()->CreateImport()->SetIdentifier(
				$Import->_getIdentifier()
			)->AssignTemplate(
				Api::Core()->Template()->CreateTemplate()->AssignContent( $Import->_getContent() )
			)
			, $Limit
		);
		return $this;
	}



	public function Content( $Clean = false ) {
		return $this->Engine->GetPayload( $Clean );
	}
}
