# Yoast SEO

## Welcome to the Yoast SEO: Granular Control GitHub repository

## Want to contribute to Yoast SEO: Granular Control?

### Prerequisites

At Yoast, we make use a specific toolset to develop our code. Please ensure you have the following tools installed before contributing. 

* [Composer](https://getcomposer.org/)
* [Yarn](https://yarnpkg.com/en/)
* [Grunt](https://gruntjs.com/)

### Getting started
After installing the aforementioned tools, you can use the steps below to acquire a development version of Yoast SEO: Granular Control.
Please note that this will download the latest development version of Yoast SEO: Granular Control. While this version is usually stable,
it is not recommended for use in a production environment.

Within your WordPress installation, navigate to `wp-content/plugins` and run the following commands:
```bash
git clone https://github.com/Yoast/yoast-seo-granular-control.git
cd yoast-seo-granular-control
```

To install all the necessary dependencies, run the following commands:
```bash
composer install
yarn
grunt build
```

Please note that if you change anything in the JavaScript or CSS, you'll have to run `grunt build:js` or `grunt build:css`, respectively.

This repository uses [the Yoast grunt tasks plugin](https://github.com/Yoast/plugin-grunt-tasks).

## Support

This is a developer's portal for Yoast SEO and should not be used for support. Please visit the
[support forums](https://wordpress.org/support/plugin/yoast-seo-granular-control).

Unfortunately, due to the nature of this plugin’s features, we can't be held responsible nor provide support for any problems with your website, visibility in search engines, drops in rankings or other issues related to your changes. Installing and using this plugin is solely your own responsibility.

## Reporting bugs

If you find an issue, [let us know here](https://github.com/yoast/yoast-seo-granular-control/issues/new)! Please follow [these guidelines](https://yoa.st/1uo) on how to write a good bug report.

It may help us a lot if you can provide a backtrace of the error encountered. You can use [code in this gist](https://gist.github.com/jrfnl/5925642) to enable the backtrace in your website's configuration.

## Contributions

Anyone is welcome to contribute to Yoast SEO. Please
[read the guidelines](.github/CONTRIBUTING.md) for contributing to this
repository.

There are various ways you can contribute:

* [Raise an issue](https://github.com/yoast/wordpress-seo/issues) on GitHub.
* Send us a Pull Request with your bug fixes and/or new features.
* [Translate Yoast SEO into different languages](http://translate.yoast.com/projects/wordpress-seo/).
* Provide feedback and [suggestions on enhancements](https://github.com/yoast/wordpress-seo/issues?direction=desc&labels=Enhancement&page=1&sort=created&state=open).

