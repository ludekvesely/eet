<?php

class Stores extends My_Db_Table  {

	/**
     * @var string
     */
    protected $_name = 'stores';
    
    /**
     * @var string
     */
    protected $_rowClass = 'Store';

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
