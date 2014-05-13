<?php
/**
 * LICENSE (BSD)
 *
 * Copyright (c) 2014, Gerd Christian Kunze
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
 * Item
 * 13.01.2014 08:44
 */
namespace MOC\Module\Network\Feed\Generator;
use MOC\Api;

/**
 *
 */
class Item extends Constants {

	/** @var int $Type */
	private $Type = self::TYPE_RSS;

	/** @var string $Title */
	private $Title = '';
	/** @var string $Link */
	private $Link = '';
	/** @var string $Description */
	private $Description = '';

	/**
	 * @param int $Type
	 */
	function __construct( $Type ) {
		$this->Type = $Type;
	}

	/**
	 * @param $Value
	 *
	 * @return Item
	 */
	public function SetTitle( $Value ) {
		$this->Title = $Value;
		return $this;
	}

	/**
	 * @param $Value
	 *
	 * @return Item
	 */
	public function SetLink( $Value ) {
		$this->Link = $Value;
		return $this;
	}

	/**
	 * @param $Value
	 *
	 * @return Item
	 */
	public function SetDescription( $Value ) {
		$this->Description = $Value;
		return $this;
	}

	/**
	 * @return \MOC\Core\Xml\Node
	 */
	function GetItem() {
		switch( $this->Type ) {
			case self::TYPE_RSS:
			default: {
				$Node = Api::Core()->Xml()->Create( 'item' );
				$Node->AddChild( Api::Core()->Xml()->Create( 'title' )->SetContent( $this->Title ) );
				$Node->AddChild( Api::Core()->Xml()->Create( 'link' )->SetContent( $this->Link ) );
				$Node->AddChild( Api::Core()->Xml()->Create( 'description' )->SetContent( $this->Description ) );
			}
		}
		return $Node;
	}

}
