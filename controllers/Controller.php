<?php

namespace app\controllers;

use app\models\Users;
use app\models\LogRequests;
use app\models\LogSession;
use app\filters\HelloWorldAuth;

class Controller extends \yii\rest\Controller
{
	protected $uid;

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

		echo json_encode(array_merge($data,['time'=>\Yii::getLogger()->getElapsedTime()*1000]));

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
		if (\Yii::$app->request->isPost)
		{
			foreach ($names as $name)
			{
				if (!isset($_POST[$name]) || ((${$name} = $_POST[$name]) == ''))
				{
					$this->error(InsufficientInputParameters, $name . ' is not set');
				}
				$vars[] = ${$name};
			}
		} else if (\Yii::$app->request->isGet)
		{
			foreach ($names as $name)
			{
				if (!isset($_GET[$name]) || ((${$name} = $_GET[$name]) == ''))
				{
					$this->error(InsufficientInputParameters, $name . ' is not set');
				}
				$vars[] = ${$name};
			}
		} else if (\Yii::$app->request->isDelete)
		{
			foreach ($names as $name)
			{
				if (!isset($_GET[$name]) || ((${$name} = $_GET[$name]) == ''))
				{
					$this->error(InsufficientInputParameters, $name . ' is not set');
				}
				$vars[] = ${$name};
			}
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
		$this->renderJSON([
				'error' => [
					'code' => $code,
					'msg' => $msg
				]
			]);
	}
}