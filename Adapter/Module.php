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
 * Module
 * 29.08.2012 15:26
 */
namespace MOC\Adapter;
use \MOC\Api;
/**
 *
 */
class Module implements \MOC\Generic\Device\Adapter {

	/** @var \MOC\Adapter\Module $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return  \MOC\Adapter\Module
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new \MOC\Adapter\Module();
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
			->Package( '\MOC\Module\Drive', Api::Core()->Version() )
			->Package( '\MOC\Module\Network', Api::Core()->Version() )
			->Package( '\MOC\Module\Office', Api::Core()->Version() )
			->Package( '\MOC\Module\Packer', Api::Core()->Version() )
			->Package( '\MOC\Module\Installer', Api::Core()->Version() )
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
			->Fix()->BugFix( '18.02.2013 14:03', 'Depending' )
		;
	}

	/**
	 * @return \MOC\Module\Drive
	 */
	public function Drive() {
		return \MOC\Module\Drive::InterfaceInstance();
	}

	/**
	 * @return \MOC\Module\Network
	 */
	public function Network() {
		return \MOC\Module\Network::InterfaceInstance();
	}

	/**
	 * @return \MOC\Module\Office
	 */
	public function Office() {
		return \MOC\Module\Office::InterfaceInstance();
	}

	/**
	 * @return \MOC\Module\Packer
	 */
	public function Packer() {
		return \MOC\Module\Packer::InterfaceInstance();
	}

	/**
	 * @return \MOC\Module\Template
	 */
	public function Template() {
		return \MOC\Module\Template::InterfaceInstance();
	}

	/**
	 * @return \MOC\Module\Database
	 */
	public function Database() {
		return \MOC\Module\Database::InterfaceInstance();
	}

	/**
	 * @return \MOC\Module\Installer
	 */
	public function Installer() {
		return \MOC\Module\Installer::InterfaceInstance();
	}
}
