<?php

class Requests extends My_Db_Table
{
	/**
	 * @var string
	 */
	protected $_name = 'requests';

	/**
	 * @var string
	 */
	protected $_rowClass = 'Request';

	/**
	 * @var mixed
	 */
	protected $_referenceMap = array(
		'User' => array(
			'columns' => array('user_id'),
			'refTableClass' => 'Users',
			'refColumns' => array('id')
		),
	);
}
