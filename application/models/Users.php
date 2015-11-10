<?php

class Users extends My_Db_Table  {

	/**
     * @var string
     */
    protected $_name = 'users';
    
    /**
     * @var string
     */
    protected $_rowClass = 'User';

    protected $_dependentTables = array('Products');

    /**
     * @return User
     */
    public function getUser()
    {
        $auth = Zend_Auth::getInstance();

        if($auth->hasIdentity()) {
            $username = $auth->getIdentity();

            return $this->fetchRow([
                sprintf('username = "%s"', $username),
            ]);
        }

        return null;
    }

}
