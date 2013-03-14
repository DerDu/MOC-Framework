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
 * 31.07.2012 16:48
 */
namespace MOC\Core\Drive\Directory;
use MOC\Api;
use MOC\Core\Drive;
use MOC\Core\Error;

/**
 * Directory-Write
 */
class Write extends Property {

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
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return object
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Write();
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
	 * @param string $Directory
	 *
	 * @return \MOC\Core\Drive\Directory
	 * @throws \Exception
	 */
	public function Create( $Directory ) {
		$Directory = $this->UpdateSyntax( $this->Location() . DIRECTORY_SEPARATOR . $Directory );
		if( !is_dir( $Directory ) && !is_file( $Directory ) ) {
			if( false === mkdir( $Directory ) ) {
				Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Unable to create directory!' );
			} else {
				return Drive::InterfaceInstance()->Directory()->Handle( $Directory );
			}
		} else {
			Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Directory or file exists already!' );
		}
		return null;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function Remove() {
		if( false === rmdir( $this->Location() ) ) {
			Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Unable to remove directory!' );
		} return true;
	}

	/**
	 * @param $Directory
	 * @todo Implement Copy
	 * @throws \Exception
	 */
	public function Copy( /** @noinspection PhpUnusedParameterInspection */
		$Directory ) {
		Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Not yet implemented!' );
	}

	/**
	 * @param $Directory
	 * @todo Implement Move
	 * @throws \Exception
	 */
	public function Move( /** @noinspection PhpUnusedParameterInspection */
		$Directory ) {
		Error::InterfaceInstance()->Type()->Exception()->Trigger( 'Not yet implemented!' );
	}
}
