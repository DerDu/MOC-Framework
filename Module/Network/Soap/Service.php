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
 * Service
 * 02.07.2013 11:06
 */
namespace MOC\Module\Network\Soap;
use MOC\Api;
use MOC\Core\Xml\Node;
use MOC\Generic\Device\Module;
use MOC\Module\Network\Soap\Service\Endpoint;

/**
 *
 */
class Service implements Module {
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
	 * @return Service
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Service();
	}

	/** @var string $Name */
	private $Name = '';
	/** @var Endpoint[] $Endpoint */
	private $Endpoint = array();

	/**
	 * @param Node $Service
	 *
	 * @return Service
	 */
	public function Definition( Node $Service ) {
		$this->Name = $Service->GetAttribute('name');
		// WSDL 1.0
		$EndpointIndex = 0;
		while( false != ( $Endpoint = $Service->GetChild('!:?port!is', null, $EndpointIndex++, false, true ) ) ) {
			$Instance = new Endpoint();
			$Instance->Definition( $Endpoint );
			$this->Endpoint[] = $Instance;
		}
		// WSDL 2.0
		$EndpointIndex = 0;
		while( false != ( $Endpoint = $Service->GetChild('!:?endpoint!is', null, $EndpointIndex++, false, true ) ) ) {
			$Instance = new Endpoint();
			$Instance->Definition( $Endpoint );
			$this->Endpoint[] = $Instance;
		}
		$Service->GetParent()->RemoveChild( $Service );
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetName() {
		return $this->Name;
	}

	/**
	 * @return Service\Endpoint[]
	 */
	public function GetEndpointList() {
		return $this->Endpoint;
	}
}
