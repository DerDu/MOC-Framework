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
 * Hook
 * 05.06.2013 13:56
 */
namespace MOC\Plugin;
/**
 *
 */
abstract class Hook {
	/**
	 * This method is used to setup your plugin
	 *
	 * - called only once
	 *
	 * @return Hook
	 */
	abstract public function HookLoader();

	/**
	 * This method is used to determine if the plugin can handle the required task
	 *
	 * @return bool
	 */
	abstract public function HookCapable();

	/**
	 * @return string
	 */
	abstract public function HookExecute();

	/**
	 * @var array
	 */
	private $Setting = array();

	/**
	 * Set a Custom Property
	 *
	 * @param string $Property
	 * @param mixed $Value
	 *
	 * @return $this
	 */
	final protected function HookConfigSet( $Property, $Value ) {
		$this->Setting[$Property] = $Value;
		return $this;
	}

	/**
	 * Get a Custom Property
	 *
	 * @param string $Property
	 *
	 * @return mixed
	 */
	final protected function HookConfigGet( $Property ) {
		if( isset( $this->Setting[$Property] ) ) {
			return $this->Setting[$Property];
		}
		return null;
	}
}
