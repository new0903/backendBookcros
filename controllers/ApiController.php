<?php

namespace app\controllers;

use app\models\AddbookForm;
use app\models\Autor;
use app\models\Book;
use app\models\BookJaner;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Towns;
use app\models\User;
use app\models\Imgplace;
use app\models\Janer;
use app\models\Userbook;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter

        // add CORS filter
        
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'login' => ['POST', 'OPTIONS'],
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
    public function actionAdduser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            // $iduser=$request->post('id');
            // $photo_200=$request->post('photo_200');
            // $first_name=$request->post('first_name');
            // $last_name=$request->post('last_name');
            $id = $request->post('id');
            $user = User::find()->where(['id' => $id])->one();
            if (!isset($user)) {
                $user = new User();
            }
            $user->id = $id;
            $user->photo_200 = $request->post('photo_200');
            $user->first_name = $request->post('first_name');
            $user->last_name = $request->post('last_name');
            //    $user->save();
            $value = $request->post("town");
            $towns = Towns::findOne(['id' => $value]);
            if (!isset($towns)) {
                $towns = new Towns();
                $towns->label = $value;
                $towns->save();
            }
            //$user=User::findOne(['id'=>$$user->id]);
            $user->town_id = $towns->id;
            $user->save();
            /* текущий запрос является POST запросом */
        }
        return ['message' => 'user add in dataBase'];
    }

    public function actionGetuser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {
            $id = $request->get('id');
            $user = User::find()->where(['id_vkontakte' => $id])->one();
            $town = Towns::find()->where(['id' => $user->town_id])->one();
            if (!isset($user)) {
                return ['message' => 'user unknow'];
            }
            return [
                'userData' => [
                    'id' => $user->id,
                    'photo_200' => $user->photo_200,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'town' => $town,
                ]
            ];
            /* текущий запрос является POST запросом */
        }
        return ['error' => 'error'];
    }

    public function actionGetbook()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {
            if ($request->get('idBook')) {
                $id = $request->get('idBook');
                $book = Book::find()->where(['id' => $id])->one();
                $userinfo = User::find()->where(['id_vkontakte' => $book->user_id])->one();
              //  $janer = Janer::find()->where(['id' => $book->janer_id])->one();
                $autor = Autor::find()->where(['id' => $book->autor_id])->one();
                $bookjaner=BookJaner::find()->where(['book_id' => $book->id])->all();
                $town=Towns::find()->where(['id'=>$book->town_id])->one();
                return [
                    'response' => [
                        'id' => $book->id,
                        'ISBN' => $book->ISBN,
                        'name' => $book->name,
                        'description' => $book->description,
                        'photo' => $book->photo,
                        'isDamaged' => $book->isDamaged,
                        'autor' => $autor,
                        'janer' => $bookjaner,
                        'userInfo' => $userinfo,
                        'town' => $town,
                    ]
                ];
            }
            $books = Book::find()->all();
            $res=[];
            foreach ($books as $book) {
                $userinfo = User::find()->where(['id_vkontakte' => $book->user_id])->one();
             //   $janer = Janer::find()->where(['id' => $book->janer_id])->one();
                $autor = Autor::find()->where(['id' => $book->autor_id])->one();
                $bookjaner=BookJaner::find()->where(['book_id' => $book->id])->all();
                $town=Towns::find()->where(['id'=>$book->town_id])->one();
                $res[] = [
                    'id' => $book->id,
                    'ISBN' => $book->ISBN,
                    'name' => $book->name,
                    'description' => $book->description,
                    'photo' => $book->photo,
                    'isDamaged' => $book->isDamaged,
                    'autor' => $autor,
                    'janer' => $bookjaner,
                    'userInfo' => $userinfo,
                    'town' => $town,

                ];
            }
            return ['response'=>$res];
        }
        return ['message' => 'error'];
    }
    public function actionGetjaner(){
        
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {

            $janer = Janer::find()->all();
            return ['response'=>$janer];
        }
        return ['message' => 'error'];
    }
    public function actionGetautor(){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {

            $autor = Autor::find()->all();
            return ['response'=>$autor];
        }
        return ['message' => 'error'];
    }
    public function actionAddbook(){
        
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            
            $model = new AddbookForm();
            $model->id=$request->post('id');
            $model->ISBN=$request->post('ISBN');
            $model->name=$request->post('name');
            $model->description=$request->post('description');
            $model->isDamaged=$request->post('isDamaged');
           // $model->photo=$request->post('photo');
                

            $model->janers=$request->post('janers');
            /*
            в multipart записывать так test[] 
            передавть id janer
            */



            $model->autor_id=$request->post('autor_id');
            $model->user_id=$request->post('user_id');
            $model->addBook();
            
            $res = array(
                'message' => 'success',
                $_FILES,
                $_POST,
                'model' => [$model]
            );
            return $res; 
        }

    }

    public function actionUpdatebook(){
        
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost) {
            
            $model = new AddbookForm();
            $model->id=$request->post('id');
            $model->ISBN=$request->post('ISBN');
            $model->name=$request->post('name');
            $model->adress=$request->post('adress');
            $model->description=$request->post('description');
            $model->isDamaged=$request->post('isDamaged');
            $model->photo=$request->post('photo');


            $model->janers=$request->post('janers');
            /*
            в multipart записывать так test[] 
            передавть id janer
            */


            $model->autor_id=$request->post('autor_id');
            $model->user_id=$request->post('user_id');
            $model->updateBook();
            $res = array(
                'message' => 'success',
                $_FILES,
                $_POST,
                'model' => [$model]
            );
            return $res; 
        }

    }
    public function actionDeletebook(){
        
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {
            $id_book=$request->get('idBook');
            $model = new AddbookForm();
            $model->deleteBook($id_book);
            $res = array(
                'message' => 'success'
            );
            return $res; 
        }

    }

    public function actionUploadfile()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isPost) {
           $urls= $this->SaveFile();
            $res = array(
                $urls,
             //   'message' => 'success',
             //   'files' => $_FILES,
             //   $_POST
            );
            return $urls;
        }

        return array(
            'message' => 'error',
        );
    }

    public function SaveFile()
    {
        //if ($_FILES['files']) {
        $files = $_FILES['files'];
        $count=count($_FILES['files']['name']);
        $url=array();
        for ($i=0; $i < $count; $i++) { 

            $imgs = new Imgplace();
            $imgs->id_place=1;
            $imgs->id=1;
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            // Output: 54esmdr0qf 
            $string= substr(str_shuffle($permitted_chars), 0, 10);
            $imgs->url='https://russcazak10.ru/web/img/'. $i."_".$string .  $files['name'][$i];
            $url[]=$imgs;//'https://russcazak10.ru/web/img/'. $i."_".$string .  $files['name'][$i];
           // array_push($url,'https://russcazak10.ru/web/img/'. $i."_".$string .  $this->files['name'][$i]);
       //     $url = 'https://russcazak10.ru/web/img/'. $i."_".$string .  $this->files['name'][$i];
            move_uploaded_file($files['tmp_name'][$i], 'img/'. $i."_".$string .  $files['name'][$i]);

        }
        return $url;
    }
    
    public function actionGettowns()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {
            $towns = Towns::find()->asArray()->all();

            return array(
                'towns' => $towns,
            );
        }
        return array(
            'message' => 'error',
        );
    }
    public function actionAddtowns()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isGet) {
            $value = $request->get("value");
            $user_id = $request->get("userid");
            $towns = Towns::findOne(['id' => $value]);
            if (!isset($towns)) {
                $towns = new Towns(); ////Towns::find()->where(['id'=>1])->count();
                $towns->label = $value;
                $towns->save();
                // return array(
                //     'message'=>'success',
                //     'towns' => $towns,
                // );
            }
            $user = User::findOne(['id_vkontakte' => $user_id]);
            $user->town_id = $towns->id;
            $user->save();
            return array(
                'message' => 'success',
                'towns' => $towns,
            );
        }
        return array(
            'message' => 'error',
        );
    }


}
