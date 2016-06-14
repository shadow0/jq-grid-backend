Введение
========
Данный модуль содержит помощник вида (view helper), генерирующий javascript, 
который использует jqGrid плагин для генерации табличного вида данных на клиентской стороне.
Для описания грида используются объекты типа Zend\Form\Fieldset.

Простейшее использование выглядит следующим образом.
Класс формы:
```php
<?php
namespace JqgridTest\Form;

use Zend\Form\Form;

class TestGrid extends Form
{

    public function __construct($name, $options = array()) {
        parent::__construct($name, $options);
        $this->init();
    }

    public function init() {
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
                'search'=> true
            ]
        ]);

        $this->add([
            'name' => 'note',
            'type' => 'text',
            'attributes' => [
                'size' => 10,
                'maxlength' => 20
            ],
            'options' => [
                'label' => 'Note'
            ]
        ]);

        $this->add([
            'name' => 'Select',
            'type' => 'select',
            'options' => [
                'label' => 'TextSelect',
                'value_options' => [
                     '0' => 'French',
                     '1' => 'English',
                     '2' => 'Japanese',
                     '3' => 'Chinese',
                ],
            ]
        ]);
    }

}
```
Создаем форму в действии контроллера и передаем во view. Фрагмент action в контроллере:
```php
    $form = new Form\TestGrid('Test');
    $form->setOption(
        'gridOptions',
        [
            'url' => '/jqgrid/data',
            'autowidth' => true,
        ]
    );
    return new ViewModel([
        'form' => $form,
    ]);
```
gridOptions - параметры для грида отличные от параметров по умолчанию. Т.к. мы предполагаем загрузку данных
[ajax][] запросом, поэтому единственным обязательным параметром является url.
Можно передавать любые опции, которые доступны согласно спецификации jqGrid. Посмотреть спецификацию 
можно [здесь](http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options).
Например, autowidth=>true позволяет растянуть грид по ширине родительского элемента.

Фрагмент view:
```php
<link rel="stylesheet" type="text/css" media="screen" href="/css/ui/jquery-ui.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/ui/ui.jqgrid.css" />

<script src="/js/grid/grid.locale-ru.js" type="text/javascript"></script>
<script src="/js/grid/jquery.jqGrid.js" type="text/javascript"></script>
<?php
echo $this->jqGrid($this->form);
```
модуль не содержит [jqGrid] и [jQuery], поэтому внедрить их в проект необходимо самостоятельно.

Имя формы будет использовано для имени самого грида.
В результате  на странице появится следующий фрагмент:
```javascript
<link rel="stylesheet" type="text/css" media="screen" href="/css/ui/jquery-ui.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/ui/ui.jqgrid.css" />

<script src="/js/grid/grid.locale-ru.js" type="text/javascript"></script>
<script src="/js/grid/jquery.jqGrid.js" type="text/javascript"></script>
<table id="GridTest"></table>
<script>
    $(document).ready(function() {
        var grid_pager_id = undefined;
        if (grid_pager_id != undefined) {
            jQuery("table#GridTest").after("<div id=\'" + grid_pager_id + "\'></div>");
        }
        var GridTest = jQuery('#GridTest').jqGrid(
{
    "url": "\/jqgrid\/data",
    "datatype": "json",
    "multiSort": true,
    "autowidth": true,
    "colModel": [
        {
            "edittype": "text",
            "index": "name",
            "label": "Name",
            "name": "name",
            "stype": "text"
        },
        {
            "edittype": "text",
            "index": "note",
            "label": "Note",
            "name": "note",
            "stype": "text"
        },
        {
            "editoptions": {
                "value": {
                    "0": "French",
                    "1": "English",
                    "2": "Japanese",
                    "3": "Chinese"
                }
            },
            "edittype": "select",
            "formatter": "select",
            "index": "Select",
            "label": "TextSelect",
            "name": "Select",
            "searchoptions": {
                "value": {
                    "0": "French",
                    "1": "English",
                    "2": "Japanese",
                    "3": "Chinese"
                }
            },
            "stype": "select"
        }
    ]
});
    });
</script>
```

[ajax]: https://ru.wikipedia.org/wiki/AJAX
[composer.json]: ./composer.json
[Composer]: http://getcomposer.org/
[jQuery]: https://jquery.com/
[jqGrid]: http://jqgrid.com/
