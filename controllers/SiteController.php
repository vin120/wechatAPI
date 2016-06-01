<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\Wechat;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
    	$code =  Yii::$app->request->get('code');
    	
    	if(isset($code)){
    		$obj_token = new Wechat();
    		$userinfo = $obj_token->GetUserInfo($code);
			if(!is_array($userinfo)){
				echo $userinfo;
				exit;
			}
			
    		$user_openid = $userinfo['openid'];
    		$user_nickname = $userinfo['nickname'];
    		$user_sex = $userinfo['sex'];
    		$user_language = $userinfo['language'];
    		$user_city = $userinfo['city'];
    		$user_province = $userinfo['province'];
    		$user_country = $userinfo['country'];
    		$user_headimgurl = $userinfo['headimgurl'];
    		$user_privilege = $userinfo['privilege'];
    		
    		echo "your openid :".$user_openid."<br>".
      			"your nickname :".$user_nickname."<br>".
      			"your sex :".$user_sex."<br>".
      			"your language :".$user_language."<br>".
      			"your city :".$user_city."<br>".
      			"your province :".$user_province."<br>".
      			"your country :".$user_country."<br>".
      			"your headimgurl :".$user_headimgurl."<br>";
      		foreach ($user_privilege as $key => $value){
      			echo "user_privielge".$key."  :".$value;
      		}
 
    	}else{
    		echo "Get code failed,try again please.";
    	}

    	
    	exit;
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

}
