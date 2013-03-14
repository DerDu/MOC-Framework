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
 * Instance
 * 14.02.2013 11:25
 */
namespace MOC\Extension\Pdf;
use \MOC\Api;
/**
 *
 */
class Instance implements \MOC\Generic\Device\Extension {

	/** @var Instance $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Instance
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		require_once( '3rdParty/tFPDF/tfpdf.php' );
		require_once( '3rdParty/tFPDF/makefont/makefont.php' );
		if( self::$Singleton === null ) {
			self::$Singleton = new Instance();
		} return self::$Singleton;
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '18.02.2013 11:22', 'Alpha' )
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
	 * Set external Extension-Instance
	 *
	 * Contains:
	 * - Set new (extern created) 3rdParty Instance to Current
	 *
	 * @param $Instance
	 *
	 * @return \MOC\Generic\Device\Extension
	 */
	public function Define( $Instance ) {
		// TODO: Implement Define() method.
	}

	/**
	 * Select Index as active 3rdParty Instance
	 *
	 * @param int $Index
	 *
	 * @return \MOC\Generic\Device\Extension
	 */
	public function Select( $Index ) {
		// TODO: Implement Select() method.
	}

	/**
	 * Get Index for Select() from Current() 3rdParty Instance
	 *
	 * @return int
	 */
	public function Index() {
		// TODO: Implement Index() method.
	}


	/** @var \tFPDF[] $Queue */
	private $Queue = array();
	/** @var \tFPDF $Current */
	private $Current = null;

	/**
	 * @return Instance
	 */
	public function Create() {
		$Instance = new \tFPDF( 'P', 'mm', 'A4' );
		if( null === $this->Current ) {
			$this->Current = $Instance;
			return $this;
		} else {
			array_push( $this->Queue, $this->Current );
			$this->Current = $Instance;
			return $this;
		}
	}

	/**
	 * @return null|\tFPDF
	 */
	public function Current() {
		return $this->Current;
	}

	/**
	 * @return Instance
	 */
	public function Destroy() {
		if( count( $this->Queue ) ) {
			$this->Current = array_pop( $this->Queue );
		} else {
			$this->Current = null;
		}
		return $this;
	}
}
