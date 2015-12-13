<?php

class SellForm extends Zend_Form
{

	const MAX_COUNT = 10;

	private $product;

	public function __construct($productId)
	{
		$this->product = My_Model::get('Products')->find($productId)->current();
		parent::__construct();
	}

	public function init()
	{

		$this->setDisableLoadDefaultDecorators(true);
		$this->addDecorator('FormElements')
			->addDecorator('HtmlTag', array('tag' => 'ul'))
			->addDecorator('Form');

		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form');


		/*$this->addDisplayGroup(array('amount'), 'hiddenGroup', array(
			'decorators' => array(
				'FormElements',
				array('HtmlTag', array('tag' => 'div', 'style' => 'display: none')),
			),
		));*/

		for ($i = 1; $i < self::MAX_COUNT; $i++) {

			$this->addElement('submit', (string)$i, array(
				'ignore' => true,
				'class' => 'submit btn btn-lg btn-success',
				'label' => (string)$i,
				'style' => 'margin-left:15px',
				//'onclick' => '$("#amount").val("' . $i . '"); return true;'
			));

			if (($i % 3) == 0) { // || $i >= $this->product->count

				$this->addDisplayGroup(array($i - 2, $i - 1, $i), 'buttons' . $i, array(
					'decorators' => array(
						'FormElements',
						array('HtmlTag', array('tag' => 'ul', 'class' => 'list-inline', 'style' => 'margin-bottom: 20px')),
					),
				));

			}

			/*if ($i >= $this->product->count) {
				break;
			}*/

		}

        $this->addElement('submit', '0', array(
            'ignore' => true,
            'class' => 'submit btn btn-lg btn-success',
            'label' => '0',
            'style' => 'margin-left:87px',
            //'onclick' => '$("#amount").val("' . $i . '"); return true;'
        ));

        $this->addDisplayGroup(array('0'), 'buttons' . $i, array(
            'decorators' => array(
                'FormElements',
                array('HtmlTag', array('tag' => 'ul', 'class' => 'list-inline', 'style' => 'margin-bottom: 20px')),
            ),
        ));

        $this->addElement('text', 'amount', array(
            'ignore' => true,
            'style' => 'display: none'
        ));

        $this->addElement('submit', 'addSlow', array(
            'ignore' => true,
            'class' => 'btn btn-lg btn-primary',
            'label' => 'Vložit',
            'style' => 'display: none'
        ));

        $this->addDisplayGroup(array('amount', 'addSlow'), 'slowGroup', array(
            'decorators' => array(
                'FormElements',
                array('HtmlTag', array('tag' => 'div', 'class' => 'slow-group')),
            ),
        ));

		$this->setElementDecorators(array(
			'ViewHelper',
			'Errors',
			new Zend_Form_Decorator_HtmlTag(array('tag' => 'li'))
		));

	}

}
