<?php

namespace DevDojo\Chatter\Controllers;

use Sentinel;
use DevDojo\Chatter\Models\Models;
// use Illuminate\Routing\Controller as Controller;
use App\Http\Controllers\Controller;
class ChatterController extends Controller
{
    // public function __construct()
    // {
    //     view()->share('signedIn', Sentinel::check());
    //     view()->share('user', Sentinel::getUser());
    // }
    public function __construct()
    {
        parent::__construct();
    }
    public function index($slug = '')
    {
        $pagination_results = config('chatter.paginate.num_of_results');

        $discussions = Models::discussion()->with('user')->with('post')->with('postsCount')->with('category')->orderBy('created_at', 'DESC')->paginate($pagination_results);
        if (isset($slug)) {
            $category = Models::category()->where('slug', '=', $slug)->first();
            if (isset($category->id)) {
                $discussions = Models::discussion()->with('user')->with('post')->with('postsCount')->with('category')->where('chatter_category_id', '=', $category->id)->orderBy('created_at', 'DESC')->paginate($pagination_results);
            }
        }

        $categories = Models::category()->all();

        return view('chatter::home', compact('discussions', 'categories'));
    }

    public function login()
    {
        if (!Sentinel::getUser()) {
            return \Redirect::to('/'.config('chatter.routes.login').'?redirect='.config('chatter.routes.home'))->with('flash_message', 'Please create an account before posting.');
        }
    }

    public function register()
    {
        if (!Sentinel::getUser()) {
            return \Redirect::to('/'.config('chatter.routes.register').'?redirect='.config('chatter.routes.home'))->with('flash_message', 'Please register for an account.');
        }
    }
}
