<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class AddbookForm extends Model
{
    public $id;
    public $ISBN;
    public $name;
    public $description;
    public $isDamaged;
    public $janers;
    public $autor_id;
    public $user_id;
    public $photo;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [

            [['id','ISBN', 'description', 'isDamaged','photo','janers'], 'safe'],
            [['name','user_id','autor_id'], 'required']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [];
    }



    public function addBook()
    {
        if ($this->validate()) {
            $book=new Book();
            $book->ISBN=$this->ISBN;
            $book->name=$this->name;
            $book->description=$this->description;
            $book->isDamaged=$this->isDamaged;
           // $book->janer_id=$this->janers;
            $book->autor_id=$this->setAutor();
            $book->user_id=$this->user_id;
            if (isset($_FILES["file"])) {
                $book->photo=$this->SaveFiles();
            }else{
                $book->photo='';
            }
            $book->save();
            foreach ($this->janers as $janer) {
                $bookjaner=new BookJaner();
                $bookjaner->janer_id=$janer;
                $bookjaner->book_id=$book->id;
                $bookjaner->save();
            }

            return true;
        }

        return false;
    }

    public function updateBook(){
        if ($this->validate()) {
            $book=Book::find()->where(['id' => $this->id])->one();
            $book->ISBN=$this->ISBN;
            $book->name=$this->name;
            $book->description=$this->description;
            $book->isDamaged=$this->isDamaged;
           // $book->janer_id=$this->janers;
            $book->autor_id=$this->setAutor();
            if (isset($_FILES["file"])) {
                if (isset($book->photo)) {
                    $oldphoto= $book->photo;
                    $search = 'https://russcazak10.ru/web/';
                    $filePath = str_replace($search, '',  $oldphoto);
                    unlink($filePath);
                }
                $book->photo=$this->SaveFiles();
            }
            /*
            $oldphoto= $book->photo;
            
            $oldphoto= $book->photo;
            $search = 'https://russcazak10.ru/web/';
            $filePath = str_replace($search, '',  $oldphoto);
            unlink($filePath);
            $book->photo=$this->SaveFiles();
            */
            $book->save();
           
            foreach ($this->janers as $janer) {
                
                $oldbookjaner=BookJaner::find()->where(['book_id' => $book->id,'janer_id'=>$janer])->all();
                if (!isset($oldbookjaner)) {
                    $bookjaner=new BookJaner();
                    $bookjaner->janer_id=$janer;
                    $bookjaner->book_id=$book->id;
                    $bookjaner->save();
                }
                
            }
            return true;
        }
    }
    public function setAutor(){
        $value = $this->autor_id;
        $autor = Autor::findOne(['id' => $value]);
        if (!isset($towns)) {
            $autor = new Autor(); ////Towns::find()->where(['id'=>1])->count();
            $autor->name_autor = $value;
            $autor->save();
            // return array(
            //     'message'=>'success',
            //     'towns' => $towns,
            // );
        }
        return $autor->id;
    }

    public function deleteBook($id_book){
        $book=Book::find()->where(['id' => $id_book])->one();
        $bookjaner=BookJaner::find()->where(['book_id' => $id_book])->all();
        foreach ($bookjaner as $bj) {
            $bj->delete();
        }
        
        $search = 'https://russcazak10.ru/web/';
        $filePath = str_replace($search, '',  $book->photo);
        unlink($filePath);

        $book->delete();
    }


    public function SaveFiles()
    {
        $files = $_FILES['file'];
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            // Output: 54esmdr0qf 
            $string= substr(str_shuffle($permitted_chars), 0, 10);

            $url = 'https://russcazak10.ru/web/img/'.$string .  $files['name'];
            move_uploaded_file($files['tmp_name'], 'img/'. $string .  $files['name']);

            return $url;
        
    }
}
