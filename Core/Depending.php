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
 * Depending
 * 31.08.2012 12:50
 */
namespace MOC\Core;
use \MOC\Api;
/**
 *
 */
class Depending implements \MOC\Generic\Device\Core {
	/** @var array $Dependencies */
	public $Dependencies = array();

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Depending
	 */
	public static function InterfaceInstance() {
		return new Depending();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '18.02.2013 13:52', 'Alpha' )
		;
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return Depending
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending()
			->Package( '\MOC\Core\Drive', Api::Core()->Version()->Build(1) );
	}

	/**
	 * @param string $Class
	 * @param Version $Version
	 *
	 * @return Depending
	 */
	public function Package( $Class, Version $Version ){
		$this->Dependencies[$Class] = $Version;
		return $this;
	}





	/**
	 * @return int
	 */
	private function PackageTree() {
		$Analyze = Version::InterfaceInstance();
		foreach( (array)$this->Dependencies as $Namespace => $Dependence ) {
			foreach( (array)$Dependence as $Class => $Version ) {
				$Directory = __DIR__ . '/../'.trim( preg_replace( '!MOC!', '', $Namespace, 1 ), '\\' );
				$File = Drive::InterfaceInstance()->File()->Handle( $Directory.'/'.$Class.'.php' );
				if( $File->Exists() ) {
					/** @var \MOC\Generic\Common $Object */
					$Object = $Namespace.'\\'.$Class;

					$DependenceTree = $Object::InterfaceDepending()->Dependencies;
					foreach( (array)$DependenceTree as $Namespace => $Node ) {
						foreach( (array)$Node as $Class => $TreeVersion ) {
							if( !array_key_exists( $Namespace, $this->Dependencies ) ) {
								$this->Dependencies[$Namespace] = array();
							}
							if( !array_key_exists( $Class, $this->Dependencies[$Namespace] ) ) {
								$this->Dependencies[$Namespace][$Class] = $TreeVersion;
							} else {
								if( true === $Analyze->Compare( $Version, $TreeVersion ) ) {
									$this->Dependencies[$Namespace][$Class] = $TreeVersion;
								}
							}
						}
					}
				}
			}
		}
		return count( $this->Dependencies );
	}

	/**
	 * @return Depending\Result
	 */
	public function Analyze() {
		$Result = Depending\Result::InterfaceInstance();
		$Analyze = Version::InterfaceInstance();

		// Collect Package-Tree
		$PackageCount = count( $this->Dependencies );
		while( $PackageCount < $this->PackageTree() ) {
			$PackageCount = count( $this->Dependencies );
		}
		// Analyze Package-Dependencies
		foreach( (array)$this->Dependencies as $Namespace => $Dependence ) {
			/** @var Version $Version */
			foreach( (array)$Dependence as $Class => $Version ) {
				$Directory = __DIR__ . '/../'.trim( preg_replace( '!MOC!', '', $Namespace, 1 ), '\\' );
				$File = Drive::InterfaceInstance()->File()->Handle( $Directory.'/'.$Class.'.php' );
				if( $File->Exists() ) {
					/** @var \MOC\Generic\Common $Object */
					$Object = $Namespace.'\\'.$Class;
					// Version not valid ?
					if( true === $Analyze->Compare( $Object::InterfaceChangelog()->Version(), $Version ) ) {
						$Result->Update( $Namespace, $Class, $Version );
					}
				} else {
					$Result->Install( $Namespace, $Class, $Version );
				}
			}
		}
		return $Result;
	}
}
