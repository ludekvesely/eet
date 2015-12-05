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
        if (!$product || $product->getUserId() !== My_Model::get('Users')->getUser()->getId()) {
            return null;
        }
        return $product->getArchivated() ? null : $product;
    }

    /**
     * @return Product|null
     */
    public function findStored()
    {
        return $this->fetchAll(
            [
                'user_id = ?' => My_Model::get('Users')->getUser()->getId(),
                'stored = ?' => true,
                'archivated = ?' => false,
            ]
        );
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
