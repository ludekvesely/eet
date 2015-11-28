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

}
