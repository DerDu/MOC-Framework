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
 * FlowPlayer
 * 05.06.2013 14:00
 */
namespace MOC\Plugin\Repository;
use MOC\Api;
use MOC\Plugin\Hook;
/**
 *
 */
class FlowPlayer1 extends Hook\VideoPlayer {

	/**
	 * This method is used to setup your plugin
	 *
	 * - called only once
	 *
	 * @return Hook
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
		// able to play the video?
		return in_array(
			Api::Module()->Drive()->File()->Open( $this->configSource() )->GetExtension(),
			array( 'flv', 'mp4', 'mov', 'm4v', 'f4v' )
		);
	}

	/**
	 * Called to run the video
	 *
	 * @return \MOC\Plugin\Hook\VideoPlayer
	 */
	public function Play() {

		static $PlayerId;
		$PlayerId++;

		$B = Api::Module()->Drive()->Directory()->Open( __DIR__.'/FlowPlayer/3rdParty/' );
		$C = Api::Module()->Drive()->Directory()->Open( Api::Core()->Drive()->Directory()->DirectoryCurrent() );

		$Script = '<script type="text/javascript" src="'.Api::Module()->Drive()->File()->Open(
			__DIR__.'/FlowPlayer/3rdParty/flowplayer-3.2.12.min.js'
		)->GetUrl().'"></script>';

		$Script .= '<a id="FlowPlayer'.$PlayerId.'" href="'.$this->configSource().'" style="display: block; width: '.$this->configWidth().'px; height:'.$this->configHeight().'px;"></a>'.
		'<script type="text/javascript">'.
			"flowplayer( 'FlowPlayer".$PlayerId."', '".$B->GetLocationRelative( $C ).'/flowplayer-3.2.16.swf'."' );".
		'</script>';

		print $Script;

		return $this;
	}

}
