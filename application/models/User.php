<?php

class User extends My_Db_Table_Row {

	/**
	 * @return Product[]
	 * @throws Zend_Db_Table_Row_Exception
	 */
	public function getProducts() {
		$products = [];

		foreach ($this->findDependentRowset('Products') as $product) {
			if (!$product->getArchivated()) {
				$products[] = $product;
			}
		}

		return $products;
	}

}
