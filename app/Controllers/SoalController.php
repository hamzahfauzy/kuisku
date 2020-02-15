<?php
namespace App\Controllers;
use App\Models\{Soal,Jawaban};
use Category;
use CategoryPost;

class SoalController
{
    function index()
    {
        $questions = Soal::where('post_author_id',session()->get('id'))->get();
        foreach($questions as $question)
            $question->categories();
        $categories = Category::get();
        return ['questions' => $questions, 'categories' => $categories];
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
                return $this->index();
            }
            
        }

        return ['status' => false];
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

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $excerpt  = strWordCut($request->post_content,100);
                $question = new Jawaban;
                $question_id = $question->save([
                    'post_author_id' => session()->get('id'),
                    'post_title'     => $request->post_content,
                    'post_content'   => $request->post_content,
                    'post_excerpt'   => $excerpt,
                    'post_parent_id' => $request->post_parent_id,
                    'post_status'    => 1,
                    'post_as'        => 'Jawaban Salah',
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
                return $this->index();
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
            return $this->index();
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
}
