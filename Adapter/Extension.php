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
 * Extension
 * 29.08.2012 15:26
 */
namespace MOC\Adapter;
use MOC\Api;
use MOC\Generic\Device\Adapter;
use MOC\Extension\AppGati\Instance as AppGati;
use MOC\Extension\Excel\Instance as Excel;
use MOC\Extension\Mail\Instance as Mail;
use MOC\Extension\Pdf\Instance as Pdf;
use MOC\Extension\Word\Instance as Word;
use MOC\Extension\Xml\Instance as Xml;
use MOC\Extension\YUICompressor\Instance as YUICompressor;
use MOC\Extension\Zip\Instance as Zip;
/**
 * Class which provides an interface to the Extension functionality of MOC
 */
class Extension implements Adapter {

	/** @var Extension $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return  Extension
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Extension();
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
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\Excel' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\Mail' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\Pdf' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\Word' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\Xml' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\YUICompressor' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
			->AddPackage( Api::Core()->Depending()->NewPackage()->SetNamespace( 'MOC\Extension\Zip' )
				->SetClass( 'Instance' )->SetOptional( false )->SetVersion( Api::Core()->Version() ) )
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
			->Build()->Clearance( '03.06.2013 15:16', 'Development' )
			->Fix()->DocFix( '03.06.2013 15:17', 'Dependencies' )
		;
	}

	/**
	 * @return Excel
	 */
	public function Excel() {
		return Excel::InterfaceInstance();
	}

	/**
	 * @return Mail
	 */
	public function Mail() {
		return Mail::InterfaceInstance();
	}

	/**
	 * @return Pdf
	 */
	public function Pdf() {
		return Pdf::InterfaceInstance();
	}

	/**
	 * @return Word
	 */
	public function Word() {
		return Word::InterfaceInstance();
	}

	/**
	 * @return Xml
	 */
	public function Xml() {
		return Xml::InterfaceInstance();
	}

	/**
	 * @return Zip
	 */
	public function Zip() {
		return Zip::InterfaceInstance();
	}

	/**
	 * @return YUICompressor
	 */
	public function YUICompressor() {
		return YUICompressor::InterfaceInstance();
	}

	/**
	 * @return AppGati
	 */
	public function AppGati() {
		return AppGati::InterfaceInstance();
	}
}
