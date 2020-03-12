<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td width="45%">
      <img src="https://cloud.githubusercontent.com/assets/1716665/14317675/ba034b8c-fc09-11e5-81ed-f10f37d86ea5.png" width="100%" title="hover text">
    </td>
    <td width="55%">
      <img src="http://search.Pagekit.ru/storage/searchscreenshot.jpg" width="100%" alt="accessibility text">
    </td>
  </tr>
</table>

------
# PageKit Search extension
> Build for updated pagekit cms, include pages/blogs/news search engine, you can choose by yourself.

You can find the updated version from [uatrend/pagekit](https://github.com/uatrend/pagekit).
This extension is update from [neicv/pagekit-search](https://github.com/neicv/pagekit-search).
> This extension is not working well with the original Pagekit(Uikit2, Vue1,Jquery).

------
## Updated Pagekit

[![Discord](https://img.shields.io/badge/chat-on%20discord-7289da.svg)](https://discord.gg/e7Kw47E)

[Homepage](http://pagekit.com) - Official home page.

This is an updated build Pagekit CMS (for developers).

Build includes:

- Pagekit CMS 1.0.18
- Blog extension
- Theme One
- News extension
- Search extension
- Highlight extension

------

### Major changes:

- **Required PHP Version - 7.2 or higher(7.4+).**
- **[Updated all project dependencies](https://github.com/uatrend/pagekit/blob/develop/package.json).**
- **Updated all core javascript components**.
- **Removed jQuery**.
- **Uikit3**.
- **Vue 2.6.10**.
- **Symfony 4.4**.

Several bugs that are present in the original assembly have been fixed, some styles have been changed for ease of use. The mobile version has remained the same with minor changes.

## <a name="install"></a>Install from source

Clone Repository

```
$ git clone git@github.com:cssailing/pagekit-search.git project-folder
$ cd project-folder
```

## Usage
If you wanna make "search page" - go to `Site -> Add new Page -> Link` in the Pagekit admin area.
And make link to "/search".

Or / And u can use "Search widget" 

## <a name="scripts"></a>Scripts

Webpack watch:

```
$ yarn watch
$ npm run watch
```

Webpack build (minified):

```
$ yarn build
$ npm run build
```

Linting with eslint:

```
$ yarn lint
$ npm run lint
```

## Gulp tasks

Compile LESS:

```
$ gulp compile
```

Compile and watch LESS:

```
$ gulp watch
```

CLDR locale data for internationalization:

```
$ gulp cldr
```

------

Thanks to Yootheme and developers!  
Thanks to uatrend updates!
Thanks to neicv coding!
Feel free to ask any questions - I will answer as much as possible.