<?php

namespace app\controllers;

use app\models\Sample;

class SampleController extends Controller
{

	/**
	 * @api {post} /sample/something
	 * @apiName something
	 * @apiGroup sample
	 * @apiVersion 0.0.1
	 * @apiDescription Here you can write a documentation for your API, and later with the "apidoc" command make it nice (read on apidocjs.com)
	 *
	 * @apiParam {String} string_param here you've got some description
	 * @apiParam {Array} friends some another nice description
	 *
	 * @apiExample
	 *	{
	 *		uid: "6709810",
	 *		auth_key: "2c01c44ec206a65c99ffb0d3ee3bad63",
	 *		string_param: "767sd877dfs4",
	 *		friends: "7678774,324353,4825225,8981008"
	 *	}
	 * 
	 * @apiSuccess {Some type} friends_virality Some another nice description
	 *
	 * @apiSuccessExample
	 * {
	 *		response: {
	 *			data: {
	 *  			friends_virality: [
	 *					{
	 *						uid: 7678774,
	 *						virality: 89,
	 *						last_invited: 1416225351
	 *					},
	 *					{
	 *						uid: 324353,
	 *						virality: 55,
	 *						last_invited: 1416239351
	 *					},
	 *					{
	 *						uid: 4825225,
	 *						virality: 32,
	 *						last_invited: 1416229354
	 *					},
	 *					{
	 *						uid: 8981008,
	 *						virality: 14,
	 *						last_invited: 1416229378
	 *					}
	 *  			]
	 *			}
	 *		}
	 * }
	 *
	 * @apiError WrongAuthKey wrong auth_key
	 * @apiError InsufficientInputParameters there is no invited_id
	 * @apiError UserNotFound uid, which was passed, not found in users table
	 */
	public function actionSomething()
	{
		// input parameters: string_param

			list($string_param) = $this->checkInputParameters(['string_param']); // you can use $this->checkInputParameters - it automatically throws right errors if some params are missing

			$friends = "";
			if (isset($_POST['friends']))
			{
				$friends = $_POST['friends'];
			} else {
				$friends = $this->getUser()->friends_list; // for those parameters which are not necessary
			}

		$data = [];

		// ... some real action

		$this->renderJSON([		// use renderJSON because it also does logging for you
			'response' => [
				'data' => $data
			]
		]);
	}
}