<?php

namespace App\Http\Controllers\Admin;

use App\News;
use App\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function newsCategoryIndex(){
        return view('admin.news.category');
    }


    public function newsCategoryList(Request $request){
        $limit = $request->get('limit',10);
        $results = NewsCategory::orderBy('id','desc')->paginate($limit);
        return $this->layuiData($results);
    }


    public function newsCategoryAdd(Request $request){
        $id = $request->get('id',null);
        $result = NewsCategory::findOrNew($id);
        return view('admin.news.category_add')->with(['result'=>$result]);
    }

    public function newsCategoryPostAdd(Request $request){
        $id = $request->get('id',null);
        $name = $request->get('name',null);
        try{
            if (empty($name)){
                return $this->error('请填写完整');
            }
            if (empty($id)){
                $has = NewsCategory::where('name',$name)->first();
                if (!empty($has)){
                    return $this->error('已有该分类');
                }
                $category = new NewsCategory();
            }else{
                $category = NewsCategory::find($id);
            }
            $category->name = $name;
            $category->save();
            return $this->success('操作成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }


    public function newsCategoryPostDel(Request $request){
        $id = $request->get('id',null);
        try{
            if (empty($id)){
                return $this->error('参数错误');
            }
            $category = NewsCategory::find($id);
            if (empty($category)){
                return $this->error('无此记录');
            }

            $category->delete();
            return $this->success('删除成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }


    public function index(){
        return view('admin.news.index');
    }


    public function newsList(Request $request){
        $limit = $request->get('limit',10);
        $results = News::orderBy('id','desc')->paginate($limit);
        return $this->layuiData($results);
    }


    public function newsAdd(Request $request){
        $id = $request->get('id',null);
        $result = News::findOrNew($id);
        return view('admin.news.add')->with(['result'=>$result,'categories'=>NewsCategory::all()]);
    }

    public function postNewsAdd(Request $request){
        $id = $request->get('id',null);
        $title = $request->get('title','');
        $category_id = $request->get('category_id',0);
        $content = $request->get('content',null);
        if (empty($title) || empty($category_id) || empty($content)){
            return $this->error('请填写完整');
        }
        try{
            $news = News::findOrNew($id);
            $news->title = $title;
            $news->category_id = $category_id;
            $news->content = $content;
            if (empty($id)){
                $news->create_time = time();
            }
            $news->save();
            return $this->success('添加成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }


    }


    public function postNewsDel(Request $request){
        $id = $request->get('id',null);
        try{
            if (empty($id)){
                return $this->error('参数错误');
            }
            $category = News::find($id);
            if (empty($category)){
                return $this->error('无此记录');
            }

            $category->delete();
            return $this->success('删除成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
}
