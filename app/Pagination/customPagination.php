<?php

namespace App\Pagination;

use Illuminate\Pagination\BootstrapThreePresenter;

class customPagination extends BootstrapThreePresenter {

    public function getActivePageWrapper($text)
    {
        return '<li><span>'.$text.'</span></li>';
    }

    public function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><a href="#">'.$text.'</a></li>';
    }

    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        return '<li><a href="'.$url.'">'.$page.'</a></li>';
    }

    public function render()
    {
        if ($this->hasPages())
        {
            return sprintf(
                '%s %s %s',
                $this->getPreviousButton('&lt; zurück;'),
                $this->getLinks(),
                $this->getNextButton('weiter &gt;')
            );
        }

        return '';
    }

  
   
}