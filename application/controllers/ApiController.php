<?php

class ApiController extends Zend_Controller_Action
{
	const STATUS_OK = 200;
	const STATUS_BAD_REQUEST = 400;
	const STATUS_UNAUTHORIZED = 401;
	const STATUS_INTERNAL_ERROR = 500;

	public function historyAction()
	{
		$this->view->title = 'Simulace API MFČR';
		$this->view->requests = My_Model::get('Requests')->fetchAll();
	}

	public function indexAction()
	{
		$this->view->title = 'Simulace API MFČR';
	}

	public function createAction()
	{
		$data = json_decode($this->getRequest()->getRawBody(), TRUE);
		try {
			$response = $this->process($data);
		} catch (Exception $e) {
			$response = [
				'status' => self::STATUS_INTERNAL_ERROR,
				'message' => 'Internal error: ' . $e->getMessage()
			];
		}
		$this->_response->setHeader('Content-Type', 'application/json');
		$this->_response->setBody(Zend_Json::encode($response));
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$this->_helper->layout->disableLayout();
	}

	private function process($data)
	{
		if (!isset($data['token']) || !$data['token']) {
			return [
				'status' => self::STATUS_BAD_REQUEST,
				'message' => 'Missing token'
			];
		}

		$user = My_Model::get('Users')->getByToken($data['token']);
		if (!$user) {
			return [
				'status' => self::STATUS_UNAUTHORIZED,
				'message' => 'Bad token'
			];
		}

		if (!isset($data['date']) || !isset($data['number']) || !isset($data['price'])) {
			return [
				'status' => self::STATUS_BAD_REQUEST,
				'message' => 'Missinq required filed (date, number od price)'
			];
		}

		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data['date'])) {
			return [
				'status' => self::STATUS_BAD_REQUEST,
				'message' => 'Date has bad format, should be YYYY-MM-DD'
			];
		}

		if (!is_numeric($data['price'])) {
			return [
				'status' => self::STATUS_BAD_REQUEST,
				'message' => 'Price should be numeric'
			];
		}

		if (!$data['number']) {
			return [
				'status' => self::STATUS_BAD_REQUEST,
				'message' => 'Missing invoice number'
			];
		}

		$requestId = My_Model::get('Requests')->insert([
			'user_id' => $user->id,
			'request' => json_encode($data)
		]);

		return [
			'status' => self::STATUS_OK,
			'message' => $requestId
		];
	}

	public function runAction()
	{
		$this->view->title = 'Simulace API MFČR';
		if ($this->getParam('export') == "1") {
			$exported = $price = 0;
			$failed = [];
			foreach (My_Model::get('Sales')->getQueued() as $sale) {
				foreach (My_Model::get('Sales')->getProducts($sale->id) as $item) {
					$price += ($item['unit_price'] * $item['amount']);
				}
				$json = json_encode([
					"token" => My_Model::get('Users')->getById($sale->user_id)->api_token,
					"date" => substr($sale->date, 0, 10),
					"number" => $sale->id,
					"price" => $price
				]);
				$client = new Zend_Http_Client($this->view->serverUrl() . $this->view->url(['action' => 'create']));
				$response = json_decode($client->setRawData($json, 'application/json')->request('POST')->getBody(), TRUE);
				if (isset($response['status']) && $response['status'] == self::STATUS_OK) {
					My_Model::get('Sales')->update(
						['exported' => $response['message']],
						['id = ' . $sale->id]
					);
					$exported++;
				} else {
					$failed[$sale->id] = $response['message'];
				}
			}
			$this->view->exported = $exported;
			$this->view->failed = $failed;
		}
		$this->view->ready = My_Model::get('Sales')->getQueued()->count();
	}
}
