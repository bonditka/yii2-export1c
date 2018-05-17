Экспорт документов из Yii2 в 1с через формат EnterpriseData ("Универсальный формат обмена 1с") 
====================

Модуль позволяет генерировать xml файлы с описанием документов через формат [EnterpriseData](http://v8.1c.ru/edi/edi_app/enterprisedata/).
Более детальное описание формата обмена можно почитать [на сайте 1с](http://v8.1c.ru/edi/edi_stnd/enterprisedata/1.0/).

На данный момент реализована генерация для следующих документов:
- [Реализация товаров и услуг](http://v8.1c.ru/edi/edi_stnd/enterprisedata/1.0/#00000000054)
- [Поступление товаров и услуг](http://v8.1c.ru/edi/edi_stnd/enterprisedata/1.0/#00000000075)


Установка
------------

Предпочтительный вариант установки через [composer](http://getcomposer.org/download/).

Чтобы установить, выполните следующую команду:

```
php composer.phar require --prefer-dist bonditka/export1c "*"
```

или добавьте

```
"bonditka/export1c": "*"
```

в блок require вашего `composer.json` файла.

Использование
-----

Для передачи данных в модуль используются dto модели.
Для всех документов обязательны следующие поля:
```php
'companyInfo' => [
	'name' => 'Lorem name',
	'nameShort' => 'Lorem name short',
	'nameFull' => 'Lorem name full',
	'inn' => 'Lorem inn',
	'kpp' => 'Lorem kpp',
	'formOrganization' => 'Lorem formOrganization'
],
'partnerInfo' => [
	'name' => 'Lorem name',
	'nameShort' => 'Lorem name short',
	'inn' => 'Lorem inn',
	'kpp' => 'Lorem kpp',
	'formOrganization' => 'Lorem formOrganization'
],
'counterparty' => [
	'name' => 'Lorem name',
	'nameShort' => 'Lorem name short',
	'inn' => 'Lorem inn',
	'kpp' => 'Lorem kpp',
	'formOrganization' => 'Lorem formOrganization'
],
'tableItems' => [
	[
		'name' => 'Lorem name',
		'code' => 'Lorem code',
		'unitCode' => 'Lorem unitCode',
		'quantity' => 2,
		'amount' => 150,
		'price' => 75,
		'vat' => 18,
		'vatAmount' => 27
	],
	[
		'name' => 'Lorem name',
		'code' => 'Lorem code',
		'unitCode' => 'Lorem unitCode',
		'quantity' => 2,
		'amount' => 150,
		'price' => 75,
		'vat' => 18,
		'vatAmount' => 27
	],
]
```

Для документа ПоступлениеТоваровУслуг дополнительно необходимо передать:
```php
'documentNumber' => [
	'number' => 'Lorem number',
	'date' => 'Lorem date'
],
'store' => [
	'name' => 'Lorem name',
	'typeStore' => 'Lorem name short',
]
```

Для документа РеализацияТоваровУслуг:
```php
'companyAccountant' => [
	'fio' => 'Lorem fio',
	'birthDay' => 'Lorem birthDay',
	'inn' => 'Lorem inn',
],
'companyBank' => [
	'accountNumber' => 'Lorem accountNumber',
	'bik' => 'Lorem bik',
	'korrAccount' => 'Lorem korrAccount',
	'name' => 'Lorem name'
],
'companyHead' => [
	'fio' => 'Lorem fio',
	'birthDay' => 'Lorem birthDay',
	'inn' => 'Lorem inn',
],
'releaseProduced' => [
	'fio' => 'Lorem fio',
	'birthDay' => 'Lorem birthDay',
	'inn' => 'Lorem inn',
]
```

Нужную dto модель можно сгенерировать из массива через соответствующий конструктор:
 
```php
//для документа ПоступлениеТоваровУслуг
$documentDto = new dto\DocumentDto($arData);

//для документа РеализацияТоваровУслуг
$documentDto = new dto\SellingDto($arData);
```

Для генерации всего документа нужно вызвать метод `addDocument`. Так же существует возможность сгенерировать только шапку (метод `addDocumentHeader`) и только табличную часть документа (метод `addDocumentTable`).
Когда нужная dto модель сформирована, запустить генерацию xml можно через специальный сервис:

```php
$action = 'addDocument';

$generatorService = new GeneratorDocumentService($dto);
$response = $generatorService->run($action, $param);

//проверка ответа на наличие ошибок 
if($response->hasError()){
  print_r($response->getErrors());
}
```

Или напрямую через модель:
```php
$documentDto = new dto\DocumentDto($arData);

$generator = new GeneratorDocument();
$generator->setParam($param);

$generator->addDocumentTable($documentDto->tableItems);
```

В массиве `$param` можно передать дополнительные парметры выполнения.
На данный момент обрабатывается только один элемен данного массива с ключом `filePath`, в котором должен содердаться путь до выходного xml-файла.


Тестирование
-----

Запускать тесты можно следующими командами:

```bash
vendor/bin/codecept build
vendor/bin/codecept run
```