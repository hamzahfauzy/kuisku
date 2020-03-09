<?php
namespace App\Controllers;
use App\Models\{Soal,Jawaban};
use Category;
use PostMeta;
use CategoryPost;
use CategoryUser;
use SpreadsheetReader;

class SoalController
{
    function index()
    {
        $category_user = CategoryUser::where('user_id',session()->user()->id)->get();
        $categories = [];
        foreach($category_user as $category)
            $categories[] = $category->category();
        return ['categories' => $categories];
    }

    function get()
    {
        $questions = Soal::where('post_author_id',session()->get('id'))->get();
        foreach($questions as $question)
        {
            $question->categories();
            $question->answers();
        }
        
        return ['questions' => $questions];
    }

    function find($id)
    {
        $question = Soal::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        $question->categories();
        return $question;
    }

    function findAnswer($id)
    {
        $question = Soal::where('id',$id)->where('post_author_id',session()->get('id'))->first();
        return $question->answers();
    }

    function insert()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'post_title'   => ['required'],
                'post_content' => ['required'],
                'categories'     => ['required'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $question = new Soal;
                $question_id = $question->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_status'    => 1,
                    'post_as'        => 'Pilihan Berganda',
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                foreach($request->categories as $category)
                {
                    $cat = new CategoryPost;
                    $cat->save([
                        'category_id' => $category,
                        'post_id' => $question_id,
                    ]);
                }
                return $this->get();
            }
            
        }

        return ['status' => false];
    }

    function getAnswer($id)
    {
        return Jawaban::find($id);
    }

    function insertAnswer()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'post_parent_id' => ['required'],
                'post_content'   => ['required'],
            ];

            $request->skor = $request->skor ? $request->skor : 0;

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                if($request->jawaban_id != 0)
                    $question = Jawaban::where('id',$request->jawaban_id)->first();
                else
                    $question = new Jawaban;
                $question_id = $question->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $request->post_content,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_parent_id' => $request->post_parent_id,
                    'post_status'    => 1,
                    'post_as'        => $request->skor,
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',
                ]);

                return ['status' => true];
            }
            
        }

        return ['status' => false];
    }

    function update()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'post_title' => ['required'],
                'post_content' => ['required'],
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $question = Soal::find($request->id);
                $question->save([
                    'post_title'     => $request->post_title,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                $cat = CategoryPost::where('post_id',$request->id)->get();
                foreach($cat as $v)
                    CategoryPost::delete($v->id);

                foreach($request->categories as $category)
                {
                    $cat = new CategoryPost;
                    $cat->save([
                        'category_id' => $category,
                        'post_id' => $request->id,
                    ]);
                }
                return $this->get();
            }
            
        }

        return ['status' => false];
    }

    function updateAnswer()
    {
        $request = request()->post();
        if($request)
        {
            $jawaban    = Jawaban::where('id',$request->id)->where('post_author_id',session()->get('id'))->first();
            if($jawaban->post_as == 'Jawaban Salah')
            {

                $allJawaban = Jawaban::where('post_parent_id',$jawaban->post_parent_id)->where('post_author_id',session()->get('id'))->get();
                foreach($allJawaban as $jwb)
                {
                    $jwb->save([
                        'post_as' => 'Jawaban Salah'
                    ]);
                }
                    
                $jawaban->save([
                    'post_as' => 'Jawaban Benar'
                ]);
            }
            else
            {
                $jawaban->save([
                    'post_as' => 'Jawaban Salah'
                ]);
            }

            return ['status' => true];
            
        }

        return ['status' => false];
    }

    function delete()
    {
        $request = request()->post();
        if($request)
        {
            Soal::delete($request->id);
            return $this->get();
        }

        return ['status' => false];
    }

    function deleteAnswer()
    {
        $request = request()->post();
        if($request)
        {
            Jawaban::delete($request->id);
            return ['status' => true];
        }

        return ['status' => false];
    }

    function imageUpload()
    {
        if(isset($_FILES['upload']['name']))
        {
            $file = $_FILES['upload']['tmp_name'];
            $file_name = $_FILES['upload']['name'];
            $file_name_array = explode(".", $file_name);
            $extension = end($file_name_array);
            $new_image_name = rand() . '.' . $extension;
            chmod('uploads', 0777);
            $allowed_extension = array("jpg", "gif", "png");
            if(in_array($extension, $allowed_extension))
            {
                move_uploaded_file($file, 'uploads/' . $new_image_name);
                $function_number = $_GET['CKEditorFuncNum'];
                $url = base_url().'/uploads/' . $new_image_name;
                $message = '';
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
            }
        }
    }

    function importSoal()
    {
        $file      = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_name_array = explode(".", $file_name);
        $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $extension = end($file_name_array);
        // if(in_array($_FILES["file"]["type"],$allowedFileType)){
            $request = request()->post();
            $new_file_name  = time() . "" . rand() . '.' . $extension;
            $targetPath = 'uploads/'.$new_file_name;
            move_uploaded_file($file, $targetPath);
            $Reader    = new SpreadsheetReader($targetPath);

            $Sheets = $Reader->Sheets();
            $ret = [];
            $customer = session()->user()->customer();
            $Reader->ChangeSheet(0);
            foreach($Reader as $key => $row)
            {
                if($key == 0) continue;
                $deskripsi = $row[0];
                $kategori  = $row[1];
                $jawaban_1 = $row[2];
                $jawaban_2 = $row[3];
                $jawaban_3 = $row[4];
                $jawaban_4 = $row[5];

                $excerpt  = strWordCut($deskripsi,100);
                $question = new Soal;
                $question_id = $question->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => 'Post Soal',
                    'post_content'   => $deskripsi,
                    'post_excerpt'   => $excerpt,
                    'post_status'    => 1,
                    'post_as'        => 'Pilihan Berganda',
                    'post_date'      => 'CURRENT_TIMESTAMP',
                    'post_modified'  => 'CURRENT_TIMESTAMP',

                ]);

                for($i=1;$i<=4;$i++)
                {
                    $jwb_excerpt = strWordCut(${'jawaban_'.$i},100);
                    $answer = new Jawaban;
                    $answer->save([
                        'post_author_id' => session()->get('id'),
                        'post_title'     => ${'jawaban_'.$i},
                        'post_content'   => '<p>'.${'jawaban_'.$i}.'</p>',
                        'post_excerpt'   => ${'jawaban_'.$i},
                        'post_parent_id' => $question_id,
                        'post_status'    => 1,
                        'post_as'        => $i == 1 ? 1 : 0,
                        'post_date'      => 'CURRENT_TIMESTAMP',
                        'post_modified'  => 'CURRENT_TIMESTAMP',
                    ]);
                }

                $categories = Category::where('category_name','LIKE','%'.$kategori.'%')->get();
                if(empty($categories))
                {
                    $category = new Category;
                    $category_id = $category->save([
                        'category_name' => $kategori,
                        'category_description' => $kategori
                    ]);

                    $category_user = new CategoryUser;
                    $category_user->save([
                        'user_id' => session()->user()->id,
                        'category_id' => $category_id
                    ]);
                }
                else
                {
                    $_category = 0;
                    foreach($categories as $category)
                    {
                        if($category->user()->user_id == session()->user()->id)
                        {
                            $_category = $category;
                            break;
                        }
                    }

                    if(is_object($_category))
                        $category_id = $_category->id;
                    else
                    {
                        $category = new Category;
                        $category_id = $category->save([
                            'category_name' => $kategori,
                            'category_description' => $kategori
                        ]);

                        $category_user = new CategoryUser;
                        $category_user->save([
                            'user_id' => session()->user()->id,
                            'category_id' => $category_id
                        ]);
                    }
                        
                }

                $cat = new CategoryPost;
                $cat->save([
                    'category_id' => $category_id,
                    'post_id' => $question_id,
                ]);
            }
            return ['status'=>true];
        // }
        // return ['status'=>false];
    }
}
