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
 * Shared
 * 10.06.2013 13:16
 */
namespace MOC\Plugin;
use MOC\Api;
use MOC\Module\Drive\File;

/**
 *
 */
abstract class Shared {

	/**
	 * @param File $File
	 */
	final public function PluginJavaScript( File $File ) {
		/** @var Gateway\mocJavaScriptHelper $Plugin */
		$Plugin = Api::Plugin()->Load( Api::Plugin()->Get()->mocJavaScriptHelper() );
		$Plugin->Register( $File );
	}

	/**
	 * @return string
	 */
	final public function PluginName() {
		$Reflection = new \ReflectionObject( $this );
		return $Reflection->getShortName();
	}

	/**
	 * @return string
	 */
	final public function PluginGateway() {
		$Reflection = new \ReflectionObject( $this );
		return $Reflection->getParentClass()->getName();
	}

	/**
	 * This method is used to determine if the plugin can handle the required task
	 *
	 * @return bool
	 */
	public function PluginCapable() {
		return false;
	}

	/**
	 * This method is used to determine if the plugin can handle the required task
	 *
	 * @return bool
	 */
	public function PluginLoader() {

	}
}
