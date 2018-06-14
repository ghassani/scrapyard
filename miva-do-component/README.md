# MvDO Component v0.5

This module allows you to call any function from any compiled miva script binary in your template code.

With the release of Miva Empress v5.21, this module would no longer be needed as it is available natively.

## Usage

To use the module, make sure the mvdo item is assigned to the page in which you wish to use it on.

Then in your template code call the item with param set with the following pipe delimited arguments:

`
<mvt:item name="mvdo" param="argument1|argument2|argument3" />
`

**Argument 1)** The name of the variable containing the path to the compiled miva script binary (.mvc). You can use globally defined variables to core files sucha as _g.Module_Library_DB_.

**Argument 2)** A name of the variable to store the functions return value.

**Argument 3)** The function and function arguments to be called from the file specified in argument 1.

For example if we wanted to execute the database library function of Miva Merchant to load a product by code, we use the following markup:

`
<mvt:item name="mvdo" param="g.Module_Library_DB|g.function_return_value|Product_Load_Code( 'FOO', g.product )" />
`

The above example function returns 1 on success, so _g.function_return_value_ would be 1 if the product exists. In addition to that, the function also populates _g.product_ with the product data and you would then have access to that through that variable. Obviously depending on the function you call the return data may be different, this was just an example to illustrate how to use this component.

## License &amp; Source Code

© 2014 [Gassan Idriss](http://www.gassanidriss.com) &amp; [Spliced Media L.L.C](http://www.splicedmedia.com)

MvDO Component is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
