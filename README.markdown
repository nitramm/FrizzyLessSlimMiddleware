## LESS Slim Middleware

### Usage

Add the following in your root composer.json file:

    {
        "require": {
            "frizzy/less-slim-middleware": "0.*"
        }
    }


#### Compiling LESS

Put your less file in any public direcotry (Example: public/css/my_stylesheet.less)

Before the compiler generates a CSS file, a directory called 'generated' will be created in your LESS file's directory.
The generated CSS file will have the same filename as your LESS file but with a .css extension.

All you need to do is configure your <link> tag with the location of the generated .css file:

`<link rel="stylesheet" href="/css/generated/my_stylesheet.css" />`