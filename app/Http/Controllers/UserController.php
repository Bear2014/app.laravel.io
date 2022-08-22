<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use App\models\User;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /**
     * 添加测试方法
     */
    public function test() {
         echo "larave-app " .  \Route::current() ->getActionName();
    }

    /**
     * 随机添加10W条数据
     * @param Request $request
     * @param int $num 插入记录的条数
     * @return json
     */
    public function addRandData(Request $request) {
        $num = $request->post('num');
        if(!is_numeric($num) || $num <= 0 || $num > 1000) {
            return $this->fail( 1,'param num err');
        }

        $data = array();
        for($i=0;$i<$num;$i++) {
            $rand = random_int(15,95);
            $data[] = [
                    "name" => "abo".$rand,
                    "age" => $rand
            ];
        }
        //DB::table("user")->insert($data);
        return $this->success('数据添加成功');
    }

    /**
     * 读取用户指定访问的数据
     * @param Request $request
     * @param int $min
     * @param int $max
     * @return json
     */
    public function getUserRange(Request $request) {
        $min = $request->post('min');
        $max = $request->post('max');
        if(!is_numeric($min) || !is_numeric($max) || $min<0 || $min>=$max) {
            return $this->fail(1,'param min or max err');
        }

        $users = DB::select('select * from user where id >= ? and id <= ?',[$min,$max]);
        return $this->success('获取数据成功',array_map('get_object_vars',$users));
    }

    /**
     * 写入用户接口传递过来的数据
     * @param string $name
     * @param int $age
     * @return json
     */
    public function addUser(Request $request) {
        $validator = Validator::make($request->input(),[
            'name' => 'required|min:3|max:20',
            'age'  => 'required|between:2,120'
        ],[
           'name.required' => 'name is required',
           'name.min' => 'name length must not less than 3',
           'name.age' => 'name length must not greater than 120',
           'age.required' => 'age is required',
           'age.between' => 'age must between 3 and 120',
        ]);
        if($validator->fails()) {
            return $this->fail(1,$validator->getMessageBag()->first());
        }

        $res = DB::insert('insert into user(name,age) values(?,?)',[$request->post('name'), $request->post('age')]);
        if($res) {
            return $this->success(0,'数据添加成功',array());
        } else {
            return $this->fail(1,'数据添加失败');
        }
    }

    /**
     * 修改用户指定的数据
     * @param int $user_id
     * @param string $name
     * @param int $age
     * @return json
     */
    public function updateUser(Request $request) {
        $validator = Validator::make($request->input(),[
            'user_id'   => 'required',
            'name' => 'required|min:3|max:20',
            'age'  => 'required|between:2,120'
        ],[
            'user_id.required' => 'user_id is required',
            'name.required' => 'name is required',
            'name.min' => 'name length must not less than 3',
            'name.age' => 'name length must not greater than 120',
            'age.required' => 'age is required',
            'age.between' => 'age must between 3 and 120',
        ]);
        if($validator->fails()) {
            return $this->fail(1,$validator->getMessageBag()->first());
        }

        $res = DB::update('update user set name = ? , age = ? where id = ?',[$request->post('name'),$request->post('age'),$request->post('user_id')]);
        if($res || $res===0) {
            return $this->success('数据修改成功！',array());
        } else {
            return $this->fail(2,'数据修改失败！');
        }
    }

    /**
     * 删除用户数据
     * @param int $user_id
     * @return json
     */
    public function delUser(Request $request) {
        $user_id = $request->user_id;
        if(!is_numeric($user_id) || $user_id <=0) {
            return $this->fail(1,'param user_id err');
        }

        $res = DB::delete('delete from user where id = :id',[$user_id]);
        if($res) {
            return $this->success('数据删除成功！');
        } else {
            return $this->fail(2,'数据删除失败！');
        }
    }

    /**
     * 分页操作
     * @param int page
     * @return json
     */
    public function paginate(Request $request) {
        $page = $request->page;
        $size = 6;
        $offset = ($page-1) * $size;
        $users = User::offset($offset)->limit($size)->get()->toArray();
        if(count($users) > 0) {
            return $this->success('数据获取成功', $users);
        } else {
            return $this->fail(2,'获取数据失败');
        }
    }

    /**
     * redis操作set
     * @param Request $request
     * @param int $user_id
     * @param string $name
     * @return  ResponseAlias
     */
    public function setRedisUser(Request $request) {
        $user_id = $request->user_id;
        $name = $request->name;
        Redis::setex("user_".$user_id,20,$name);
        return $this->success('redis设置成功',array());
    }

    public function getRedisUser(Request $request) {
        $user_id = $request->user_id;
        $name = Redis::get("user_".$user_id);
        if(strlen($name)>0) {
           return $this->success('数据获取成功',['name'=>$name]);
        } else {
           return $this->fail(2,'数据获取失败');
        }
    }
}
