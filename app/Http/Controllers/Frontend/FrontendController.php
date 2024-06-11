<?php

namespace App\Http\Controllers\Frontend;

use App\Models\News;
use App\Models\Category;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function index(){

        //get data category
        $category = Category::latest()->get();

        // slider/carousel news latest
        $sliderNews = News::latest()->limit(3)->get();

        return view('frontend.news.index', compact(
            'category',
            'sliderNews',
        ));
    }

    public function detailNews($slug){

        //get data category
        $category = Category::latest()->get();

        //get data news by slug
        $news = News::where('slug', $slug)->first();

        return view('frontend.news.detail', compact(
            'category',
            'news',
        ));

    }

    public function detailCategory($slug){
        // get data category
        $category = Category::latest()->get();

        // get data news by slug
        $detailCategory = Category::where('slug', $slug)->first();

        // get news by category
        $news = News::where('category_id', $detailCategory->id)->latest()->get();
        

        return view('frontend.news.detail-category', compact(
            'category',
            'detailCategory',
            'news'
        ));

    }

}
