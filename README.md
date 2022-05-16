## Проект 2. Вычислитель отличий / Project 2. Get difference between two json/yaml files. 

Второй проект в рамках професии PHP-программист на [Хекслет](https://ru.hexlet.io/programs/php)\
"Вычислитель отличий" - консольная утилита вычисляющая разницу между парой json и/или yaml файлов.\
Результаты выводятся в нескольких вариантах форматов: stylish, plain и json


### Статусы проверки тестов Hexlet и линтера / Hexlet tests and linter status:
[![Actions Status](https://github.com/Posashkov/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/Posashkov/php-project-lvl2/actions)
[![my-workflow](https://github.com/Posashkov/php-project-lvl2/actions/workflows/my-workflow.yml/badge.svg)](https://github.com/Posashkov/php-project-lvl2/actions/workflows/my-workflow.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/9a7049c8e3421daf59c0/maintainability)](https://codeclimate.com/github/Posashkov/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/9a7049c8e3421daf59c0/test_coverage)](https://codeclimate.com/github/Posashkov/php-project-lvl2/test_coverage)


### Установка / Setup
Установка и запуск проекта.\
Install and run project.\
https://asciinema.org/a/mL2LccP6ihgdUiCQgkjecdclK

```
$ git clone git@github.com:Posashkov/php-project-lvl2.git

$ make install
```


### Примеры использования / Examples

Получить разницу между двумя плоскими json файлами.\
Get difference between two plain json files.\
https://asciinema.org/a/cDuaDCQdfm6b7v98otyQnWitW

Получить разницу между двумя плоскими yaml файлами.\
Get difference between two plain yaml files.\
https://asciinema.org/a/IdOlfLPyCsKXWwRxdW4bfSDrU

Получить разницу между вложенными json/yaml файлами. Вывод в формате Stylish (по умолчанию).\
Get difference between two nested json/yaml files.\
https://asciinema.org/a/FWiXNJNJAOOXLw4Th9dBhFMaa

Получить разницу между вложенными json/yaml файлами. Вывод в формате Plain.\
Get difference between two json/yaml files using Plain formatter.\
https://asciinema.org/a/Instc4AchE6pRAgzQkHx0j55C

Получить разницу между вложенными json/yaml файлами. Вывод в формате json.\
Get difference between two json/yaml files in json format.\
https://asciinema.org/a/obvNcN208mUuV4g8Swb31LdD5


### Запуск тестов / Run tests
```
$ make test
```

