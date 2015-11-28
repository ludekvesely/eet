<?php

class SalesProducts extends My_Db_Table  {

	/**
     * @var string
     */
    protected $_name = 'sales_products';
    
    /**
     * @var string
     */
    protected $_rowClass = 'SaleProduct';

    /**
     * @var mixed
     */
    protected $_referenceMap = array (
        'Sale' => array(
            'columns' => array ('sales_id'),
            'refTableClass' => 'Sales',
            'refColumns' => array ('id')
        ),
        'Product' => array(
            'columns' => array ('products_id'),
            'refTableClass' => 'Products',
            'refColumns' => array ('id')
        ),
    );

}
