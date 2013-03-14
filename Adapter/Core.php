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
use \MOC\Api;
/**
 *
 */
class Core implements \MOC\Generic\Device\Adapter {

	/** @var \MOC\Adapter\Core $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return  \MOC\Adapter\Core
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new \MOC\Adapter\Core();
		} return self::$Singleton;
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending()
			->Package( '\MOC\Core\Cache', Api::Core()->Version() )
			->Package( '\MOC\Core\Drive', Api::Core()->Version() )
			->Package( '\MOC\Core\Encoding', Api::Core()->Version() )
			->Package( '\MOC\Core\Error', Api::Core()->Version() )
			->Package( '\MOC\Core\Journal', Api::Core()->Version() )
			->Package( '\MOC\Core\Proxy', Api::Core()->Version() )
			->Package( '\MOC\Core\Session', Api::Core()->Version() )
			->Package( '\MOC\Core\Xml', Api::Core()->Version() )
			->Package( '\MOC\Core\Template', Api::Core()->Version() )
		;
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '18.02.2013 10:02', 'Alpha' )
			->Build()->Clearance( '18.02.2013 11:31', 'Beta' )
			->Fix()->BugFix( '18.02.2013 13:56', 'Depending' )
			->Update()->Added( '18.02.2013 16:33', 'Template()' )
		;
	}

	// ========================================================================================================== //
	/**
	 * @return \MOC\Core\Depending
	 */
	public function Depending() {
		return \MOC\Core\Depending::InterfaceInstance();
	}
	/**
	 * @return \MOC\Core\Version
	 */
	public function Version() {
		return \MOC\Core\Version::InterfaceInstance();
	}
	/**
	 * @return \MOC\Core\Changelog
	 */
	public function Changelog() {
		return \MOC\Core\Changelog::InterfaceInstance();
	}
    // ========================================================================================================== //

	/**
	 * @return \MOC\Core\Cache
	 */
	public function Cache() {
		return \MOC\Core\Cache::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Drive
	 */
	public function Drive() {
		return \MOC\Core\Drive::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Encoding
	 */
	public function Encoding() {
		return \MOC\Core\Encoding::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Error
	 */
	public function Error() {
		return \MOC\Core\Error::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Journal
	 */
	public function Journal() {
		return \MOC\Core\Journal::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Proxy
	 */
	public function Proxy() {
		return \MOC\Core\Proxy::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Session
	 */
	public function Session() {
		return \MOC\Core\Session::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Xml
	 */
	public function Xml() {
		return \MOC\Core\Xml::InterfaceInstance();
	}

	/**
	 * @return \MOC\Core\Template
	 */
	public function Template() {
		return \MOC\Core\Template::InterfaceInstance();
	}
}
