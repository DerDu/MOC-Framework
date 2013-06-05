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
 * Manager
 * 05.06.2013 13:30
 */
namespace MOC\Plugin;
use MOC\Api;
use MOC\Generic\Device\Plugin;
/**
 *
 */
class Manager implements Plugin {

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		// TODO: Implement InterfaceChangelog() method.
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		// TODO: Implement InterfaceDepending() method.
	}

	/** @var Manager $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Manager
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Manager();
		} return self::$Singleton;
	}

	/** @var Hook[] $Repository */
	private $Repository = array();

	/**
	 * Load Plugin-Repository
	 */
	private function __construct() {
		$Repository = Api::Module()->Drive()->Directory()->Open( __DIR__.DIRECTORY_SEPARATOR.'Repository' )->FileList();
		foreach( $Repository as $Plugin ) {
			try {
				$Reflection = new \ReflectionClass( 'MOC\\Plugin\\Repository\\'.$Plugin->GetName() );
				if( is_object( $Reflection->getParentClass() ) && $Reflection->getParentClass()->getName() == 'MOC\\Plugin\\Hook' ) {
					/** @var Hook $Plugin */
					$Plugin = $Reflection->newInstance();
					$Plugin->HookLoader();
					$this->Repository[$Reflection->getShortName()] = $Plugin;
				} else {
					Api::Core()->Error()->Type()->Exception()->Trigger( 'Plugin-Manager: '.$Reflection->getName().' is not a plugin!', $Plugin->GetLocation(), $Reflection->getStartLine() );
				}
			} catch( \Exception $Exception  ) {
				Api::Core()->Error()->Type()->Exception()->Trigger( $Exception->getMessage(), $Exception->getFile(), $Exception->getLine(), $Exception->getTraceAsString() );
			}
		}
	}


	/**
	 * @param string $HookName
	 * @param null|string $PluginName
	 *
	 * @return Hook
	 */
	private function RepositorySearch( $HookName, $PluginName = null ) {
		if( $PluginName === null ) {
			// Select first available plugin
			foreach( $this->Repository as $Plugin ) {
				$Reflection = new \ReflectionObject( $Plugin );
				if( $Reflection->implementsInterface( 'MOC\\Plugin\\Hook\\'.$HookName ) ) {
					return $Plugin;
				}
			}
		} else {
			// Use specific plugin
			if( isset( $this->Repository[$PluginName] ) ) {
				return $this->Repository[$PluginName];
			}
		}
		Api::Core()->Error()->Type()->Exception()->Trigger( 'Plugin-Manager: Missing '.$HookName.' plugin!', __FILE__, __LINE__ );
		return false;
	}


	/**
	 * Exposed Hook
	 *
	 * @param null|string $PluginName
	 *
	 * @return \MOC\Plugin\Hook\VideoPlayer
	 */
	public function VideoPlayer( $PluginName = null ) {
		return $this->RepositorySearch( 'VideoPlayer', $PluginName );
	}

}
