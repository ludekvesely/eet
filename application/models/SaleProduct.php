<?php

class SaleProduct extends My_Db_Table_Row {

	/**
	 * @param mixed[] $values
	 * @return Store
	 */
	public function updateFromArray($values)
	{
		$this->setFromArray($values);
		$this->save();

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray()
	{
		$values = parent::toArray();
		return $values;
	}

}
