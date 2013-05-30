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

### Linking 3rd party software to the MOC-Framework

Note:
- The term "3rd party software" will be substituted by "3PS" in the following section

To respect (most of) the licenses the 3PS author/owner may have chosen, this is achieved
by using it with a kind of "plugin" architecture. This is (unfortunately) necessary because of the (e.g.) GPLv3 license.

After weeks of research (if i am allowed to do this) i found this way to add in GPLv3 3PS and still let the MOC-Framework be under BSD as a whole.

> GPLv3 adds more clarity with regard to what constitutes a derivative work. For example, GPLv3 states that if the program
> is "specifically designed" to work with a GPL-governed library, then the library is considered part of the overall work
> and the entire application is governed by the GPL.
>
> However, if one could swap out the GPL library for another library (i.e., if the application wasn't
> "specifically designed" to work with the GPL library), then it's not part of the overall work and
> would not be governed by the license.
>
> Source: <http://www.ibm.com/developerworks/rational/library/edge/08/mar08/curran/>

Loaded with this argument i can clearly say: So it is!

- MOC-Framework is NOT "specifically designed" to work with a "specific" 3PS.
- The 3PS is an interchangeable "Extension" to the MOC-Framework
- The functionality of MOC is provided by a "Module" which COULD use a "Extension", but don't have to.

Example:

`Api::Module()->Office()->Document()->Pdf();`

- This Pdf-Module uses the Pdf-Extension which is powered by 3PS from "fPDF"
- You are able to exchange this Pdf-Extension with one powered by 3PS from "tcPdf"
- See? MOC-Framework is not "specifically designed" to work with "fPDF" ;-)


So there are three parts:

MOC-Framework (BSD)
- Provides an seamless API to the developer
Module (BSD)
- Provides the functionality to the API
	- Case 1: it contains the code itself
	- Case 2: it uses an Extension
Extension (BSD)
- Loads and initializes the 3PS
- Provides the 3PS functionality to the Module
- Contains (File-System):
	- Folder: "3rdParty" (<3PS-License>)
		- Contains the complete and unmodified (AS IS) 3rd party application (including source code, if any)
	- File: "Instance.php"
		- A minimal interface (BSD) to load/boot/close the 3PS

### Used 3rd party software


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

#### FlowPlayer
Add Office:Video capability
- Project: <http://flash.flowplayer.org>
- License: GPLv3
