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
 * 30.05.2013 13:32
 */
namespace MOC\Extension\FlowPlayer;
use MOC\Api;
use MOC\Generic\Device\Extension;
use MOC\Generic\Device\Extension\Download;

/**
 *
 */
class Instance extends Download implements Extension {

	/** @var Instance $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Instance
	 */
	public static function InterfaceInstance() {
		/*
		if( self::$Singleton === null ) {
			self::$Singleton = new Instance();
		} return self::$Singleton;
		*/
		return new Instance();
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

	/**
	 * Create new Extension-Instance
	 *
	 * Contains:
	 * - Do 3rdParty Setup
	 * - Create new (intern) 3rdParty Instance
	 *
	 * @return \MOC\Generic\Device\Extension
	 */
	public function Create() {
		$Path = __DIR__.DIRECTORY_SEPARATOR.'3rdParty';
		if( Api::Core()->Drive()->Directory()->Handle( $Path )->IsEmpty() ) {
			$this->DownloadProjectPage( 'http://flash.flowplayer.org/download/' );
			$this->DownloadProjectFilter( '!http://releases\.flowplayer\.org/flowplayer/[^"]*?.zip!is' );
			$this->DownloadProjectLocation( $Path );
			$this->DownloadProject();
		}
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

	/**
	 * Get active 3rdParty Instance
	 *
	 * @return Object
	 */
	public function Current() {
		// TODO: Implement Current() method.
	}

	/**
	 * Destroy active 3rdParty Instance
	 *
	 * @return \MOC\Generic\Device\Extension
	 */
	public function Destroy() {
		// TODO: Implement Destroy() method.
	}


}
