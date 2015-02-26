<?php

namespace app\controllers;

use app\models\Users;
use app\models\LogRequests;
use app\models\LogSession;
use app\filters\HelloWorldAuth;

use app\components\Security;

class Controller extends \yii\rest\Controller
{
	protected $uid;

	private $encrypted;

	protected $input_parameters;

	public function __get($key)
	{
		return $this->input_parameters[$key];
	}

	public function post($key, $default=NULL)
	{
		if (isset($this->$key))
			return $this->$key;
		else
			return $default;
	}

	public function __construct($id, $module, $config = [])
    {
        $this->uid = \Yii::$app->request->getAuthUser();
        parent::__construct($id, $module, $config);
    }

	public function behaviors()
	{
		if (\Yii::$app->controller->id == 'register')
			return [
				'corsFilter' => [
					'class' => \yii\filters\Cors::className(),
				],
				// 'rateLimiter' => [
				// 	'class' => \yii\filters\RateLimiter::className(),
				// ],
			];
		else
			return [
				'authenticator' => [
					'class' => HelloWorldAuth::className(),
				],
				'corsFilter' => [
					'class' => \yii\filters\Cors::className(),
				],
				// 'rateLimiter' => [
				// 	'class' => \yii\filters\RateLimiter::className(),
				// ],
			];
	}

	private function udate($format, $utimestamp = null)
	{
		if (is_null($utimestamp))
			$utimestamp = microtime(true);
	 
		$timestamp = floor($utimestamp);
		$milliseconds = round(($utimestamp - $timestamp) * 1000000);
	 
		return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
	}

	/**
	 * Return data to browser as JSON and end application.
	 * @param array $data
	 */
	protected function renderJSON($data)
	{
		header('Content-type: application/json');

		$data['time'] = time();

		echo json_encode([
			'data' => $this->encrypted?Security::encrypt(json_encode($data),\Yii::$app->user->identity->key):json_encode($data),
			'time'=>\Yii::getLogger()->getElapsedTime()*1000
		]);

		// logging
			\Yii::info("\n" . json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), 'api');

			\Yii::info(json_encode([
					'timestamp' => $this->udate('Y-m-d\Th:i:s.u\Z'),
					// 'cpu' => $cpu,
					'total_time_ms' => \Yii::getLogger()->getElapsedTime()*1000,
					'total_db_queries_count' => \Yii::getLogger()->getDbProfiling()[0],
					'total_db_queries_time_ms' => \Yii::getLogger()->getDbProfiling()[1]*1000,
				]), \Yii::$app->request->url);
			\Yii::endProfile("apiTotalTimeBenchmark \n\tTotal time: " . \Yii::getLogger()->getElapsedTime() . "\n\tAll profiling results:\n" . json_encode(\Yii::getLogger()->getProfiling(), JSON_PRETTY_PRINT), \Yii::$app->request->url);

		exit;
	}

	public function beforeAction($action)
	{
		parent::beforeAction($action);

        // decoding input parameters
	        if (isset($_POST['data']))
	        {
	        	if ($data = json_decode($_POST['data']))
	        	{
	        		// unencrypted
	        		$this->encrypted = false;
	        		$this->input_parameters = json_decode($data);
	        	} else {
	        		$this->encrypted = true;
	        		$user = \Yii::$app->user->identity;
	        		$this->input_parameters = json_decode(Security::decrypt($data, $user->key));
	        	}
	        } else {
	        	$this->encrypted = false;
	        }
		
		// start profiling
			\Yii::beginProfile('apiTotalTimeBenchmark');

		// to measure how many requests per minute we have
			$log = new LogRequests();
			$log->timestamp = microtime(true);
			$log->save();

		// checking all mandatory parameters for all requests
			// if (!isset($_POST['uid']) || (($this->uid = $_POST['uid']) == ''))
			// {
			// 	echo json_encode([
			// 		'error' => [
			// 			'code' => InsufficientInputParameters,
			// 			'msg' => 'uid is not set'
			// 		]
			// 	]);
			// 	return false;
			// }

		return true;
	}

	protected function checkInputParameters($names)
	{
		$vars = [];

		foreach ($names as $name)
		{
			if (!isset($this->$name) || ((${$name} = $this->$name) == ''))
			{
				$this->error(InsufficientInputParameters, $name . ' is not set');
			}
			$vars[] = ${$name};
		}

		return $vars;
	}

	protected function getUser()
	{
		if (!$user_model = \Yii::$app->user->identity /*Users::findOne(['user_id'=>$this->uid])*/)
		{
			$this->error(UserNotFound, 'user with ID ' . $this->uid . ' is not found in users table');
		}
		return $user_model;
	}

	protected function error($code, $msg)
	{
		$data = [
					'code' => $code,
					'msg' => $msg
				];
		echo json_encode([
			'error' => $this->encrypted?Security::encrypt(json_encode($data),\Yii::$app->user->identity->key):json_encode($data),
			'time'=>\Yii::getLogger()->getElapsedTime()*1000
		]);
	}
}