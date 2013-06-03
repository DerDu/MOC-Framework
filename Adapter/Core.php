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
 * Core
 * 29.08.2012 15:25
 */
namespace MOC\Adapter;
use MOC\Generic\Device\Adapter;
use MOC\Api;
use MOC\Core\Cache;
use MOC\Core\Changelog;
use MOC\Core\Depending;
use MOC\Core\Drive;
use MOC\Core\Encoding;
use MOC\Core\Error;
use MOC\Core\Journal;
use MOC\Core\Proxy;
use MOC\Core\Session;
use MOC\Core\Template;
use MOC\Core\Version;
use MOC\Core\Xml;

/**
 * Class which provides an interface to the Core functionality of MOC
 */
class Core implements Adapter {

	/** @var Core $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return  Core
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Core();
		} return self::$Singleton;
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
	}

	/**
	 * @return Depending
	 */
	public function Depending() {
		return Depending::InterfaceInstance();
	}

	/**
	 * @return Version
	 */
	public function Version() {
		return Version::InterfaceInstance();
	}

	/**
	 * @return Changelog
	 */
	public function Changelog() {
		return Changelog::InterfaceInstance();
	}

	/**
	 * @return Cache
	 */
	public function Cache() {
		return Cache::InterfaceInstance();
	}

	/**
	 * @return Drive
	 */
	public function Drive() {
		return Drive::InterfaceInstance();
	}

	/**
	 * @return Encoding
	 */
	public function Encoding() {
		return Encoding::InterfaceInstance();
	}

	/**
	 * @return Error
	 */
	public function Error() {
		return Error::InterfaceInstance();
	}

	/**
	 * @return Journal
	 */
	public function Journal() {
		return Journal::InterfaceInstance();
	}

	/**
	 * @return Proxy
	 */
	public function Proxy() {
		return Proxy::InterfaceInstance();
	}

	/**
	 * @return Session
	 */
	public function Session() {
		return Session::InterfaceInstance();
	}

	/**
	 * @return Xml
	 */
	public function Xml() {
		return Xml::InterfaceInstance();
	}

	/**
	 * @return Template
	 */
	public function Template() {
		return Template::InterfaceInstance();
	}
}
