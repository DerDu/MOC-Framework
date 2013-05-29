### Modular - Object - Chaining
The easy way to OOP

> Simple, fast, extensible, unified interface to unlimited possibilities.
>
> Mind the task - forget the obstacles...

------------------------------------------------------------------------------------------------------------------------

### Usage

1. Get in the MOC

	```php
	require('MOC.php');
	```
2. Start your engine

	```php
	use MOC\Api;
	```
3. Put the pedal to the metal.

	```php
	var_dump(
		Api::Module()->Drive()->File()->Open('README.md')->Read()
	);
	```

------------------------------------------------------------------------------------------------------------------------


LICENSE (BSD)
Copyright (c) 2012, Gerd Christian Kunze
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

 * Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.

 * Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.

 * Neither the name of Gerd Christian Kunze nor the names of the
   contributors may be used to endorse or promote products derived from
   this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


------------------------------------------------------------------------------------------------------------------------

### Design

1. ##### MOC
	"The Api"
	- Init the library
	- Provide adapter access

	And MOC said: "Let there be ligh... err.. ```Api::``` !"

2. ##### Adapter (Prayer)
	Your Way to a Better Life
	- The starting point of the chain
	- Provide Core/Extension/Module/Plugin access

	... and MOC devided the ```::Core()``` from the ```::Module()``` ... and it was good

3. ##### Core (God)
	Contains secret internal operations ;-)
	- Error handling
	- Session handling
	- Cache handling
	- ...

	You should never use this one directly in your application

4. ##### Extension (The tree of life)
	Add power to the system
	- Defines a common interface for 3rd party applications

	And again: You should never use this one directly in your application

5. ##### Module (The tree of knowledge)
	Make the hole thing shiny
	- Add in the functionality
	- Defines a seamless interface to extensions

	You will need this one - and ONLY this one - for your application

6. ##### Plugin (The snake)
	Up to your imagination

	I had an idea but... never mind... -.-


------------------------------------------------------------------------------------------------------------------------

### Used 3rd Party Applications


#### apigen
Used to create the MOC-Documentation
- Project: <http://apigen.org>
- License: BSD

#### YUICompressor
Add Packer:Script/Style capability
- Project: <http://yui.github.com/yuicompressor>
- License: BSD

#### PHPExcel
Add Office:Document:Excel capability
- Project: <http://phpexcel.codeplex.com>
- License: LGPL

#### PHPWord
Add Office:Document:Word capability
- Project: <http://phpword.codeplex.com>
- License: LGPL

#### tFPDF
Add Office:Document:PDF capability
- Project: <http://fpdf.org/fr/script/script92.php>
- License: LGPL

#### PHPMailer
Add Office:Mail:Smtp capability
- Project: <http://sourceforge.net/projects/phpmailer>
- License: LGPL

#### PclZip
Add Packer:Zip capability
- Project: <http://www.phpconcept.net>
- License: LGPL

#### Flot
Add Office:Chart capability
- Project: <http://www.flotcharts.org>
- License: Copyright (see Project)
