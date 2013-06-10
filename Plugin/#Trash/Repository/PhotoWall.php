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
 * PhotoWall
 * 06.06.2013 13:51
 */
namespace MOC\Plugin\Repository;
use MOC\Api;
use MOC\Plugin\Hook;
/**
 *
 */
class PhotoWall extends Hook\Gallery {

	/**
	 * This method is used to setup your plugin
	 *
	 * - called only once
	 *
	 * @return \MOC\Plugin\Hook
	 */
	public function HookLoader() {
		// TODO: Implement HookLoader() method.
	}

	/**
	 * This method is used to determine if the plugin can handle the required task
	 *
	 * @return bool
	 */
	public function HookCapable() {
		return true;
	}

	/**
	 * @return void|mixed
	 */
	public function HookExecute() {

		$FileList = Api::Module()->Drive()->Directory()->Open( $this->configImageLocation() )->FileList( true );

		$Html = '<div style="margin: 5px; border: 1px dotted silver;">';

		foreach( $FileList as $File ) {
			if( in_array( $File->GetExtension(), $this->configExtensionList() ) ) {
				$Html .= '<div style="width: 100px; height: 100px; background: #ffffff url(\''.$File->GetUrl().'\') center no-repeat; float: left; margin: 5px; border: 1px dotted silver;"></div>';
			}
		}

		$Html .= '<div style="clear: both; float: none;"></div>';
		$Html .= '</div>';

		print $Html;
	}

}
