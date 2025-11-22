<p align="center"><a href="https://ai.symfony.com" target="_blank">
    <img src="logo.svg" alt="Symfony AI Logo" width="300">
</a></p>

Workshop: Symfony AI - Discover The New Ecosystem

## Requirements

What you need to participate in this workshop:

* Internet Connection
* Terminal & Browser
* [Git](https://git-scm.com/) & [GitHub Account](https://github.com)
* [Docker](https://www.docker.com/) with [Docker Compose Plugin](https://docs.docker.com/compose/)
* Your Favorite IDE or Editor
* An [OpenAI API Key](https://platform.openai.com/docs/api-reference/create-and-export-an-api-key)
* A [Hugging Face API Key](https://huggingface.co/settings/tokens) _(optional)_
* An MCP Client _(optional)_

## Technology

This small demo sits on top of following technologies:

* [PHP >= 8.5](https://www.php.net/releases/8.5/en.php)
* [Symfony 8.0 incl. Twig, Asset Mapper & UX](https://symfony.com/)
* [Stimulus](https://stimulus.hotwired.dev/) & [Bootstrap 5](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
* [OpenAI's GPT & Embeddings](https://platform.openai.com/docs/overview)
* [ChromaDB Vector Store](https://www.trychroma.com/)
* [FrankenPHP](https://frankenphp.dev/)

**And of course, the [Symfony AI Components & Bundles](https://ai.symfony.com/).**

## Setup

The setup is split into three parts, the Symfony application, the OpenAI configuration, and initializing the Chroma DB.

### 1. Symfony App

Checkout the repository, start the docker environment and install dependencies:

```shell
git clone git@github.com:chr-hertel/symfony-ai-workshop.git
cd symfony-ai-workshop
docker compose up -d
docker compose run composer install
```

Now you should be able to open https://localhost/ in your browser, and the chatbot UI should be available for you to
start chatting.

> [!NOTE]
> You might have to bypass the security warning of your browser with regard to self-signed certificates.

### 2. OpenAI Configuration

For using GPT and embedding models from OpenAI, you need to configure the `OPENAI_API_KEY` env variable.

This is done by copying the provided `dev.decrypt.private.php` file into `config/secrets/dev/` directory.

Verify the success of this step by running the following command:
```shell
docker compose exec app bin/console secrets:list [--reveal]
```

You should see the `OPENAI_API_KEY` in the list of secrets.

Now dump the secrets into the `.env.dev.local` file:
```shell
docker compose exec app bin/console secrets:decrypt-to-local --force
```

And as a last step, you can call the test command:
```shell
docker compose exec app bin/console app:openai:test
```

**Don't forget to set up the project in your favorite IDE or editor.**

## Functionality

* The chatbot application is a simple and small Symfony application.
* The UI is coupled to a [Twig LiveComponent](https://symfony.com/bundles/ux-live-component/current/index.html), that integrates different `Chat` implementations on top of the user's session.
* You can reset the chat context by hitting the `Reset` button in the top right corner.
* As part of this workshop, we will connect the `Chat` with GPT, a vector store and other tools.

## Helpers

This repository comes with some tools for quality assurance installed, and a small wrapper script.

### Execute all quality checks at once

```shell
bin/check
```

### Composer

```shell
docker compose run run composer install
docker compose run composer validate
```

### PHP CS Fixer

```shell
docker compose exec app vendor/bin/php-cs-fixer fix
```

### PHPStan

```shell
docker compose exec app vendor/bin/phpstan analyse --memory-limit=-1
```

### PHPUnit

```shell
docker compose exec app vendor/bin/phpunit
```
