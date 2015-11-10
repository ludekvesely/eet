<?php

class Products extends My_Db_Table {

	/**
     * @var string
     */
    protected $_name = 'products';
    
    /**
     * @var string
     */
    protected $_rowClass = 'Product';

    /**
     * @param $id
     * @return Product|null
     */
    public function findById($id)
    {
        $product = $this->getById($id);
        return $product->getArchivated() ? null : $product;
    }

    /**
     * @var mixed
     */
    protected $_referenceMap = array (  
        'User' => array(
           'columns' => array ('user_id'),
           'refTableClass' => 'Users',
           'refColumns' => array ('id')
        ), 
    );

}
