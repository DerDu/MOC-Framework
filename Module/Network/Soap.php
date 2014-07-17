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
 * Soap
 * 27.06.2013 15:01
 */
namespace MOC\Module\Network;
use MOC\Api;
use MOC\Core\Xml\Node;
use MOC\Generic\Device\Module;
use MOC\Module\Drive\File;
use MOC\Module\Network\Soap\Binding;
use MOC\Module\Network\Soap\Service;
use MOC\Module\Network\Soap\Api as SoapApi;

use Type\Login;
use Type\GetClaimById;
use Type\GetProducts;

/**
 *
 */
class Soap implements Module {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog();
	}

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
	 * @return Soap
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new Soap();
	}

	/** @var string $SoapService */
	private $SoapService = '';
	/** @var \SoapClient $SoapClient */
	public $SoapClient = null;
	/** @var SoapApi $SoapApi */
	private $SoapApi = null;

	public function Call() {
		return $this->SoapApi;
	}

	/**
	 * @return Soap
	 */
	public function Connect() {

		$OptionList = array(
			'trace' => 1,
			'exceptions' => false,
			'cache_wsdl' => WSDL_CACHE_NONE,
			//'cache_wsdl' => WSDL_CACHE_BOTH
		);

		if( Api::Core()->Proxy()->IsDefined() ) {
			$Proxy = array();
			$Proxy['proxy_host'] = Api::Core()->Proxy()->GetServer()->Host();
			$Proxy['proxy_port'] = Api::Core()->Proxy()->GetServer()->Port();
			if( null != Api::Core()->Proxy()->GetCredentials() ) {
				$Proxy['proxy_login'] = Api::Core()->Proxy()->GetCredentials()->Username();
				$Proxy['proxy_password'] = Api::Core()->Proxy()->GetCredentials()->Password();

			}
			$OptionList = array_merge( $OptionList, $Proxy );
		}

		$this->SoapClient = new \SoapClient(
			//$this->WsdlFile->GetUrl()
			$this->SoapService
			, $OptionList
		);

		return $this;
	}

	public function Read() {

		$WsdlTypes = $this->Wsdl->GetChild( '!([a-z:]+)?types!is', null, null, true, true );

		$Cache = Api::Core()->Cache()->Group( __METHOD__ )->Identifier( sha1( $this->Wsdl->Code() ) )->Extension('php')->Timeout( 604800 );
		$CacheContent = '<?php namespace Type;'."\n";

		if( false == ( $File = $Cache->Get() ) ) {

			$WsdlSchemaIndex = 0;
			while( false != ( $WsdlSchema = $WsdlTypes->GetChild( '!([a-z:]+)?schema!is', null, $WsdlSchemaIndex++, true, true ) ) ) {

				/**
				 * simpleType
				 */
				$WsdlSchemaSimpleIndex = 0;
				/** @var Node[] $WsdlSchemaSimpleDone */
				$WsdlSchemaSimpleDone = array();
				while( false != ( $WsdlSchemaSimple = $WsdlSchema->GetChild('!([a-z:]+)?simpleType!is', null, $WsdlSchemaSimpleIndex++, false, true ) ) ) {

					$Restriction = $WsdlSchemaSimple->GetChild('!([a-z:]+)?restriction!is', null, null, false, true );

					if( $Restriction->GetChildListCount() == 0 ) { continue; }

					if( false != ( $Pattern = $Restriction->GetChild('!([a-z:]+)?pattern!is', null, null, false, true ) ) ) {
						$Template = Api::Module()->Template();
						$Engine = $Template->Engine();
						$Engine->Draft(
							$Template->Draft()->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Simple/Pattern.html' ) ) )
							->Variable( $Template->Variable()->Identifier( 'ClassName' )->Content( $WsdlSchemaSimple->GetAttribute('name') ) )
							->Variable( $Template->Variable()->Identifier( 'Pattern' )->Content( $Pattern->GetAttribute('value') ) )
						;
						$PhpCode = $Engine->Content( true );
					} else {
						$Template = Api::Module()->Template();
						$Engine = $Template->Engine();
						$Engine->Draft(
							$Template->Draft()->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Simple/Enumeration.html' ) ) )
							->Variable( $Template->Variable()->Identifier( 'ClassName' )->Content( $WsdlSchemaSimple->GetAttribute('name') ) );
						$EnumerationIndex = 0;
						while( false != ( $Enumeration = $Restriction->GetChild('!([a-z:]+)?enumeration!is', null, $EnumerationIndex++, false, true ) ) ) {
							$Method = preg_replace( '!([A-Z])!s', ' ${1}', $Enumeration->GetAttribute('value') );
							$Method = preg_replace( '![^\s\w\d]!is', '', $Method );
							$Method = ucwords( $Method );
							$Method = preg_replace( '![\s]!is', '', $Method );
							$Engine->Import(
								$Template->Import()->Identifier('Method')->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Simple/EnumerationMethod.html' ) ) )
								->Variable( $Template->Variable()->Identifier( 'MethodName' )->Content( $Method ) )
								->Variable( $Template->Variable()->Identifier( 'MethodValue' )->Content( $Enumeration->GetAttribute('value') ) )
							;
						}
						$PhpCode = $Engine->Content( true );
					}
					$CacheContent .= $PhpCode;
					$WsdlSchemaSimpleDone[] = $WsdlSchemaSimple;
				}
				// Clean WSDL
				array_walk( $WsdlSchemaSimpleDone, create_function('$Node','$Node->GetParent()->RemoveChild( $Node );') );

				/**
				 * complexType
				 */
				$WsdlSchemaComplexIndex = 0;
				/** @var Node[] $WsdlSchemaComplexDone */
				$WsdlSchemaComplexDone = array();
				while( false != ( $WsdlSchemaComplex = $WsdlSchema->GetChild('!([a-z:]+)?complexType!is', null, $WsdlSchemaComplexIndex++, false, true ) ) ) {

					$Sequence = $WsdlSchemaComplex->GetChild('!([a-z:]+)?sequence!is', null, null, false, true );
					$ComplexContent = $WsdlSchemaComplex->GetChild('!([a-z:]+)?complexContent!is', null, null, false, true );

					if( $Sequence != false ) {

						$Template = Api::Module()->Template();
						$Engine = $Template->Engine();
						$Engine->Draft(
							$Template->Draft()->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Complex/Class.html' ) ) )
							->Variable( $Template->Variable()->Identifier( 'ClassName' )->Content( $WsdlSchemaComplex->GetAttribute('name') ) );

						$ElementIndex = 0;
						while( false != ( $Element = $Sequence->GetChild('!([a-z:]+)?element!is', null, $ElementIndex++, false, true ) ) ) {
							$Method = preg_replace( '!([A-Z])!s', ' ${1}', $Element->GetAttribute('name') );
							$Method = preg_replace( '![^\s\w\d]!is', '', $Method );
							$Method = ucwords( $Method );
							$Method = preg_replace( '![\s]!is', '', $Method );
							$Engine->Import(
								$Template->Import()->Identifier('Method')->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Complex/Element.html' ) ) )
								->Variable( $Template->Variable()->Identifier( 'MethodName' )->Content( $Method ) )
							;
							// TypeHint
							if( $this->getNamespace( $Element->GetName() ) == $this->getNamespace( $Element->GetAttribute('type') ) ) {
								$Engine->Variable( $Template->Variable()->Identifier( 'ParameterType' )->Content('') );
							} else {
								$Engine->Variable( $Template->Variable()->Identifier( 'ParameterType' )->Content( $this->getName( $Element->GetAttribute('type') ) ) );
							}
							// Nillable
							if( $Element->GetAttribute('nillable') == 'true' ) {
								$Engine->Variable( $Template->Variable()->Identifier( 'Nillable' )->Content('= null') );
							} else {
								$Engine->Variable( $Template->Variable()->Identifier( 'Nillable' )->Content( '' ) );
							}
						}

					}

					if( $ComplexContent != false ) {

						$ExtensionIndex = 0;
						while( false != ( $Extension = $ComplexContent->GetChild('!([a-z:]+)?extension!is', null, $ExtensionIndex++, false, true ) ) ) {

							$Template = Api::Module()->Template();
							$Engine = $Template->Engine();
							$Engine->Draft(
								$Template->Draft()->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Complex/Extension.html' ) ) )
								->Variable( $Template->Variable()->Identifier( 'ClassName' )->Content( $WsdlSchemaComplex->GetAttribute('name') ) )
								->Variable( $Template->Variable()->Identifier( 'Extension' )->Content( $this->getName( $Extension->GetAttribute( 'base' ) ) ) );

							$Sequence = $Extension->GetChild('!([a-z:]+)?sequence!is', null, null, false, true );
							if( $Sequence != false ) {

								$ElementIndex = 0;
								while( false != ( $Element = $Sequence->GetChild('!([a-z:]+)?element!is', null, $ElementIndex++, false, true ) ) ) {
									$Method = preg_replace( '!([A-Z])!s', ' ${1}', $Element->GetAttribute('name') );
									$Method = preg_replace( '![^\s\w\d]!is', '', $Method );
									$Method = ucwords( $Method );
									$Method = preg_replace( '![\s]!is', '', $Method );
									$Engine->Import(
										$Template->Import()->Identifier('Method')->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Complex/Element.html' ) ) )
										->Variable( $Template->Variable()->Identifier( 'MethodName' )->Content( $Method ) )
									;
									// TypeHint
									if( $this->getNamespace( $Element->GetName() ) == $this->getNamespace( $Element->GetAttribute('type') ) ) {
										$Engine->Variable( $Template->Variable()->Identifier( 'ParameterType' )->Content('') );
									} else {
										$Engine->Variable( $Template->Variable()->Identifier( 'ParameterType' )->Content( $this->getName( $Element->GetAttribute('type') ) ) );
									}
									// Nillable
									if( $Element->GetAttribute('nillable') == 'true' ) {
										$Engine->Variable( $Template->Variable()->Identifier( 'Nillable' )->Content('= null') );
									} else {
										$Engine->Variable( $Template->Variable()->Identifier( 'Nillable' )->Content( '' ) );
									}
								}

							}
						}
					}
					$PhpCode = $Engine->Content( true );
					$CacheContent .= $PhpCode;
					$WsdlSchemaComplexDone[] = $WsdlSchemaComplex;
				}
				// Clean WSDL
				array_walk( $WsdlSchemaComplexDone, create_function('$Node','$Node->GetParent()->RemoveChild( $Node );') );

				/**
				 * element WITH complexType
				 */
				$WsdlSchemaElementIndex = 0;
				/** @var Node[] $WsdlSchemaElementDone */
				$WsdlSchemaElementDone = array();
				while( false != ( $WsdlSchemaElement = $WsdlSchema->GetChild('!([a-z:]+)?element!is', null, $WsdlSchemaElementIndex++, false, true ) ) ) {

					if( $WsdlSchemaElement->GetChildListCount() > 0 ) {

						$Template = Api::Module()->Template();
						$Engine = $Template->Engine();
						$Engine->Draft(
							$Template->Draft()->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Element/Class.html' ) ) )
							->Variable( $Template->Variable()->Identifier( 'ClassName' )->Content( $WsdlSchemaElement->GetAttribute('name') ) );

						$ComplexType = $WsdlSchemaElement->GetChild('!([a-z:]+)?complexType!is', null, null, false, true );
						$Sequence = $ComplexType->GetChild('!([a-z:]+)?sequence!is', null, null, false, true );

						if( $Sequence != false ) {
							$ElementIndex = 0;
							while( false != ( $Element = $Sequence->GetChild('!([a-z:]+)?element!is', null, $ElementIndex++, false, true ) ) ) {
								$Method = preg_replace( '!([A-Z])!s', ' ${1}', $Element->GetAttribute('name') );
								$Method = preg_replace( '![^\s\w\d]!is', '', $Method );
								$Method = ucwords( $Method );
								$Method = preg_replace( '![\s]!is', '', $Method );
								$Engine->Import(
									$Template->Import()->Identifier('Method')->File( Api::Module()->Drive()->File()->Open( __DIR__.'/Soap/Type/Element/Element.html' ) ) )
									->Variable( $Template->Variable()->Identifier( 'MethodName' )->Content( $Method ) )
								;
								// TypeHint
								if( $this->getNamespace( $Element->GetName() ) == $this->getNamespace( $Element->GetAttribute('type') ) ) {
									$Engine->Variable( $Template->Variable()->Identifier( 'ParameterType' )->Content('') );
								} else {
									$Engine->Variable( $Template->Variable()->Identifier( 'ParameterType' )->Content( $this->getName( $Element->GetAttribute('type') ) ) );
								}
								// Nillable
								if( $Element->GetAttribute('nillable') == 'true' ) {
									$Engine->Variable( $Template->Variable()->Identifier( 'Nillable' )->Content('= null') );
								} else {
									$Engine->Variable( $Template->Variable()->Identifier( 'Nillable' )->Content( '' ) );
								}
							}
						}

						$PhpCode = $Engine->Content( true );
						$CacheContent .= $PhpCode;
						$WsdlSchemaElementDone[] = $WsdlSchemaElement;
					}
				}
				array_walk( $WsdlSchemaElementDone, create_function('$Node','$Node->GetParent()->RemoveChild( $Node );') );
			}


			$Cache->Set( $CacheContent );
			$File = $Cache->Get();
		}

		/** @noinspection PhpIncludeInspection */
		require_once( $File->Location() );

		// Interface
		// WSDL 1.0
		if( false != ( $Node = $this->Wsdl->GetChild('!:?portType!is', null, null, false, true ) ) ) {
			$this->SoapApi = new SoapApi();
			$this->SoapApi->Definition( $Node, $this->Wsdl );
		}
		// WSDL 2.0
		if( false != ( $Node = $this->Wsdl->GetChild('!:?interface!is', null, null, false, true ) ) ) {
			$this->SoapApi = new SoapApi();
			$this->SoapApi->Definition( $Node, $this->Wsdl );
		}

/*
		// Service
		$this->SoapService = new Service();
		$this->SoapService->Definition(
			$this->Wsdl->GetChild('!:?service!is', null, null, false, true )
		);

		// Binding
		$EndpointList = $this->SoapService->GetEndpointList();
		foreach( $EndpointList as $Endpoint ) {
			$BindingIndex = 0;
			while( false != ( $Binding = $this->Wsdl->GetChild('!:?binding!is', null, $BindingIndex++, false, true ) ) ) {
				if( $Binding->GetAttribute('name') == $Endpoint->GetBinding() ) {
					$Instance = new Binding();
					$Instance->Definition( $Binding );
					$this->SoapBinding[$Endpoint->GetBinding()] = $Instance;
					break;
				}
			}
		}


/*
 */
//		$this->debugNode( $this->Wsdl );
//		print '<pre>';
	}

	/**
	 * @param string $Name
	 *
	 * @return string
	 */
	private function getNamespace( $Name ) {
		$Name = explode( ':', $Name );
		if( count( $Name ) > 1 ) {
			return array_shift( $Name );
		}
		return '';
	}

	/**
	 * @param string $Name
	 *
	 * @return string
	 */
	private function getName( $Name ) {
		$Name = explode( ':', $Name );
		if( count( $Name ) > 1 ) {
			return array_pop( $Name );
		}
		return current( $Name );
	}

	/** @var null|Node $Wsdl */
	private $Wsdl = null;
	/** @var null|File $WsdlFile */
	private $WsdlFile = null;

	/**
	 * Get Service-Files
	 *
	 * @param $Service
	 * @return Soap
	 */
	public function Get( $Service ) {
		$this->SoapService = $Service;
		// Cache WSDL-Service-Definition
		$Cache = Api::Core()->Cache()->Group( __METHOD__ )->Identifier( $Service )->Timeout( 604800 )->Extension('xml');
		if( false != ( $Wsdl = $Cache->Get() ) ) {
			$this->WsdlFile = Api::Module()->Drive()->File()->Open( $Wsdl->Location() );
			$this->Wsdl = Api::Core()->Xml()->Parse( $Wsdl->Content() );
			return $this;
		}

		// Get <Wsdl>
		$Wsdl = $this->getFile( $Service );
		// Parse <Wsdl>
		$this->Wsdl = Api::Core()->Xml()->Parse( $Wsdl->Read() );

		// Import additional files
		while( false != ( $ImportList = $this->getImport() ) ) {
			$this->runImport( $ImportList );
		}

		// Combine possible multiple <Types>, <Service>
		while( false != ( $Source = $this->Wsdl->GetChild('!:?types!is', null, 1, false, true ) ) ) {
			$Target = $this->Wsdl->GetChild('!:?types!is', null, 0, false, true );
			$ChildList = $Source->GetChildList();
			foreach( $ChildList as $Child ) {
				$Target->AddChild( $Child );
			}
			$Source->GetParent()->RemoveChild( $Source );
		}
		while( false != ( $Source = $this->Wsdl->GetChild('!:?service!is', null, 1, false, true ) ) ) {
			$Target = $this->Wsdl->GetChild('!:?service!is', null, 0, false, true );
			$ChildList = $Source->GetChildList();
			foreach( $ChildList as $Child ) {
				$Target->AddChild( $Child );
			}
			$Source->GetParent()->RemoveChild( $Source );
		}
		// Review possible multiple <Schema>
		if( false != $this->Wsdl->GetChild('!:?schema!is', null, 1, true, true ) ) {

			$Schema = $this->Wsdl->GetChild('!:?schema!is', null, 0, true, true );
			$Source = $Schema->GetChildList();
			foreach( $Source as $Child ) {
				$Schema->GetParent()->AddChild( $Child, $Schema );
			}
			$Schema->GetParent()->RemoveChild( $Schema );
		}
		print '<pre>';

		//$this->debugNode( $this->Wsdl );
		// Fix: Missing Parent-Child-Links -> ReParse XmlCode after Import, just in case ;-)
		$this->Wsdl = Api::Core()->Xml()->Parse( $this->Wsdl->Code() );

		// Fix: Remove http://schemas.microsoft.com/2003/10/Serialization
		$SchemaIndex = 0;
		while( false != ( $Schema = $this->Wsdl->GetChild('!:?schema!is', null, $SchemaIndex++, true, true ) ) ) {
			if( $Schema->GetAttribute( 'targetNamespace' ) == 'http://schemas.microsoft.com/2003/10/Serialization/' ) {
				$Schema->GetParent()->RemoveChild( $Schema );
			}
		}

		// Cache WSDL-Service-Definition
		$Cache->Set( $this->Wsdl->Code() );

		$this->WsdlFile = Api::Module()->Drive()->File()->Open( $Cache->Get()->Location() );

		return $this;
	}

	/**
	 * @param array $ImportList
	 */
	private function runImport( $ImportList ) {
		// Fix: Breaking circular reference
		static $RequireOnce = array();

		while( ! empty( $ImportList ) ) {
			/** @var array $ImportData */
			$ImportData = array_shift( $ImportList );
			/** @var string $ImportLocation */
			$ImportLocation = $ImportData[0];
			/** @var Node $Import */
			$Import = $ImportData[1];
			// Fix: Breaking circular reference
			if( in_array( $ImportLocation, $RequireOnce ) ) {
				$Import->GetParent()->RemoveChild( $Import );
			} else {
				// Import additional file
				$Wsdl = $this->getFile( $ImportLocation );
				$Wsdl = Api::Core()->Xml()->Parse( $Wsdl->Read() );
				if( preg_match( '!:?definitions!is', $Wsdl->GetName() ) ) {
					$Wsdl = $Wsdl->GetChildList();
					// Swap order for one-by-one injection
					krsort( $Wsdl );
				} else {
					$Wsdl = array( $Wsdl );
				}
				// Inject imported children
				foreach( $Wsdl as $Child ) {
					$Import->GetParent()->AddChild( $Child, $Import );
				}
				// Remove imported Tag
				$Import->GetParent()->RemoveChild( $Import );
				// Fix: Breaking circular reference
				array_push( $RequireOnce, $ImportLocation );
			}
		}
		// Fix: Missing Parent-Child-Links -> ReParse XmlCode after Import
		$this->Wsdl = Api::Core()->Xml()->Parse( $this->Wsdl->Code() );
	}

	/**
	 * @return array
	 */
	private function getImport() {
		$ImportIndex = 0;
		$ImportList = array();
		while( false != ( $Import = $this->Wsdl->GetChild('!:?import!is', null, $ImportIndex++, true, true ) ) ) {
			// Get Import-<Wsdl>
			$ImportLocation = $Import->GetAttributeList();
			if( isset( $ImportLocation['location'] ) ) {
				$ImportLocation = $ImportLocation['location'];
			} else if( isset( $ImportLocation['schemaLocation'] ) ) {
				$ImportLocation = $ImportLocation['schemaLocation'];
			}
			$ImportList[] = array( $ImportLocation, $Import );
		}
		if( empty( $ImportList ) ) {
			return false;
		}
		return $ImportList;
	}

	/**
	 * Download Service-File to Cache
	 *
	 * @param $Service
	 *
	 * @return File
	 */
	private function getFile( $Service ) {
		$Cache = Api::Core()->Cache()->Group( __METHOD__ )->Identifier( $Service )->Timeout( 3600 )->Extension('xml');
		if( false == ( $Wsdl = $Cache->Get() ) ) {
			$Cache->Set( Api::Module()->Network()->Http()->Get()->Request( $Service ) );
			$Wsdl = $Cache->Get();
		}
		$Wsdl = Api::Module()->Drive()->File()->Open( $Wsdl->Location() );
		return $Wsdl;
	}

	/**
	 * @param Node $Node
	 */
	private function debugNode( Node $Node ) {
		print '<pre><span style="color: silver;">';print htmlspecialchars( $Node->Code() );print '</span></pre>';
	}

	private function debugPhp( $String ) {
		highlight_string( $String );
	}
}
