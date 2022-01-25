<?php

namespace GeoSot\BaseAdmin\Helpers;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\URL;

class PageMeta implements Htmlable
{
    protected string $title = '';
    protected string $description = '';
    protected string $image = '';
    protected string $url = '';
    protected array $keywords = [];
    /**
     * @var array<string,string>
     */
    protected array $extraMetaTags = [];


    public static function make(): self
    {
        return new static();
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }


    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function addKeywords(...$keywords): self
    {
        $this->keywords = array_unique(array_merge($this->keywords, $keywords));
        return $this;
    }


    public function getKeywords(): string
    {
        return join(', ', $this->keywords);
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImage(): string
    {
        return $this->image ?: asset('favicon.ico');
    }


    public function getUrl(): string
    {
        return $this->url ?: URL::current();
    }

    public function getSiteName(): string
    {
        return config('app.name', '');
    }

    /**
     * @return array<string,string>
     */
    public function getExtraMetaTags(): array
    {
        return $this->extraMetaTags;
    }


    public function addExtraMetaTags(string $key, ?string $value): void
    {
        $this->extraMetaTags[$key] = $value;
    }

    public function toHtml(): string
    {
        return view('baseAdmin::_subBlades.page-meta', ['meta' => $this])->render();
    }
}
