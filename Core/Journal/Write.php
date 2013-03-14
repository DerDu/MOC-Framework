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
 * Write
 * 01.08.2012 12:15
 */
namespace MOC\Core\Journal;
use \MOC\Api;
/**
 *
 */
class Write implements \MOC\Generic\Common {
	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Write
	 */
	public static function InterfaceInstance() {
		return new Write();
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending()
			->Package( '\MOC\Core\Drive', \MOC\Core\Version::InterfaceInstance()->Build(1) )
			->Package( '\MOC\Core\Session', \MOC\Core\Version::InterfaceInstance()->Build(1) );
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

	private $Name = null;
	private $Location = null;

	/**
	 *
	 */
	function __construct() {
		$this->Name = 'Common';
		$this->Location = __DIR__ . '/../../Data/Journal';

		$Directory = \MOC\Core\Drive::InterfaceInstance()->Directory();
		$Directory->Handle( $this->Location );

		$this->Location = $Directory->Location();
	}

	/**
	 * @param null $Journal
	 *
	 * @return Write|null|string
	 */
	public function Name( $Journal = null ) {
		if( null !== $Journal ) {
			$this->Name = preg_replace('![^\w\d]!','-',$Journal);
			return $this;
		} return $this->Name;
	}

	/**
	 * @param $Content
	 */
	public function Content( $Content ) {
		$Journal = $this->Location.'/Journal.'.$this->Name.'.txt';

		$File = \MOC\Core\Drive::InterfaceInstance()->File();
		$File->Handle( $Journal );

		if( date( 'Ymd', $File->Time() ) < date('Ymd') ) {
			$File->Move( substr( $Journal, 0, -4 ).'.'.date('YmdHis').'.txt' );
			$File->Handle( $Journal );
		}

		$Content = ( $File->Size() != 0 ? "\n" : '' )
				.str_repeat('-',50)."\n"
				.date("d.m.Y H:i:s",time())." SID:".strtoupper(
					\MOC\Core\Session::InterfaceInstance()->Id()
				)."\n"
				.str_repeat('-',50)."\n"
				.print_r( $Content, true )."\n";

		$File->Content( $Content )->Save( $File::MODE_APPEND );
	}
}
