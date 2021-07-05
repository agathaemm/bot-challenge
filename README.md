# Bot Challenge

## Rodando a aplicação pela primeira vez

Primeiro deve-se configurar o arquivo .env da aplicação
Feito isso siga os passo a seguir:

```bash
# Esta aplicação utiliza o laravel com sail então primeiro deve-se rodar o comando
$ sail up -d

# Para finalizar o sail basta rodar
$ sail down

# Em seguida rode o comando para importar as dependencias
$ sail composer install

# Depois devemos gerar a chave da aplicação
$ sail artisan key:generate

# Rode as migrations
$ sail artisan migrate
```

## Executando os comandos

```bash
# Para rodar o primeiro item do desafio execute o comando:

$ sail artisan capture-table-information

# Para rodar o segundo item do desafio execute o comando:

$ sail artisan fill-form

# Para rodar o terceiro item do desafio execute o comando:
# (Você encontrará o arquivo baixado na pasta storage/files)

$ sail artisan download-file

# Para rodar o quarto item do desafio execute o comando:

$ sail artisan upload-file

# Para rodar o quinto item do desafio execute o comando:
# (Você encontrará o arquivo excel na pasta storage/framework/laravel-excel)

$ sail artisan extract-pdf-data
```
