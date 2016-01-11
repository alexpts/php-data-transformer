# php-data-transformer

[![Build Status](https://travis-ci.org/alexpts/php-data-transformer.svg?branch=master)](https://travis-ci.org/alexpts/php-data-transformer)
[![Test Coverage](https://codeclimate.com/github/alexpts/php-data-transformer/badges/coverage.svg)](https://codeclimate.com/github/alexpts/php-data-transformer/coverage)
[![Code Climate](https://codeclimate.com/github/alexpts/php-data-transformer/badges/gpa.svg)](https://codeclimate.com/github/alexpts/php-data-transformer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/php-data-transformer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/php-data-transformer/?branch=master)


Одни и те же данные нужно представить в разном виде. В коде удобно работать с высокоуровневыми моделями. Но для сохранения этих данных в базу данных, как правило данные требуется перевести в более простой вид, как правило в ассоциативный массив. Для передачи данных между приложениями используют простые DTO сущности.

Компонент позволяет легко конвертировать ваши данные в ассоциативный массив и обратно из массива в вашу модель.

Вся схема маппинга описывается декларативно и вне модели. Позволяя для одной и то же модели описать разные схемы маппинга и трансформации полей. Например поле типа `DateTime` перед сохренением в mongoDB удобно преобразовать в объект типа `MongoDate`. Перед отдачей клиенту преобразовать его в строку ISO8601. Перед сохранением в redis преобразовать в `timestamp`.

Данные присланные из формы браузера всегда имеют стрковый тип данных, будь то числа или `true/false`. Трансформер решает эту проблему, в моделе данные всегда будут указанного типа.

## Installation

```$ composer require alexpts/php-data-transformer```

#### Трансформеры
При трансформации модели в массив или массива в модель. Каждое поле проходит через определнный тип в карте трансформации тип трансформации.
Карта трансформации представляет собой yml файл вида:
```yml
id:
    type: int
    get: getId
login:
    type: string
    get: getLogin
    set: setLogin
name:
    type: string
    prop: name
active:
    type: bool
    prop: active
creAt:
    type: date
    get: getCreAt
email:
    type: string
    prop: email
```

Где ключ является индексом массива. 

`type` - тип трансформера.

`prop` - свойство модели

`get` - метод геттер модели (`prop` игнорируется)

`set` - метод сеттер модели (`prop` игнорируется)

#### Менеджер карт трансформации
Для регистрации карт трансформации используется метод `setMapDir` объкта типа `MapsManager`.
Первый параметр - класс модели. Второй - директория с картами трансформации для этой модели. 

```php
$mapsManager = new MapsManager(new Parser);
$mapsManager = setMapDir(UserModel::class, __DIR__ . '/transformers');
```

#### Model to data

```php
$transformer = new DataTransformer(
	new TypeConverter,
	$mapsManager,
	new ModelClosure
);
```

Модель передается первым параметром в метод `getData` трансформатора. Вторым параметром указывается имя карты трансформации для этой модели. (по умолчанию `dto`).

```php
$user = new User('name', 'login', 'email@gmail.com');
$transformer->getData($user, 'dto');
```

Путь файла трансформации в этом примере будет иметь вид:

`__DIR__ . '/transformers/dto.yml'`.


#### Data to model
Через эту же карту транфсформации данные могут быть обратимо преобразованы назад в модель.
```php
$data = [
	'name' => 'name',
	'login' => 'login',
	'email' => 'email@gmail.com'
];

$model = $transformer->createModel(User::class);
$transformer->fillModel($data, $model, 'dto');
```
