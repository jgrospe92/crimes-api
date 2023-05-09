<?php
namespace Vanier\Api\Controllers;
use Vanier\Api\Helpers\WebServiceInvoker;

class NewsController extends WebServiceInvoker
{
    public function getNews()
    {

        $news_URI = 'https://saurav.tech/NewsAPI/';
        $data = $this->invokeUri($news_URI,'everything/cnn.json');

        //var_dump($data);exit;
        $counter = 0;
        $news_data = [];
        foreach ($data->articles as $key => $article) {
            $news_data[$key]['author'] = $article->author;
            $news_data[$key]['title'] = $article->title;
            $news_data[$key]['description'] = $article->description;
            $news_data[$key]['url'] = $article->url;
            $counter++ ;
            if($counter == 15){
                break;
            }
        }
        //var_dump($news_data);exit;
        return $news_data;
    }
}
