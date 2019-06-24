<?php

namespace App\Http\Controllers\Admin;

use App\AccountLog;
use App\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountLogController extends Controller
{
    public function index(){
        $types = AccountLog::TYPE_LIST;
        return view('admin.account.index')->with('types',$types);
    }


    public function lists(Request $request){
        $limit = $request->get('limit',10);
        $search_mobile = $request->get('search_mobile',null);
        $search_type = $request->get('search_type',null);
        $results = new AccountLog();
        if (!empty($search_mobile)){
            $users = Users::where('mobile','like','%'.$search_mobile.'%')->pluck('id')->toArray();
            $results = $results->whereIn('user_id',$users);
        }
        if (!empty($search_type)){
            $results = $results->where('type',$search_type);
        }
        $results = $results->orderBy('id','desc')->paginate($limit);
        return $this->layuiData($results);
    }
}
