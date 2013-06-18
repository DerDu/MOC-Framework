### Modular - Object - Chaining
The easy way to OOP

> Simple, fast, extensible, unified interface to unlimited possibilities.
>
> * It empowers you to succeed office tasks like using excel / charts / video / word / mail / pdf / xml / archives / images / ...
> * Simplified database access to e.g. mysql / mssql / oracle / ...
> * Independent filesystem access and networking abilities e.g. http / ftp / proxy / ...
> * Powerful templating engine and much much more right at your fingertips available to your php application
>
> Not satisfied yet? Let me know whats missing, or integrate it yourself

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
	print Api::Module()->Drive()->File()->Open('README.md')->Read();
	```

Full API-Documentation visit http://derdu.github.io/MOC-Framework

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

### 3rd-Party Software (3PS) used by MOC-Framework

This can be achieved by two ways:

	* Extension (needs to be BSD-License compatible!)
	* Plugin (could be any FLOSS compatible license)

#### Used 3PS Extensions:

YUICompressor to add Packer:Script/Style capability
- Project: <http://yui.github.com/yuicompressor>
- License: BSD

PHPExcel to add Office:Document:Excel capability
- Project: <http://phpexcel.codeplex.com>
- License: LGPL

PHPWord to add Office:Document:Word capability
- Project: <http://phpword.codeplex.com>
- License: LGPL

tFPDF to add Office:Document:PDF capability
- Project: <http://fpdf.org/fr/script/script92.php>
- License: LGPL

PHPMailer to add Office:Mail:Smtp capability
- Project: <http://sourceforge.net/projects/phpmailer>
- License: LGPL

PclZip to add Packer:Zip capability
- Project: <http://www.phpconcept.net>
- License: LGPL

Flot to add Office:Chart capability
- Project: <http://www.flotcharts.org>
- License: Copyright (see Project)

#### Used 3PS Plugins:

apigen to create the MOC-Documentation
- Project: <http://apigen.org>
- License: BSD

FlowPlayer to add Office:Video capability
- Project: <http://flash.flowplayer.org>
- License: GPLv3
