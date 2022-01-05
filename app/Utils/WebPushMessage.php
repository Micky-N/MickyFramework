<?php


namespace App\Utils;


class WebPushMessage
{
    /**
     * The message title.
     */
    protected string $title = '';

    /**
     * The message body.
     */
    protected string $body = '';


    /**
     * The message icon.
     */
    protected string $icon = '';

    /**
     * The message image.
     */
    protected string $image = '';

    /**
     * URL to follow on notification click.
     */
    protected string $link = '';

    /**
     * Extra options that will get added to the message.
     */
    protected array $data = [];

    /**
     * Button actions max 2 buttons
     */
    protected array $actions = [];

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Set the message title.
     *
     * @param string $value
     * @return $this
     */
    public function title(string $value)
    {
        $this->title = $value;
        return $this;
    }

    /**
     * Set the message body.
     *
     * @param string $value
     * @return $this
     */
    public function body(string $value)
    {
        $this->body = $value;
        return $this;
    }

    /**
     * Set the message icon.
     *
     * @param string $url
     * @return $this
     */
    public function icon(string $url)
    {
        $this->icon = $url;
        return $this;
    }

    /**
     * Set the message image.
     *
     * @param string $url
     * @return $this
     */
    public function image(string $url)
    {
        $this->image = $url;
        return $this;
    }

    /**
     * Set the message link.
     *
     * @param string $value
     * @return $this
     */
    public function link($value)
    {
        $this->link = $value;
        return $this;
    }

    /**
     * @param array $action
     * @return $this
     */
    public function actions(array $action)
    {
        $this->actions = $action;
        return $this;
    }

    /**
     * Format the message for web.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon,
            'image' => $this->image,
            'data' => ['link' => $this->link, ...$this->data],
            'actions' => $this->actions
        ]);
    }
}