<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Visitor;
use App\User;
use App\Page;

class HomeController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $dateLimit = date('Y-m-d H:i:s', strtotime('-5 minutes'));

        $visitsCount = count(Visitor::select('ip')->groupBy('ip')->get());
        $onlineCount = count(Visitor::select('ip')->where('date_access', '>=', $dateLimit)->groupBy('ip')->get());
        $pageCount = Page::count();
        $userCount = User::count();

        $pagePie = [];
        $visitsAll = Visitor::selectRaw('page, count(page) as c')->groupBy('page')->get();
        foreach($visitsAll as $visit){
            $pagePie [$visit['page']] = intval($visit['c']);
        }

        $pageLabels = json_encode( array_keys($pagePie));
        $pageValues = json_encode(array_values($pagePie));

        return view('admin.home',[
            'visitsCount' => $visitsCount,
            'onlineCount' => $onlineCount,
            'pageCount' => $pageCount,
            'userCount' => $userCount,
            'pageLabels' => $pageLabels,
            'pageValues' => $pageValues
        ]);
    }
}
