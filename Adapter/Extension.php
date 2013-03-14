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
 * Extension
 * 29.08.2012 15:26
 */
namespace MOC\Adapter;
use MOC\Api;
use MOC\Generic\Device\Adapter;

/**
 *
 */
class Extension implements Adapter {

	/** @var Extension $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return  Extension
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Extension();
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
			->Package( '\MOC\Extension\Excel\Instance', Api::Core()->Version() )
			->Package( '\MOC\Extension\Mail\Instance', Api::Core()->Version() )
			->Package( '\MOC\Extension\Pdf\Instance', Api::Core()->Version() )
			->Package( '\MOC\Extension\Zip\Instance', Api::Core()->Version() )
			->Package( '\MOC\Extension\ApiGen\Instance', Api::Core()->Version() )
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
			->Fix()->BugFix( '18.02.2013 13:57', 'Depending' )
			->Update()->Added( '19.02.2013 8:29', 'ApiGen()' )
			->Update()->Added( '01.03.2013 14:25', 'Word()' )
		;
	}

	/**
	 * @return \MOC\Extension\Excel\Instance
	 */
	public function Excel() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\Excel\Instance::InterfaceInstance();
	}

	/**
	 * @return \MOC\Extension\Mail\Instance
	 */
	public function Mail() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\Mail\Instance::InterfaceInstance();
	}

	/**
	 * @return \MOC\Extension\Pdf\Instance
	 */
	public function Pdf() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\Pdf\Instance::InterfaceInstance();
	}

	/**
	 * @return \MOC\Extension\Word\Instance
	 */
	public function Word() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\Word\Instance::InterfaceInstance();
	}

	/**
	 * @return \MOC\Extension\Zip\Instance
	 */
	public function Zip() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\Zip\Instance::InterfaceInstance();
	}

	/**
	 * @return \MOC\Extension\ApiGen\Instance
	 */
	public function ApiGen() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\ApiGen\Instance::InterfaceInstance();
	}

	/**
	 * @return \MOC\Extension\YUICompressor\Instance
	 */
	public function YUICompressor() {
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		return \MOC\Extension\YUICompressor\Instance::InterfaceInstance();
	}
}
