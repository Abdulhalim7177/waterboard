<?php

namespace App\Services;

class BreadcrumbService
{
    protected $breadcrumbs = [];

    public function add($title, $url = null)
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'url' => $url
        ];

        return $this;
    }

    public function addHome()
    {
        return $this->add('Home', route('staff.dashboard'));
    }

    public function get()
    {
        return $this->breadcrumbs;
    }

    public function render()
    {
        // If no breadcrumbs are set, create default ones based on page title
        if (empty($this->breadcrumbs)) {
            $pageTitle = view()->shared('page_title', 'Dashboard');
            $this->addHome();
            $this->add($pageTitle);
        }

        $html = '<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">';

        foreach ($this->breadcrumbs as $index => $breadcrumb) {
            if ($index > 0) {
                $html .= '<li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li>';
            }

            if ($breadcrumb['url']) {
                $html .= '<li class="breadcrumb-item text-muted"><a href="' . $breadcrumb['url'] . '" class="text-muted text-hover-primary">' . $breadcrumb['title'] . '</a></li>';
            } else {
                $html .= '<li class="breadcrumb-item text-dark">' . $breadcrumb['title'] . '</li>';
            }
        }

        $html .= '</ul>';

        return $html;
    }
    
    public function setTitle($title)
    {
        // Clear existing breadcrumbs except home
        $homeBreadcrumb = null;
        foreach ($this->breadcrumbs as $breadcrumb) {
            if ($breadcrumb['title'] === 'Home') {
                $homeBreadcrumb = $breadcrumb;
                break;
            }
        }
        
        $this->breadcrumbs = [];
        if ($homeBreadcrumb) {
            $this->breadcrumbs[] = $homeBreadcrumb;
        }
        
        // Add the new title
        $this->add($title);
        
        return $this;
    }
}