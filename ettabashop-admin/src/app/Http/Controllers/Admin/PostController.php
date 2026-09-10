<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\post\PostInsertRequest;
use App\Models\Post;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Mockery\Exception;

class PostController extends Controller
{
    protected $model="Post";
    private $globalObject ;
    private  $moduleName="Post";
    private $singularVariableName = 'post';
    private $pluralVariableName = 'posts';

    private $retrievedDataList;
    private $singleData;


    public function __construct()
    {
        $this->globalObject = new Post();
    }

    public function index()
    {

        return view('admin.posts.index',['posts'=>$this->globalObject->all()]);
    }
    public function create()
    {
        return view('admin.posts.create',[
            'categories'=>Category::all()
        ]);
    }
    public function edit(Post $post,$id)
    {
        $post = Post::findOrFail($id);

        return view('admin.posts.edit',[
            'post'=>$post ,
            'categories'=>Category::all()
        ]);
    }
    public function store(PostInsertRequest $request)
    {
        try
        {
            $post = new Post();
            if (Post::create($post->PostData($request->all())))
            {
                return redirect()->back()->with(['success'=>"Post created successfully"]);
            }
        }
        catch (\Illuminate\Database\QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);

    }
    public function update(Request $request){
        try
        {
            $post = new Post();
            $oldPost = Post::findOrFail($request->id);
            if ($oldPost->update($post->PostData($request->all())))
            {
                return redirect()->back()->with(['success'=>"Post updated successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to update"]);

        }
        catch (\Illuminate\Database\QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }
    }
    public function delete($id){
        try{
            if (Category::destroy($id)){
                return redirect()->back()->with(['success'=>"Category deleted successfully"]);
            }
        }
        catch (QueryException $exception){
            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
    }

    public function Like(Request $request)
    {
        $post = new Post();
        $likes =$post->UpdateAndGetLikes($request->id);
        if($likes!=0){
            return response()->json([
                'status'=>'ok',
                'likes'=>$likes
            ]);
        }
    }

}

