<?php

class Sales extends My_Db_Table  {

	/**
     * @var string
     */
    protected $_name = 'sales';
    
    /**
     * @var string
     */
    protected $_rowClass = 'Sale';

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

    public function getProducts($id)
    {
        $ret = [];
        foreach ($this->getById($id)->findDependentRowset('SalesProducts') as $rel) {
            $ret[] = My_Model::get('Products')->getById($rel->products_id)->toArray() + array(
                'amount' => $rel->amount,
                'unit_price' => $rel->unit_price,
                'sales_products_id' => $rel->id
            );
        }
        return $ret;
    }

}
