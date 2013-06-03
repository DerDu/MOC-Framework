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
use MOC\Api;
use MOC\Core\Depending\Package;
use MOC\Generic\Device\Core;

/**
 *
 */
class Depending implements Core {

	/** @var Package[] $Dependencies */
	private $PackageList = array();

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
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '03.06.2013 14:43', 'Redesign' )
			->Build()->Clearance( '03.06.2013 14:58', 'Development' )
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
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Core\Depending' )
				->SetClass( 'Package' )->SetOptional( false )->SetVersion(
					Api::Core()->Version()
				)
			)
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Core\Depending' )
				->SetClass( 'Result' )->SetOptional( false )->SetVersion(
					Api::Core()->Version()
				)
			)
		;
	}

	/**
	 * Create a new Dependency-Package
	 *
	 * @return Package
	 */
	public function NewPackage() {
		return Package::InterfaceInstance();
	}

	/**
	 * @param Package $Package
	 *
	 * @return Depending
	 */
	public function AddPackage( Package $Package ){
		$this->PackageList[] = $Package;
		return $this;
	}

	/**
	 * @return Package[]
	 */
	public function GetPackageList() {
		return $this->PackageList;
	}

	/**
	 * @return int
	 */
	private function PackageTree() {
		foreach( $this->PackageList as $Package ) {
			if( $Package->GetFile()->Exists() ) {
				$Object = $Package->GetFQN();
				/** @var \MOC\Generic\Common $Object */
				$Children = $Object::InterfaceDepending()->GetPackageList();
				foreach( $Children as $ChildPackage ) {
					if( !in_array( $ChildPackage, $this->PackageList ) ) {
						$this->PackageList[] = $ChildPackage;
					}
				}
			}
		}
		return count( $this->PackageList );
	}

	/**
	 * @return Depending\Result
	 */
	public function Analyze() {
		$Analyze = Version::InterfaceInstance();
		$Result = Depending\Result::InterfaceInstance();

		// Collect Package-Tree
		$PackageCount = count( $this->PackageList );
		while( $PackageCount < $this->PackageTree() ) {
			$PackageCount = count( $this->PackageList );
		}

		// Analyze Package-Dependencies
		/** @var Package[] $RequiredVersion */
		$RequiredVersion = array();
		foreach( $this->PackageList as $Package ) {
			if( array_key_exists( $Package->GetFQN(), $RequiredVersion ) ) {
				if( true === $Analyze->Compare( $RequiredVersion[$Package->GetFQN()]->GetVersion(), $Package->GetVersion() ) ) {
					$RequiredVersion[$Package->GetFQN()] = $Package;
				}
			} else {
				$RequiredVersion[$Package->GetFQN()] = $Package;
			}
		}

		// Create Package-Update-List
		foreach( $RequiredVersion as $Package ) {
			if( $Package->GetFile()->Exists() ) {
				/** @var \MOC\Generic\Common $Object */
				$Object = $Package->GetFQN();
				$Changelog = $Object::InterfaceChangelog();
				if( true === $Analyze->Compare( $Changelog->Version(), $Package->GetVersion() ) ) {
					$Package->SetChangelog( $Changelog );
					$Result->Update( $Package );
				} else {
					$Package->SetVersion( $Changelog->Version() );
					$Result->Available( $Package );
				}
			} else {
				$Result->Install( $Package );
			}
		}

		return $Result;
	}
}
