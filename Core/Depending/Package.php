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
 * Package
 * 03.06.2013 13:53
 */
namespace MOC\Core\Depending;
use MOC\Api;
use MOC\Core\Version;
use MOC\Core\Changelog;
use MOC\Generic\Device\Core;

/**
 *
 */
class Package implements Core {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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
	 * @return Package
	 */
	public static function InterfaceInstance() {
		return new Package();
	}

	/** @var string $Namespace */
	private $Namespace = '';
	/** @var string $Class */
	private $Class = '';
	/** @var Version $Version */
	private $Version = null;
	/** @var bool $Optional */
	private $Optional = false;
	/** @var Changelog $Changelog */
	private $Changelog = false;

	/**
	 * @param string $Namespace
	 *
	 * @return Package
	 */
	public function SetNamespace( $Namespace ) {
		$this->Namespace = $Namespace;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetNamespace() {
		return $this->Namespace;
	}

	/**
	 * @param string $Class
	 *
	 * @return Package
	 */
	public function SetClass( $Class ) {
		$this->Class = $Class;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetClass() {
		return $this->Class;
	}

	/**
	 * @param Version $Version
	 *
	 * @return Package
	 */
	public function SetVersion( Version $Version ) {
		$this->Version = $Version;
		return $this;
	}

	/**
	 * @return Version
	 */
	public function GetVersion() {
		return $this->Version;
	}

	/**
	 * @param Changelog $Changelog
	 *
	 * @return Package
	 */
	public function SetChangelog( Changelog $Changelog ) {
		$this->Changelog = $Changelog;
		return $this;
	}

	/**
	 * @return Changelog
	 */
	public function GetChangelog() {
		return $this->Changelog;
	}

	/**
	 * @param bool $Optional
	 *
	 * @return Package
	 */
	public function SetOptional( $Optional = false ) {
		$this->Optional = $Optional;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetOptional() {
		return $this->Optional;
	}

	/**
	 * @return \MOC\Core\Drive\File
	 */
	public function GetFile() {
		$Directory = Api::Core()->Drive()->Directory()->Handle( __DIR__ . '/../../'.trim( preg_replace( '!MOC!', '', $this->Namespace, 1 ), '\\' ) );
		return Api::Core()->Drive()->File()->Handle( $Directory->Location().DIRECTORY_SEPARATOR.$this->Class.'.php' );
	}

	/**
	 * @return string
	 */
	public function GetFQN() {
		return trim( $this->Namespace.'\\'.$this->Class , '\\' );
	}
}
