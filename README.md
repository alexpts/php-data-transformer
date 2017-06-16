# php-data-transformer

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46/big.png)](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46)

[![Build Status](https://travis-ci.org/alexpts/php-data-transformer.svg?branch=master)](https://travis-ci.org/alexpts/php-data-transformer)
[![Test Coverage](https://codeclimate.com/github/alexpts/php-data-transformer/badges/coverage.svg)](https://codeclimate.com/github/alexpts/php-data-transformer/coverage)
[![Code Climate](https://codeclimate.com/github/alexpts/php-data-transformer/badges/gpa.svg)](https://codeclimate.com/github/alexpts/php-data-transformer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/php-data-transformer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/php-data-transformer/?branch=master)


Более новый несовместимый вариант библиотеки - https://github.com/alexpts/php-hydrator

Одни и те же данные нужно представить в разном виде. В коде удобно работать с высокоуровневыми моделями. Но для сохранения этих данных в базу данных, как правило, данные требуется перевести в более простой вид, обычно в ассоциативный массив. Для передачи данных между приложениями используют простые DTO сущности.

Компонент позволяет легко конвертировать ваши данные в ассоциативный массив и обратно из массива в вашу модель.

Вся схема маппинга описывается декларативно и вне модели. Позволяя для одной и то же модели описать разные схемы маппинга и трансформации полей. Например поле типа `DateTime` перед сохренением в mongoDB удобно преобразовать в объект типа `MongoDate` или `UTCDateTime`. Перед отдачей клиенту преобразовать его в строку ISO8601. Перед сохранением в redis преобразовать в `timestamp`.

Данные присланные из формы браузера всегда имеют стрковый тип данных, будь то числа или `true/false`. Трансформер решает эту проблему, в моделе данные всегда будут перобразованны в тип, указанный в карте трансформации.

#### Installation

```$ composer require alexpts/php-data-transformer```

#### Требования
PHP 7.0+

#### Модели
Трансформатор никак не ограничивает ваши модели. Вы работаете с чистыми моделями.

#### Трансформеры
При трансформации модели в массив или массива в модель каждое поле проходит через определнный тип в карте трансформации.
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
postsIds:
	type: int
	coll: true
roles:
    type: refModels
    prop: roles
    rel:
        model: \\SomeNamespace\\Role
        map: dto
```

Где ключ является индексом массива. 

`type` - тип трансформера

`coll` - [optional] true|false является ли значение коллекцией

`prop` - [optional] свойство модели

`get` - [optional] метод геттер модели (`prop` игнорируется)

`set` - [optional] метод сеттер модели (`prop` игнорируется)

`rel` - [optional] объект описывающий связанную моделть
	
`rel.model` - [optional] полный стр оковый путь класса связанной модели

`rel.map` - [optional] имя карты трансформации вложенной модели [умолч.: 'dto']


#### Менеджер карт трансформации
Для регистрации карт трансформации используется метод `setMapDir` объекта типа `MapsManager`.
Первый параметр - класс модели. Второй - директория с картами трансформации для этой модели. 

```php
$mapsManager = new MapsManager(new Parser);
$mapsManager = setMapDir(UserModel::class, __DIR__ . '/transformers');
```

#### Model to DTO

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
$dtoUser = $transformer->getData($user, 'dto');
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

#### Значение как коллекция
Если поле модели представлено колелкцией каких-либо значение, то можно прогнать каждое значение коллекции через тип трансформации и получить коллекцию трансформированных значений. Для этого укажите полю в карте трансформации флаг `coll: true`. Тоже самое можно сделать написав собственный тип трансформации, который сам решает как обработать значение-коллекцию.

#### Вложенные модели
Вы можете описывать свои типы трансформации для вложенных моделей. Примером трансформации вложенной коллекции моделей служит тип `refModels`. [Пример трансформации модели с массивом вложенных моделей](https://github.com/alexpts/php-data-transformer/blob/master/example/refs/demo1.php).

Зачастую передавать или хранить вместо вложенной модели разумно только ее id. Примером такой трансформации является тип `refModelsToArrayStringId`. Если id модели должен быть представлен не строковым типом (например `MongoId` или `ObjectId`), то просто опишите свой тип.

#### Подключение собственного типа
Создайте свой тип трансформации с методами `toData` и `toModel`. Новый тип подключается к объекту типа `TypeConverter` методом `addType($name, $type)`. С помощью этого метода, можно перетереть стандатные типы, заместив их собтсвенной реализацией.

Можно отнаследоваться от класса TypeConverter и указать в конуструкторе все дефолтные типы, чтобы не подключать типы вручную.
