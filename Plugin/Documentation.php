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
 * Documentation
 * 19.02.2013 08:23
 */
namespace MOC\Plugin;
use MOC\Api;
use MOC\Generic\Device\Plugin;
use Nette\Config\Adapters\NeonAdapter;

/**
 *
 */
class Documentation implements Plugin {
	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ )
			->Build()->Clearance( '19.02.2013 08:26', 'Dev' )
			->Fix()->BugFix( '19.02.2013 11:10', 'Changed Config' )
		;
	}

	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending()
			->Package( '\MOC\Extension\ApiGen\Instance', Api::Core()->Version() );
	}

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return Documentation
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		Api::Extension()->ApiGen()->Create();
		return new Documentation();
	}

	/**
	 *
	 */
	public function Create() {

		set_time_limit(120);

		$Source = Api::Module()->Drive()->Directory()->Open( __DIR__.'/../' );
		$Destination = Api::Module()->Drive()->Directory()->Open( __DIR__.'/Documentation/Content' );
		$Configuration = Api::Module()->Drive()->File()->Open( __DIR__.'/Documentation/Config.neon' );

		$Config = array(
			// Source file or directory to parse
			'source' => $Source->GetLocation(),
			// Directory where to save the generated documentation
			'destination' => $Destination->GetLocation(),
			// List of allowed file extensions
			'extensions' => array( 'php' ),
			// Mask to exclude file or directory from processing
			'exclude' => '*/Documentation/Content/*,*/.idea/*,*/.git/*,*/#Trash/*,*/Data/*,*/Library/*,*/3rdParty/*',
			// Don't generate documentation for classes from file or directory with this mask
			//'skipDocPath' => '',
			// Don't generate documentation for classes with this name prefix
			//'skipDocPrefix' => '',
			// Character set of source files
			'charset' => 'auto',
			// Main project name prefix
			'main' => 'MOC',
			// Title of generated documentation
			'title' => '',
			// Documentation base URL
			//'baseUrl' => '',
			// Google Custom Search ID
			//'googleCseId' => '',
			// Google Custom Search label
			//'googleCseLabel' => '',
			// Google Analytics tracking code
			//'googleAnalytics' => '',
			// Template config file
			//'templateConfig' =>  './templates/default/config.neon',
			// Grouping of classes
			'groups' => 'auto',
			// List of allowed HTML tags in documentation
			'allowedHtml' => array( 'b', 'i', 'a', 'ul', 'ol', 'li', 'p', 'br', 'var', 'samp', 'kbd', 'tt' ),
			// Element types for search input autocomplete
			'autocomplete' => array( 'classes', 'constants', 'functions' ),
			// Generate documentation for methods and properties with given access level
			'accessLevels' => array( 'public', 'protected', 'private' ),
			// Generate documentation for elements marked as internal and display internal documentation parts
			'internal' =>  true,
			// Generate documentation for PHP internal classes
			'php' =>  true,
			// Generate tree view of classes, interfaces and exceptions
			'tree' =>  true,
			// Generate documentation for deprecated classes, methods, properties and constants
			'deprecated' =>  true,
			// Generate documentation of tasks
			'todo' =>  true,
			// Generate highlighted source code files
			'sourceCode' =>  true,
			// Add a link to download documentation as a ZIP archive
			'download' => true,
			// Save a checkstyle report of poorly documented elements into a file
			'report' => '',
			// Wipe out the destination directory first
			'wipeout' => true,
			// Don't display scanning and generating messages
			'quiet' => true,
			// Display progressbars
			'progressbar' => false,
			// Use colors
			'colors' => false,
			// Check for update
			'updateCheck' => false,
			// Display additional information in case of an error
			'debug' => false
		);

		$Neon = new NeonAdapter();

		$Configuration->Write( $Neon->dump( $Config ) );

		$_SERVER['argv'] = array(
			'DUMMY-SHELL-ARGS',
			'--config', $Configuration->GetLocation()
		);

		include( __DIR__.'/../Extension/ApiGen/3rdParty/apigen.php' );
	}
}
