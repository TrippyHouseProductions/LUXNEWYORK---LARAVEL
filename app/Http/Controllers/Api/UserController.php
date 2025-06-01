<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    // Only admin should access this!
    // public function index()
    // {

    //     if (Auth::check() && Auth::user()->user_type !== 'admin') {
    //         abort(403, 'Admins only');
    //     }
        
    //     return UserResource::collection(User::where('user_type', 'customer')->get());
    // }

    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $perPage = $request->get('per_page', 6);

        $users = User::where('user_type', 'customer')
            ->withCount('orders')
            ->withSum('orders as orders_total', 'total')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($users);
    }


    public function destroy($id)
    {

        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        User::destroy($id);
        return response()->noContent();
    }

    public function countUsers() 
    {
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        return response()->json(['count' => User::count()]);
    }
}
