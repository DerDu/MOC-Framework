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
 * Changelog
 * 18.02.2013 08:19
 */
namespace MOC\Core;
use \MOC\Api;
/**
 *
 */
class Changelog implements \MOC\Generic\Device\Core {
	/** @var Changelog $Singleton */
	private static $Singleton = null;

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return Depending
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
		if( self::$Singleton === null ) {
			self::$Singleton = new Changelog();
		} return self::$Singleton;
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
			->Update()->Added( '19.02.2013 20:49', 'Protocol()' )
			->Fix()->BugFix( '20.02.2013 13:46', 'Protocol() reported wrong [Version]' )
		;
	}

	/** @var string $RecordClass */
	private $RecordClass = '-NA-';
	/** @var Changelog\Record[] $RecordList */
	private $RecordList = array();

	/** @var Changelog\Entry[] $Log */
	private $Log = array();

	/** @var Version $Version */
	private $Version = null;

	/**
	 * @param $__CLASS__
	 *
	 * @return Changelog
	 */
	public function Create( $__CLASS__ ) {
		$this->RecordClass = $__CLASS__;
		$this->RecordList = array();
		return $this;
	}

	/**
	 * @param Changelog\Record $Record
	 *
	 * @return Changelog
	 */
	public function Append( Changelog\Record $Record ) {
		array_push( $this->RecordList, $Record );
		return $this;
	}

	/**
	 * @return Version
	 */
	public function Version() {
		$this->Setup();
		return $this->Version;
	}

	/**
	 * @return Changelog\Entry[]
	 */
	public function Log() {
		$this->Setup();
		return $this->Log;
	}

	/**
	 * @return array
	 */
	public function Protocol() {
		$Version = Api::Core()->Version();
		$Protocol = array();
		/** @var Changelog\Record $Record */
		foreach( (array)$this->RecordList as $Record ) {
			$rMethod = new \ReflectionMethod( $Record->Method() );
			$ClassName = $rMethod->getDeclaringClass()->getShortName();
			$MethodName = $rMethod->getName();
			switch( $ClassName ) {
				case 'Release': {
					$Value = $Version->Release();
					$Version->Release( $Value +1 );
					$Version->Build( 0 );
					$Version->Update( 0 );
					$Version->Fix( 0 );
					break;
				}
				case 'Build': {
					$Value = $Version->Build();
					$Version->Build( $Value +1 );
					$Version->Update( 0 );
					$Version->Fix( 0 );
					break;
				}
				case 'Update': {
					$Value = $Version->Update();
					$Version->Update( $Value +1 );
					$Version->Fix( 0 );
					break;
				}
				case 'Fix': {
					$Value = $Version->Fix();
					$Version->Fix( $Value +1 );
					break;
				}
			}
			$Protocol[$this->RecordClass][] = array(
				'Timestamp' => $Record->Timestamp(),
				'Type' => $ClassName,
				'Action' => $MethodName,
				'Version' => clone $Version,
				'Message' => $Record->Message()
			);
		}
		return $Protocol;
	}

	private function Setup() {
		$this->Log = array();
		$this->Version = Api::Core()->Version();

		if( empty( $this->RecordList ) ) {

			$ChangeLogEntry = Changelog\Entry::InterfaceInstance();

			$ChangeLogEntry->Location( $this->RecordClass );
			$ChangeLogEntry->Message( 'MISSING CHANGELOG' );
			//$ChangeLogEntry->Timestamp( '-NA-' );

			$this->Log[] = $ChangeLogEntry;

		} else {

			/** @var Changelog\Record $Record */
			foreach( (array)$this->RecordList as $Record ) {
				$rMethod = new \ReflectionMethod( $Record->Method() );
				$ClassName = $rMethod->getDeclaringClass()->getShortName();
				$MethodName = $rMethod->getName();
				$ChangeLogEntry = Changelog\Entry::InterfaceInstance();
				switch( $ClassName ) {
					case 'Release': {
						$Value = $this->Version->Release();
						$this->Version->Release( $Value +1 );
						$this->Version->Build( 0 );
						$this->Version->Update( 0 );
						$this->Version->Fix( 0 );
						break;
					}
					case 'Build': {
						$Value = $this->Version->Build();
						$this->Version->Build( $Value +1 );
						$this->Version->Update( 0 );
						$this->Version->Fix( 0 );
						break;
					}
					case 'Update': {
						$Value = $this->Version->Update();
						$this->Version->Update( $Value +1 );
						$this->Version->Fix( 0 );
						break;
					}
					case 'Fix': {
						$Value = $this->Version->Fix();
						$this->Version->Fix( $Value +1 );
						break;
					}
				}
				$ChangeLogEntry->Timestamp( $Record->Timestamp() );
				$ChangeLogEntry->Version( $this->Version->Number() );
				$ChangeLogEntry->Type( $ClassName );
				$ChangeLogEntry->Cause( $MethodName );
				$ChangeLogEntry->Message( $Record->Message() );
				$ChangeLogEntry->Location( $Record->Location() );
				$this->Log[] = $ChangeLogEntry;
			}

		}
		rsort( $this->Log );
	}

	/**
	 * Allowed Changes:
	 *
	 * - Interface: NO (use Release)
	 * - Deprecate Method: NO (use Update)
	 * - Add Method: NO (use Update)
	 * - Remove Method: NO (use Build)
	 * - Internal Change: YES
	 *
	 * @return Changelog\Fix
	 */
	public function Fix() {
		return Changelog\Fix::InterfaceInstance();
	}

	/**
	 * Allowed Changes:
	 *
	 * - Interface: NO (use Release)
	 * - Deprecate Method: YES
	 * - Add Method: YES
	 * - Remove Method: NO (use Build)
	 * - Internal Change: NO (use Fix)
	 *
	 * @return Changelog\Update
	 */
	public function Update() {
		return Changelog\Update::InterfaceInstance();
	}

	/**
	 * Allowed Changes:
	 *
	 * - Interface: NO (use Release)
	 * - Deprecate Method: NO (use Update)
	 * - Add Method: NO (use Update)
	 * - Remove Method: YES
	 * - Internal Change: NO (use Fix)
	 *
	 * @return Changelog\Build
	 */
	public function Build() {
		return Changelog\Build::InterfaceInstance();
	}

	/**
	 * Allowed Changes:
	 *
	 * - Interface: YES
	 * - Deprecate Method: NO (use Update)
	 * - Add Method: NO (use Update)
	 * - Remove Method: NO (use Build)
	 * - Internal Change: NO (use Fix)
	 *
	 * @return Changelog\Release
	 */
	public function Release() {
		return Changelog\Release::InterfaceInstance();
	}
}
