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
 * Module
 * 29.08.2012 15:26
 */
namespace MOC\Adapter;
use MOC\Api;
use MOC\Generic\Device\Adapter;
use MOC\Module\Browser;
use MOC\Module\Database;
use MOC\Module\Drive;
use MOC\Module\Html;
use MOC\Module\Network;
use MOC\Module\Office;
use MOC\Module\Packer;
use MOC\Module\Template;

/**
 * Class which provides an interface to the Module functionality of MOC
 */
class Module implements Adapter {

	/** @var Module $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return  Module
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Module();
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
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Module' )
				->SetClass( 'Database' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Module' )
				->SetClass( 'Drive' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Module' )
				->SetClass( 'Network' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Module' )
				->SetClass( 'Office' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Module' )
				->SetClass( 'Packer' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Module' )
				->SetClass( 'Template' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
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
			->Build()->Clearance( '03.06.2013 15:17', 'Development' )
			->Fix()->DocFix( '03.06.2013 15:18', 'Dependencies' )
		;
	}

	/**
	 * @return Drive
	 */
	public function Drive() {
		return Drive::InterfaceInstance();
	}

	/**
	 * @return Browser
	 */
	public function Browser() {
		return Browser::InterfaceInstance();
	}

	/**
	 * @return Network
	 */
	public function Network() {
		return Network::InterfaceInstance();
	}

	/**
	 * @return Office
	 */
	public function Office() {
		return Office::InterfaceInstance();
	}

	/**
	 * @return Packer
	 */
	public function Packer() {
		return Packer::InterfaceInstance();
	}

	/**
	 * @return Template
	 */
	public function Template() {
		return Template::InterfaceInstance();
	}

	/**
	 * @return Database
	 */
	public function Database() {
		return Database::InterfaceInstance();
	}

	/**
	 * @return Html
	 */
	public function Html() {
		return Html::InterfaceInstance();
	}

}
