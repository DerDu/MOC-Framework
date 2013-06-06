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
 * MOC
 * 29.08.2012 15:21
 */
namespace MOC;
/**
 *
 */
class Api {
	/**
	 * Setup / Startup-Procedure for MOC-System
	 *
	 * @static
	 * @return void
	 */
	public static function Setup() {
		// Register Auto-Loader
		spl_autoload_register( array(__CLASS__,'AutoLoader') );

		// Register Error-Handler
		Core\Error::InterfaceInstance()->Register()->Error()->SetHandler();
		// Register Exception-Handler
		Core\Error::InterfaceInstance()->Register()->Exception()->SetHandler();
		// Register Shutdown-Handler
		Core\Error::InterfaceInstance()->Register()->Shutdown()->SetHandler();
		// Set Reporting-Level
		Core\Error::InterfaceInstance()->Reporting()->Level( E_ALL )->Display( true )->Debug( true )->Apply();
	}

	/**
	 * Auto-Loader for MOC-System
	 *
	 * @static
	 *
	 * @param string $Class
	 *
	 * @return bool
	 * @noinspection PhpUnusedPrivateMethodInspection
	 */
	private static function AutoLoader( $Class ) {
		// Cut Root-Namespace
		$Class = preg_replace( '!^'.__NAMESPACE__.'!', '', $Class );
		// Correct & Trim DIRECTORY_SEPARATOR
		$Class = preg_replace( '![\\\/]+!', DIRECTORY_SEPARATOR, __DIR__.DIRECTORY_SEPARATOR.$Class.'.php' );
		if( false === ( $Class = realpath( $Class ) ) ) {
			// File not found
			return false;
		} else {
			/** @noinspection PhpIncludeInspection */
			require_once( $Class );
			return true;
		}
	}

	/**
	 * @return Adapter\Core
	 */
	public static function Core() {
		return Adapter\Core::InterfaceInstance();
	}
	/**
	 * @return Adapter\Extension
	 */
	public static function Extension() {
		return Adapter\Extension::InterfaceInstance();
	}
	/**
	 * @return Adapter\Module
	 */
	public static function Module() {
		return Adapter\Module::InterfaceInstance();
	}

	/**
	 * @param null $PluginName
	 *  [null] -> Select Default-Plugin
	 *  ['PluginName'] -> Select this plugin
	 *
	 * @return Adapter\Plugin
	 */
	public static function Plugin( $PluginName = null ) {
		$Adapter = Adapter\Plugin::InterfaceInstance();
		$Adapter->InterfaceSelectPlugin( $PluginName );
		return $Adapter;
	}
}

/**
 * Startup MOC-API
 */
Api::Setup();
