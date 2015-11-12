<?php

class User extends My_Db_Table_Row {

	/**
	 * @return Product[]
	 * @throws Zend_Db_Table_Row_Exception
	 */
	public function getProducts()
	{
		$products = [];

		foreach ($this->findDependentRowset('Products') as $product) {
			if (!$product->getArchivated()) {
				$products[] = $product;
			}
		}

		return $products;
	}

	/**
	 * @return Store|null
	 * @throws Zend_Db_Table_Row_Exception
	 */
	public function getStore()
	{
		$store = $this->findDependentRowset('Stores');
		return (count($store) !== 0)  ? $store[0] : null;
	}

}
