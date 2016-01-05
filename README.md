BaconLanguageBundle
===============

Este bundle é responsável por adicionar gerenciar os idiomas do Manager, nele esta o crud completo e mais a rota para alterar o idioma e voltar para a pagina que estava.

## Instalação

Para instalar o bundle basta rodar o seguinte comando abaixo:

```bash
$ composer require bacon/language-bundle
```
Agora adicione os seguintes bundles no arquivo AppKernel.php:

```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    // ...
    new Bacon\Bundle\LanguageBundle\BaconLanguageBundle(),
    // ...
}
```

## Configuração

Adicionar as seguintes linhas no arquivo de configuração de rotas

```yaml
bacon_language:
    resource: "@BaconLanguageBundle/Controller/"
    type:     annotation
    prefix:   /{_locale}/admin

```

## Renderizando o menu de idiomas

```html
{{ bacon_menu_language_render() }}
```