<p align="center"><a href="https://github.com/ibnsultan/polar" target="_blank"><img src="https://raw.githubusercontent.com/ibnsultan/polar/7ca21756a4f37072a8a5e714cf1488ed60487187/public/assets/images/logo-dark.svg" width="400" alt="Laravel Logo"></a></p>

## About Polar

Polar is a modern, streamlined Laravel starter kit built on top of Jetstream, crafted to help you launch your next idea faster. Featuring a clean design, powerful tools, and the flexibility you expect from Laravel, Polar is here to help you build with confidence and joy.

## Learning Polar

Polar is built on top of Laravel, a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience for everyone. Laravel strives to be a framework that you will love using.

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

## Getting Started

Install Polar using Composer:

```bash
composer create-project friendlylabs/polar
```

Then, install the dependencies using NPM:

```bash
npm install
```
Then, run the following command to build the assets:

```bash
npm run build
```

Finally, run the following command to start the development server:

```bash
php artisan serve
```
You can now access the application at `http://localhost:8000`.

## Theme Customization
Polar is designed to be easily customizable. You can change the theme colors, layout, and other design elements by modifying the `.env` file or directly the `jetstream.php` configuration file. You can also customize the theme by modifying the CSS files in the `resources/css` directory.

```bash
# preset: 'preset-1' to 'preset-18', these are pre made theme colors
JETSTREAM_THEME_PRESET=preset-12

# theme: 'light' or 'dark', default color scheme for the application
JETSTREAM_THEME_MODE=light

# layout: 'vertical', 'horizontal', 'compact', 'tab', 'color-header'
JETSTREAM_APP_LAYOUT=vertical
```