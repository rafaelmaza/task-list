<?php
/**
 * Part of the code is borrowed from http://www.yiiframework.com/wiki/175/how-to-create-a-rest-api/
 */
class ApiController extends Controller
{
	
	private $_session;
	
	public function __construct($id, $module = null) {
		$this->_session=new CDbHttpSession();
		$this->_session->connectionID='db';
		$this->_session->sessionTableName='api_session';
		$this->_session->autoCreateSessionTable=true;
		$this->_session->setCookieMode('none');
		parent::__construct($id, $module);
	}
	
	public function actionIndex() {
		$this->redirect($this->createUrl('site/page', array('view'=>'api_reference')));
	}
	
	public function actionCreate()
	{
		switch($_GET['model']) {
			case 'user':
				$model = new User('create'); 
				break;
			case 'task':
				$model = new Task; 
				break;			
			default:	
				$this->_sendResponse(501, Yii::t('tasklist', 'Mode "create" is not implemented for model "{model}"', array(
					'{model}'=>$_GET['model']
				)));
                exit;
		}
		
		foreach($_POST as $var=>$value) {
            if($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500, Yii::t('tasklist', 'Parameter "{param}" is not allowed', array(
					'{param}'=>$var
				)));
            }
        }
        
        if($model->save()) {			
            $this->_sendResponse(200, CJSON::encode($model->attributes));
        } else {
            $this->_sendResponse(500, CJSON::encode($model->errors));
        }
	}

	public function actionDelete()
	{
		switch($_GET['model']) {
			case 'task':
				$model = Task::model()->findByPk($_GET['id']);
				break;
			default:	
				$this->_sendResponse(501, Yii::t('tasklist', 'Mode "delete" is not implemented for model "{model}"', array(
					'{model}'=>$_GET['model']
				)));
                exit;
		}
		
        if(is_null($model)) {
            $this->_sendResponse(404, Yii::t('tasklist', 'Couldn\'t find any {model} with id "{id}"', array(
				'{model}'=>$_GET['model'],
				'{id}'=>$_GET['id'],
			)));
        }

        if($model->delete()>0)
            $this->_sendResponse(200, Yii::t('tasklist', '{model} with id "{id}" has been deleted', array(
				'{model}'=>ucfirst($_GET['model']),
				'{id}'=>$_GET['id'],
			)));
        else
            $this->_sendResponse(500, Yii::t('tasklist', 'Couldn\'t delete {model} with id "{id}"', array(
				'{model}'=>$_GET['model'],
				'{id}'=>$_GET['id'],
			)));
	}

	public function actionList()
	{
		switch($_GET['model']) {
			case 'task':
				$models = Task::model()->findAllByAttributes(array(
					'user_id'=>$this->_session['user_id']
				));
				break;
			default:
				$this->_sendResponse(501, Yii::t('tasklist', 'Mode "list" is not implemented for model "{model}"', array(
					'{model}'=>$_GET['model']
				)));
                exit;
		}
		
		if (empty($models)) {
			$this->_sendResponse(200, Yii::t('tasklist', 'No {model}s were found.', array(
				'{model}'=>$_GET['model']
			)));
		} else {
			$rows = array();
			foreach ($models as $model)
				$rows[] = $model->attributes;
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionUpdate()
	{
		parse_str(file_get_contents('php://input'), $put_vars);
		
		switch($_GET['model']) {
			case 'user':
				$model = User::model()->findByPk($_GET['id']); 
				break;
			case 'task':
				$model = Task::model()->findByPk($_GET['id']); 
				break;
			default:	
				$this->_sendResponse(501, Yii::t('tasklist', 'Mode "update" is not implemented for model "{model}"', array(
					'{model}'=>$_GET['model']
				)));
                exit;
		}
		
		if(is_null($model)) {
            $this->_sendResponse(404, Yii::t('tasklist', 'Couldn\'t find any {model} with id "{id}"', array(
				'{model}'=>$_GET['model'],
				'{id}'=>$_GET['id'],
			)));
        }
		
		foreach($put_vars as $var=>$value) {
            if($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500, Yii::t('tasklist', 'Parameter "{param}" is not allowed', array(
					'{param}'=>$var
				)));
            }
        }
        
        if($model->save()) {			
            $this->_sendResponse(200, Yii::t('tasklist', '{model} with id "{id}" has been updated', array(
				'{model}'=>ucfirst($_GET['model']),
				'{id}'=>$_GET['id'],
			)));
        } else {
            $this->_sendResponse(500, CJSON::encode($model->errors));
        }
	}

	public function actionView()
	{
		if (!isset($_GET['id']))
			$this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');
		
		switch($_GET['model']) {
			case 'user':
				$model = User::model()->findByPk($_GET['id']); 
				unset($model->password);
				break;
			case 'task':
				$model = Task::model()->findByPk($_GET['id']); 
				break;
			default:	
				$this->_sendResponse(501, Yii::t('tasklist', 'Mode "view" is not implemented for model "{model}"', array(
					'{model}'=>$_GET['model']
				)));
                exit;
		}
		
		if (is_null($model))
			$this->_sendResponse(404, Yii::t('tasklist', 'Couldn\'t find any {model} with id "{id}"', array(
				'{model}'=>$_GET['model'],
				'{id}'=>$_GET['id'],
			)));
		else
			$this->_sendResponse(200, CJSON::encode($model->attributes));
	}
	
	public function actionAuth()
	{
		$_username=Yii::app()->getRequest()->getParam('username');
		$_password=Yii::app()->getRequest()->getParam('password');
		
		$identity=new UserIdentity($_username, $_password);
		$identity->authenticate();

		if($identity->errorCode==UserIdentity::ERROR_NONE) {
			$this->_session->regenerateID(true);
			$this->_session->open();
			$this->_session['user_id']=User::model()->findByAttributes(array('username'=>$_username))->user_id;
			$this->_sendResponse(200, CJSON::encode(array(
				'token'=>$this->_session->getSessionID(),
			)));
		}
		else {
			$this->_sendResponse(404, Yii::t('tasklist', 'Incorrect username or password.'));
		}
	}
	
	private function _getStatusCodeMessage($status)
    {
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }
	
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        // set the status
        header($status_header);
        // set the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if($body != '')
        {
            // send the body
            echo $body;
            exit;
        }
        // we need to create the body if none is passed
        else
        {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status)
            {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                            </head>
                            <body>
                                <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                                <p>' . $message . '</p>
                                <hr />
                                <address>' . $signature . '</address>
                            </body>
                        </html>';

            echo $body;
            exit;
        }
    }

	
	public function filterAuthenticate($filterChain) {
		$sessionId=Yii::app()->getRequest()->getParam('token');
		$this->_session->setSessionID($sessionId);
		$this->_session->open();
		
		if(!$this->_session['user_id']) {
			$this->_session->destroy();
			$this->_sendResponse(404, Yii::t('tasklist', 'Your session could not be restored. Please double-check your token'));
		}
		else {
			User::model()->updateByPk($this->_session['user_id'], array('last_login'=>date('Y-m-d H:i:s')));
			$filterChain->run();
		}
	}
	
	public function filters()
	{
		return array(
			'authenticate - auth, index',
		);
	}

	/*
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}