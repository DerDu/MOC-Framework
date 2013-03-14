# MOC-Framework
===============

### Modular - Object - Chaining
The easy way to OOP

### Purpose


### Design

#### MOC - "The Api"

- Init the Library
- Provide Adapter-Access

#### Adapter
Your window into a better world

#### Core
Contains secret internal operations ;-)

#### Extension
Add power to the system

#### Module
Make the hole thing shiny

#### Plugin
Up to your imagination


### Usage

1. Get in the MOC

	```php
	require('MOC.php');
	```
2. Start your Engine

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

### Used 3rd Party Applications
==========================

#### apigen
Used to create the MOC-Documentation
- License: BSD
- Project: http://apigen.org

#### YUICompressor
Add Packer:Script/Style capability
- License: BSD
- Project: http://yui.github.com/yuicompressor

#### PHPExcel
Add Office:Document:Excel capability
- License: LGPL
- Project: http://phpexcel.codeplex.com

#### PHPWord
Add Office:Document:Word capability
- License: LGPL
- Project: http://phpword.codeplex.com

#### tFPDF
Add Office:Document:PDF capability
- License: LGPL
- Project: http://fpdf.org/fr/script/script92.php

#### PHPMailer
Add Office:Mail:Smtp capability
- License: LGPL
- Project: http://sourceforge.net/projects/phpmailer

#### PclZip
Add Packer:Zip capability
- License: LGPL
- Project: http://www.phpconcept.net
