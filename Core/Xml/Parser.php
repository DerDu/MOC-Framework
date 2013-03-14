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
 * Parser
 * 06.01.2013 14:37
 */
namespace MOC\Core\Xml;
use MOC\Api;
use MOC\Generic\Device\Core;

/**
 *
 */
class Parser implements Core {
	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Parser
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Parser();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}


	/** @var Tokenizer $Tokenizer */
	private $Tokenizer = null;
	/** @var array $Stack */
	private $Stack = array();
	/** @var null|Node $Result */
	private $Result = null;

	private $PatternComment = '!(?<=\!--).*?(?=//--)!is';
	private $PatternCDATA = '!(?<=\!\[CDATA\[).*?(?=\]\])!is';

	/**
	 * @param Tokenizer $Tokenizer
	 *
	 * @return Parser
	 */
	public function Setup( Tokenizer $Tokenizer ) {
		$this->Stack = array();
		$this->Tokenizer = $Tokenizer;
		$this->Result = null;
		$this->Parse();
		return $this;
	}

	private function Parse() {
		/** @var Token $Token */
		foreach( (array)$this->Tokenizer->GetResult() as $Token ) {
			// Convert Token to Node
			$Node = Node::InterfaceInstance()->Setup( $Token );
			// Handle Token by Type
			if( $Token->isOpenTag() ) {
				// Set Parent Type to Structure
				if( !empty( $this->Stack ) ) {
					$Parent = array_pop( $this->Stack );
					$Parent->SetType( $Parent::TYPE_STRUCTURE );
					array_push( $this->Stack, $Parent );
				}
				// Add Node to Stack
				array_push( $this->Stack, $Node );
			} elseif( $Token->isCloseTag() ) {
				// Get Parent (OpenTag)
				/** @var Node $Parent */
				$Parent = array_pop( $this->Stack );
				// Handle Close by Type
				switch( $Parent->GetType() ) {
					case $Parent::TYPE_CONTENT : {
						// Get Content
						$LengthName = strlen( $Parent->GetName() ) +1;
						$LengthAttribute = strlen( $Parent->GetAttributeString() ) +1;
						$LengthAttribute = ( $LengthAttribute == 1 ? 0 : $LengthAttribute );
						$Parent->SetContent(
							substr(
								$this->Tokenizer->GetContent(),

								$Parent->GetPosition()
									+ $LengthName
									+ $LengthAttribute,

								( $Token->GetPosition() - $Parent->GetPosition() )
									- ( $LengthName +1 )
									- ( $LengthAttribute )
							)
						);
						// Do Parent Close
						$Ancestor = array_pop( $this->Stack );
						$Ancestor->AddChild( $Parent );
						array_push( $this->Stack, $Ancestor );
						break;
					}
					case $Parent::TYPE_STRUCTURE : {
						// Set Ancestor <-> Parent Relation
						/** @var Node $Ancestor */
						$Ancestor = array_pop( $this->Stack );
						if( is_object( $Ancestor ) ) {
							// Do Parent Close
							$Ancestor->AddChild( $Parent );
							array_push( $this->Stack, $Ancestor );
						} else {
							// No Ancestor -> Parent = Root
							array_push( $this->Stack, $Parent );
						}
						break;
					}
					case $Parent::TYPE_CDATA : {
						// Set Ancestor <-> Parent Relation
						/** @var Node $Ancestor */
						$Ancestor = array_pop( $this->Stack );
						// Do Parent Close
						$Ancestor->AddChild( $Parent );
						array_push( $this->Stack, $Ancestor );
						break;
					}
				}
			} elseif( $Token->isShortTag() ) {
				// Set Ancestor <-> Node Relation
				/** @var Node $Parent */
				$Ancestor = array_pop( $this->Stack );
				$Ancestor->SetType( $Ancestor::TYPE_STRUCTURE );
				// Do Node Close
				$Ancestor->AddChild( $Node );
				array_push( $this->Stack, $Ancestor );
			} elseif( $Token->isCDATATag() ) {
				// Set Parent Type/Content
				/** @var Node $Parent */
				$Parent = array_pop( $this->Stack );
				$Parent->SetType( $Parent::TYPE_CDATA );
				$Parent->SetContent( $Node->GetName() );
				$this->DecodeCDATA( $Parent );
				// Do Node Close
				array_push( $this->Stack, $Parent );
			} elseif( $Token->isCommentTag() ) {
				// Set Parent Type/Content
				/** @var Node $Parent */
				$Parent = array_pop( $this->Stack );
				$Node->SetType( $Node::TYPE_COMMENT );
				$Node->SetContent( $Node->GetName() );
				$Node->SetName( '__COMMENT__' );
				$this->DecodeComment( $Node );
				// Do Node Close
				$Parent->AddChild( $Node );
				array_push( $this->Stack, $Parent );
			}
		}
		// Set parsed Stack as Result
		$this->Result = array_pop( $this->Stack );
	}

	/**
	 * @return Node|null
	 */
	public function GetResult() {
		return $this->Result;
	}

	/**
	 * @param Node $Node
	 */
	private function DecodeCDATA( Node &$Node ) {
		$Match = array();
		preg_match( $this->PatternCDATA, $Node->GetContent(), $Match );
		$Node->SetContent( $this->DecodeBase64( $Match[0] ) );
	}

	/**
	 * @param Node $Node
	 */
	private function DecodeComment( Node &$Node ) {
		$Match = array();
		preg_match( $this->PatternComment, $Node->GetContent(), $Match );
		$Node->SetContent( trim( $this->DecodeBase64( $Match[0] ) ) );
	}

	/**
	 * @param $Content
	 *
	 * @return string
	 */
	private function DecodeBase64( $Content ) {
		return base64_decode( $Content );
	}
}
