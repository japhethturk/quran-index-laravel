<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{



    

    public function mangerPaginate(Request $request)
    {
        try {
            $rows = intval($request->rows);
            $first = intval($request->first);

            $usersQuery = User::where('role', 'manager')->orWhere('role', 'admin');
            $filters = json_decode($request['filters'], TRUE);
            $filters = gettype($filters) === 'array' ? $filters : [];
            if (sizeof($filters) > 0){
                if (isset($filters['name'])){
                    $usersQuery->where('name','LIKE','%'.$filters['name']['value'].'%');
                }
            }


            if ($request['sortField'] == 'name'){
                if (intval($request['sortOrder']) === 1){
                    $usersQuery->orderBy('name', 'asc');
                } else {
                    $usersQuery->orderBy('name', 'desc');
                }
            }

            if ($request['sortField'] == 'email'){
                if (intval($request['sortOrder']) === 1){
                    $usersQuery->orderBy('email', 'asc');
                } else {
                    $usersQuery->orderBy('email', 'desc');
                }
            }


            if ($request['sortField'] == 'number'){
                if (intval($request['sortOrder']) === 1){
                    $usersQuery->orderBy('number', 'asc');
                } else {
                    $usersQuery->orderBy('number', 'desc');
                }
            }

            if ($request['sortField'] == 'date'){
                if (intval($request['sortOrder']) === 1){
                    $usersQuery->orderBy('created_at', 'asc');
                } else {
                    $usersQuery->orderBy('created_at', 'desc');
                }
            }  else {
                $usersQuery->orderBy('id', 'desc');
            }


            $count = $usersQuery->count();
            $users = $usersQuery->offset($first)->limit($rows)->get();
            return response()->json([
                'status'=>'ok',
                'request'=>$request,
                'paginate' => [
                    'data'=>$users,
                    'total'=>$count
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ]);
        }
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
            'role' => 'required',
        ]);

        $validator->setAttributeNames([
            'name' => trans('translation.name'),
            'email' => trans('translation.email'),
            'password' => trans('translation.password'),
            'role' => trans('translation.role'),
        ]);

        if ($validator->fails()) {
            return Helper::validateFailResponse($validator->messages()->first());
        }

        try {
            $request['password'] = Hash::make($request->password);
            $user = new User($request->all());
            $user->save();
            return Helper::saveSuccessResponse();
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            return response()->json([
                'status'=>'ok',
                'user'=>User::find($id)
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'role' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return Helper::validateFailResponse($validator->messages()->first());
        }

        try {
            $authUser = Auth::user();
            $updateItem = User::find($id);
            if ($authUser->role !== 'admin') {
                if ($authUser->id !== $id) {
                    return response()->json([
                        'status' => 'warn',
                        'message' => Helper::message('warn', trans('translation.not_authorized'))
                    ]);
                }
            }

            $passwordChange = '-';
            if (isset($request->new_password) || isset($request->repassword)) {

                $validator = Validator::make($request->all(), [
                    'new_password' => 'required|min:6',
                    'repassword' => 'required|same:new_password',
                ]);

                $validator->setAttributeNames([
                    'new_password' => trans('translation.new_password'),
                    'repassword' => trans('translation.confirm_password')
                ]);

                if ($validator->fails()) {
                    return Helper::validateFailResponse($validator->messages()->first());
                }

                $updateItem->password = Hash::make($request->new_password);
            }

            $updateItem->update($request->all());

            return response()->json([
                'status' => 'ok',
                'password_change' => $passwordChange,
                'message' => Helper::saveSuccessMessage()
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $authUser = Auth::user();
            if ($authUser->id === intval($id)) {
                if ($authUser->id !== $id) {
                    return response()->json([
                        'status' => 'warn',
                        'message' => Helper::message('warn', trans('translation.not_authorized'))
                    ]);
                }
            }
            $removeItem = User::find($id);
            $removeItem->delete();
            return Helper::deleteSuccessResponse();
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }
}
