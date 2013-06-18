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
 * Repository
 * 10.06.2013 11:56
 */
namespace MOC\Plugin;
use MOC\Api;
use MOC\Generic\Common;

/**
 *
 */
class Repository implements Common {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		Api::Core()->Changelog();
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		Api::Core()->Depending();
	}

	/** @var Repository $Singleton */
	private static $Singleton = null;

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Repository
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		if( self::$Singleton === null ) {
			self::$Singleton = new Repository();
			self::$Singleton->LoadRepository();
		} return self::$Singleton;
	}

	/** @var Shared[] $Repository */
	private $Repository = array();
	/** @var Shared[] $Helper */
	private $Helper = array(
		'mocStyleSheetHelper',
		'mocJavaScriptHelper'
	);

	private function LoadRepository() {
		$Repository = Api::Module()->Drive()->Directory()->Open( __DIR__.DIRECTORY_SEPARATOR.'Repository' )->FileList();

		// HelperPlugins
		foreach( $this->Helper as $Plugin ) {
			$Reflection = new \ReflectionClass( 'MOC\\Plugin\\Repository\\'.$Plugin );
			$Plugin = $Reflection->newInstance();
			$this->Repository[$Reflection->getParentClass()->getName()][$Reflection->getShortName()] = $Plugin;
		}

		foreach( $Repository as $Plugin ) {
			try {
				$Reflection = new \ReflectionClass( 'MOC\\Plugin\\Repository\\'.$Plugin->GetName() );
				if( is_object( $Reflection->getParentClass() )
					&& is_object( $Reflection->getParentClass()->getParentClass() )
					&& is_object( $Reflection->getParentClass()->getParentClass()->getParentClass() )
					&& $Reflection->getParentClass()->getParentClass()->getParentClass()->getName() == 'MOC\\Plugin\\Shared'
				) {

					/** @var Shared $Plugin */
					$Plugin = $Reflection->newInstance();
					if( !isset( $this->Repository[$Reflection->getParentClass()->getName()] )
						|| !isset( $this->Repository[$Reflection->getParentClass()->getName()][$Reflection->getShortName()] )
					) {
						$this->Repository[$Reflection->getParentClass()->getName()][$Reflection->getShortName()] = $Plugin;
						$Plugin->PluginLoader();
					}
				} else {
					Api::Core()->Error()->Type()->Exception()->Trigger( 'Plugin-Repository: '.$Reflection->getName().' is not a plugin!', $Plugin->GetLocation(), $Reflection->getStartLine() );
				}
			} catch( \Exception $Exception  ) {
				Api::Core()->Error()->Type()->Exception()->Trigger( $Exception->getMessage(), $Exception->getFile(), $Exception->getLine(), $Exception->getTraceAsString() );
			}
		}
	}

	/**
	 * @param Shared $Shared
	 * @param null|string $PluginName - Name of specific plugin to use
	 *
	 * @return Shared|null
	 */
	public function Execute( Shared $Shared, $PluginName = null ) {
		$SharedReflection = new \ReflectionObject( $Shared );
		if( $SharedReflection->getNamespaceName() != 'MOC\\Plugin\\Shared' ) {
			Api::Core()->Error()->Type()->Exception()->Trigger( 'Plugin-Repository: '.$SharedReflection->getName().' is not a valid plugin configuration!', __FILE__, __LINE__ );
		}
		$SharedPropertyList = $SharedReflection->getProperties();
		$PluginGateway = 'MOC\\Plugin\\Gateway\\'.$SharedReflection->getShortName();
		foreach( $this->Repository[$PluginGateway] as $Plugin ) {
			/** @var Shared $Plugin  */
			if( $PluginName !== null && $PluginName != $Plugin->PluginName() ) {
				continue;
			}
			/** @var Shared $Prospect */
			$Prospect = clone $Plugin;
			foreach( $SharedPropertyList as $Property ) {
				$Prospect->{$Property->getName()}( $Shared->{$Property->getName()}() );
			}
			if( $Prospect->PluginCapable() ) {
				return $Prospect;
			}
		}
		if( $PluginName !== null ) {
			Api::Core()->Error()->Type()->Exception()->Trigger( 'Plugin-Repository: '.$PluginName.' is either not available or not a capable plugin!', __FILE__, __LINE__ );
		} else {
			Api::Core()->Error()->Type()->Exception()->Trigger( 'Plugin-Repository: Missing capable '.$PluginGateway.' plugin!', __FILE__, __LINE__ );
		}
		return null;
	}
}
