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
 * FlashPlayer
 * 05.06.2013 16:00
 */
namespace MOC\Plugin\Repository;
use MOC\Api;
use MOC\Plugin\Hook;
/**
 *
 */
class FlashPlayer extends Hook implements Hook\VideoPlayer {

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
		// TODO: Implement HookCapable() method.
	}


	/** @var int $Width */
	private $Width = '';
	/** @var int $Height */
	private $Height = '';
	/**
	 * @param int $Width  px
	 * @param int $Height px
	 *
	 * @return Hook\VideoPlayer
	 */
	public function SetPlayerSize( $Width, $Height ) {
		$this->Width = $Width;
		$this->Height = $Height;
		return $this;
	}

	/** @var string $Source */
	private $Source = '';
	/**
	 * @param string $Movie
	 *
	 * @return Hook\VideoPlayer
	 */
	public function SetPlayerSource( $Movie ) {
		$this->Source = $Movie;
		return $this;
	}

	/**
	 * @return string
	 */
	public function ExecutePlayer() {

		static $PlayerId;
		$PlayerId++;

		print '
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$this->Width.'" height="'.$this->Height.'" id="FlashPlayer'.$PlayerId.'" align="middle">
		    <param name="movie" value="'.$this->Source.'"/>
		    <!--[if !IE]>-->
		    <object type="application/x-shockwave-flash" data="'.$this->Source.'" width="'.$this->Width.'" height="'.$this->Height.'">
		        <param name="movie" value="'.$this->Source.'"/>
		    <!--<![endif]-->
		        <a href="http://www.adobe.com/go/getflash">
		            <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/>
		        </a>
		    <!--[if !IE]>-->
		    </object>
		    <!--<![endif]-->
		</object>
		';
	}
}
