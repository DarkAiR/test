<?php

class NewsLentaWidget extends ExtendedWidget
{
    const LIMIT = 2;

    public $model;
    public $attribute;

    public function run()
    {
        $news = $this->getNews();
        $this->render('newsLenta', array(
            'news' => $news,
        ));
    }

    /**
     Получить список новостей
     */
    private function getNews()
    {
        $news = News::model()
            ->onSite()
            ->onMain()
            ->orderDefault()
            ->byLimit(4)
            ->findAll();
        return $news;



        $arr = array();
        $news = News::model()
            ->onSite()
            ->onMain()
            ->byLimit(self::LIMIT)
            ->findAll();
        foreach ($news as &$n)
        {
            $size = 140;
            $text = strip_tags($n->title);
            if (mb_strlen($text, 'utf-8') > $size) {
                $lastSpacePos = mb_strrpos(mb_substr($text, 0, $size, 'utf-8'), ' ', 'utf-8');
                $text = mb_substr($text, 0, $lastSpacePos, 'utf-8')."…";
            }
            $arr[] = array(
                'id'    => $n->id,
                'date'  => DateHelper::formatNewsDate($n->createTime),
                'text'  => $text,
                'image' => $n->getImageUrl(),
                'link'  => CHtml::normalizeUrl(array('/news/news/show', 'id'=>$n->id))
            );
        }
        return $arr;
    }
}
