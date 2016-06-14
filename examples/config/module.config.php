<?php
namespace JqGridBackend;

use JqGridBackend\Grid\View\Helper\Grid;
use Zend\Form\Element as FormElement;
use Zend\Form\FieldsetInterface;
use JqGridBackend\Grid\View\Helper\ColModel;
//use JqGridBackend\Grid\View\Helper\Grid\SubgridPagerId;

use JqGridBackend\Grid\View\Helper\ColModelAdapterPluginManagerInterface;
use JqGridBackend\Grid\View\Helper\ColModelAdapterPluginManager;

return array(
    'JqGridBackend' => [
        'example_1' => [
            'template' => 'grid/index',
            /**
             * Look into documentation http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options about
             * available grid options. Any option can be overrided in form-object grid options
             */
            'options' => [
                /** The url that returns the data needed to populate the grid. */
                'url' => null,
                /** The datatype format which is expected for fill grid. The default is xml,
                 * but as for me json is more usual in php
                 */
                'datatype' => 'json',
                /** It allows to sort the results by multiple columns. The field sort sign will have 3 state.
                 * See documentation about options
                 */
                'multiSort' => true,
                /** Sets how many records we want to view in the grid.
                 * This parameter is passed to the url for use by the server routine retrieving the data.
                 * Note that if you set this parameter to 10 (i.e. retrieve 10 records) and
                 * if your server return 15 then only 10 records will be loaded.
                 */
                'rowNum' => 10,
                /** The select element which allow us to choose how many records we will see at ones  */
                'rowList' => [10,20,30],
                /**
                 * http://www.trirand.com/jqgridwiki/doku.php?id=wiki:pager
                 * If we want to print some data "as is" we must assing the object that extends from Zend\Json\Expr
                 * and possible override __toString() method
                 */
                'pager' => new Grid\GridPagerId('GridPager-'),
                /** Show information about begining and ending record number in pager bar */
                'viewrecords' => true,
                /** if you want grid without caption set it to null */
                'caption' =>  'Grid example 1',


            ],
            'methods' => [
                /**
                 * Look jqgrid documentation about available methods
                 * http://www.trirand.com/jqgridwiki/doku.php?id=wiki:methods
                 *
                 * arr[0] - method name,
                 * arr[1...n] - array of parameters (each will be transformed to json)
                 */
                'filterToolbar' =>  [
                    /** http://www.trirand.com/jqgridwiki/doku.php?id=wiki:toolbar_searching */
                    'filterToolbar',
                    [
                        "searchOnEnter" => true,
                        /** if the option is true, the posted data is equal on those as in searchGrid method
                         * http://www.trirand.com/jqgridwiki/doku.php?id=wiki:advanced_searching#options
                         */
                        "stringResult" => true,
                        "groupOp" => 'AND',
                        "searchOperators" => true,
                    ]
                ]
            ]
        ],
        /**
         * The default subgrid configuration.
         * It use other template and other pager because of using this grid into other grid
         */
        'subgrid' => [
            'template' => 'grid/subgrid',
            'options' => [
                'datatype' => 'json',
                'multiSort' => true,
                'rowNum' => 20,
                'rowList' => [10,20,30],
                'pager' => new Grid\SubgridPagerId('GridPager-'),
                'viewrecords' => true,
                'caption' =>  'Тестовый грид'
            ],
            'methods' => [
//                'filterToolbar' =>  [
//                    'filterToolbar',
//                    [
//                        "searchOnEnter" => true,
//                    ]
//                ],
            ]
        ],

    ],
	'service_manager' => [
        'invokables' => [
            ColModelAdapterPluginManagerInterface::class => ColModelAdapterPluginManager::class
        ]
    ],
    'jqgrid_adapter_manager' => [
        'invokables' => [
            ColModel\TextAdapter::class => ColModel\TextAdapter::class,
            ColModel\SelectAdapter::class => ColModel\SelectAdapter::class,
        ],
    ]
);
