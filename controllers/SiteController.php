<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionGetlocation()
    {
        //  Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        //  $request = Yii::$app->request;
//         $nameCity = "Челябинск";
//         $adress = "г Челябинск, ул Цвиллинга, д 5";
//         $namePlace = "Plove";
//         $url = 'https://geocode-maps.yandex.ru/1.x/?apikey=818ec6cb-2c8f-4fb5-9221-5c81d7038206&geocode=' . $nameCity . ',' . $adress . ',' . $namePlace . '&format=json';
//         // $ch = curl_init($url);
//         // curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true); //CURLOPT_POSTFIELDS
//         // $response = curl_exec($ch);
//         // $info = curl_getinfo($ch);
//         // curl_close($ch);
//         // $res = json_decode($response, true);

           

// //https://geocode-maps.yandex.ru/1.x/?apikey=818ec6cb-2c8f-4fb5-9221-5c81d7038206&geocode=Челябинск,г Челябинск, ул Цвиллинга, д 5,Plove&format=json
//         $ch = curl_init($url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_HEADER, false);
//         $response = curl_exec($ch);
//         curl_close($ch);
//         $res = json_decode($response, true);
//         // $resultat=array('res'=>$res,'info'=>$info);
//         echo $res;
//         echo $response;
//         return $this->render('location', ["res" => $res, 'response' => $response]); //$resultat;
    }
}
