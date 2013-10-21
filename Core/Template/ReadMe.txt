Format
	- const = Named-Index
	- array = 'regex-search' => 'regex-format'
	- new( const ) | new( 'regex-search', 'regex-format' ) -> Format

	- GetSearchPattern() -> string
	- GetFormatPattern() -> string

Variable ${name}
	- name
	- value
	- Format
	- new( name, value, Format|null ) -> Variable
	- ApplyFormat( Format ) -> Variable

	- GetIdentifier() -> string
	- GetPayload() -> string

Import $(name)
	- name
	- Template
	- new( name, Template|null ) -> Import
	- ApplyTemplate( Template ) -> Variable

	- GetIdentifier() -> string
	- GetPayload() -> string

Complex $[name]
	- name
	- Template
	- new( name, Template|null ) -> Complex
	- ApplyEngine( Template ) -> Complex

	- GetIdentifier() -> string
	- GetPayload() -> string

Request $<name>
	- insert Api::Module()->Network()->Http()->Request()->Select(name)->Get()

Template
	- new( Template|null )
	- UseTemplate( Template ) -> Template

	- ApplyVariable( Variable ) -> Template
	- ApplyInclude( Import ) -> Template
	- ApplyComplex( Complex ) -> Template

	- GetResult() -> string

Template
	- content
	- new( file|null )
	- AssignFile( string ) -> Template
	- AssignContent( string ) -> Template

	- GetPayload()

Usage:
	- Load File
		$T1 = new Template( '../T1.html' );
	- New Engine
		$E1 = new Template( $T1 );
