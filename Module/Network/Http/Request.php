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
 * Request
 * 16.07.2013 12:47
 */
namespace MOC\Module\Network\Http;
use MOC\Api;
use MOC\Generic\Device\Module;

/**
 *
 */
class Request implements Module {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
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
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Request
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Request();
	}

	/** @var null|string $Name */
	private $Name = null;
	private $Index = array();

	/**
	 * @param string $Name
	 *
	 * Index List after Name e.g. Select( 'Key' [,'Index-Dim-1'] [,'Index-Dim-2'] [,...] )
	 *
	 * @return Request
	 */
	public function Select( $Name ) {
		if( func_num_args() > 1 ) {
			$Index = func_get_args();
			array_shift( $Index );
			$this->Index = $Index;
		} else {
			$this->Index = array();
		}
		$this->Name = $Name;
		return $this;
	}

	public function Name() {
		return $this->Name;
	}

	/**
	 * @return Request\Validation
	 */
	public function Check() {
		return new Request\Validation( $this );
	}

	public function Count() {
		$Value = $this->GetValue();
		if( is_array( $Value ) ) {
			return count( $Value );
		} else {
			return 1;
		}
	}
	public function IndexList() {
		$Value = $this->GetValue();
		if( is_array( $Value ) ) {
			return array_keys( $Value );
		} else {
			return array();
		}
	}
	public function Get() {
		$Value = $this->GetValue();
		if( is_array( $Value ) ) {
			Api::Core()->Error()->Type()->Exception()->Trigger( 'Get() may return single values only!', __FILE__, __LINE__ );
			return null;
		} else {
			return $Value;
		}
	}

	/**
	 * @return mixed
	 */
	private function GetValue() {
		if( isset( $_REQUEST[$this->Name] ) ) {
			if( empty( $this->Index ) ) {
				return $_REQUEST[$this->Name];
			} else {
				$Request = $_REQUEST[$this->Name];
				$IndexCount = count( $this->Index );
				for( $I = 0; $I < $IndexCount; $I++ ) {
					if( isset( $Request[$this->Index[$I]] ) ) {
						$Request = $Request[$this->Index[$I]];
					} else {
						return null;
					}
				}
				return $Request;
			}
		} else {
			Api::Core()->Error()->Type()->Exception()->Trigger( 'The selected key ['.$this->Name.'] is not available!', __FILE__, __LINE__ );
		}
		return null;
	}

	/**
	 * @param $Value
	 */
	public function Set( $Value ) {
		if( isset( $_REQUEST[$this->Name] ) ) {
			$_REQUEST[$this->Name] = $Value;
		} else {
			Api::Core()->Error()->Type()->Exception()->Trigger( 'The selected key ['.$this->Name.'] is not available!', __FILE__, __LINE__ );
		}
	}
}
