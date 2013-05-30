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
 * 13.03.2013 09:51
 */
namespace MOC\Extension\YUICompressor;
use MOC\Api;
use MOC\Generic\Device\Extension;

/**
 *
 */
class Instance implements Extension {

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

	/** @var \YUICompressor[] $Queue */
	private $Queue = array();
	/** @var \YUICompressor $Current */
	private $Current = null;

	/**
	 * Create new Extension-Instance
	 *
	 * Contains:
	 * - Do 3rdParty Setup
	 * - Create new (internal) 3rdParty Instance
	 *
	 * @return Instance
	 */
	public function Create() {
		$JarPath = Api::Core()->Drive()->File()->Handle( __DIR__.'/3rdParty/yuicompressor/build/yuicompressor-2.4.8pre.jar' )->Location();
		$CachePath = Api::Core()->Cache()->Group(__CLASS__)->Timeout( 60 )->Set('')->Path();
		require_once( '3rdParty/yuicompressor.php' );
		$this->Define( new \YUICompressor( $JarPath, $CachePath ) );
		return $this;
	}

	/**
	 * Set external Extension-Instance
	 *
	 * Contains:
	 * - Set new (external created) 3rdParty Instance to Current
	 *
	 * @param $Instance
	 *
	 * @return Instance
	 */
	public function Define( $Instance ) {
		if( null === $this->Current ) {
			$this->Current = $Instance;
		} else {
			array_push( $this->Queue, $this->Current );
			$this->Current = $Instance;
		}
		return $this;
	}

	/**
	 * Select Index as active 3rdParty Instance
	 *
	 * @param int $Index
	 *
	 * @return Instance
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

	/**
	 * @return \YUICompressor
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
