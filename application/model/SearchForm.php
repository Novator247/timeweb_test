<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.12.17
 * Time: 4:28
 */

namespace Model;


use Library\Model;

class SearchForm extends Model
{
    const VALIDATION_SUCCESS = 0;
    const VALIDATION_ERROR_URL = 1;
    const VALIDATION_ERROR_TEXT = 2;

    const TYPE_LINKS = 'links';
    const TYPE_IMAGES = 'images';
    const TYPE_TEXT = 'text';

    CONST TYPE_LINKS_PATTERN = '/<a.*href=["\']?([^"\']{2,})["\']?[^>]*>(.*)<\/a>/';
    CONST TYPE_IMAGES_PATTERN = '/<img.*src=["\']?([^"\']*)["\']?[^>\/]*\/*>/';

    CONST URL_VALID_PATTERN = '/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:[\/?#]\S*)?$/iu';

    /**
     * Site url
     * @var string
     */
    protected $url = '';

    /**
     * Object type for search
     * @var string
     */
    protected $type = 'links';

    /**
     * Search value for text
     * @var string
     */
    protected $text = '';

    /**
     * Validation errors list
     * @var array
     */
    protected $errors = [];

    /**
     * Return list of validation errors
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validate object data
     * @return bool
     */
    public function validate(): bool
    {
        if (!preg_match(static::URL_VALID_PATTERN, $this->url, $matches)) {
            $this->errors['url'] = "Invalid site URL";
        }

        if (!in_array($this->type, [static::TYPE_LINKS, static::TYPE_IMAGES, static::TYPE_TEXT])) {
            $this->errors['type'] = "Invalid search type";
        }

        if ($this->type === static::TYPE_TEXT && !$this->text) {
            $this->errors['text'] = "Invalid search text value";
        }

        return !$this->hasErrors();
    }

    /**
     * Check that there are errors
     * @return bool
     */
    public function hasErrors(): bool
    {
        return (bool)count($this->errors);
    }

    public function searchData(): SearchResult
    {
        $html = $this->loadContent();
        $matches = [];
        $count = 0;

        switch ($this->type) {
            case static::TYPE_LINKS:
                $count = preg_match_all(static::TYPE_LINKS_PATTERN, $html, $matches);
                break;
            case static::TYPE_IMAGES:
                $count = preg_match_all(static::TYPE_IMAGES_PATTERN, $html, $matches);
                break;
            case static::TYPE_TEXT:
                $pattern = '/' . preg_quote($this->text) . '/';
                $count = preg_match_all($pattern, $html, $matches);
                break;
        }

        $result = new SearchResult([]);

        $result->setUrl($this->url);
        $result->setCount($count);
        $result->setData($count > 0 ? $matches[1] : []);

        return $result;

    }

    /**
     * Download site html via curl
     * @return string
     */
    protected function loadContent(): string
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        $html = curl_exec($ch);

        $error = curl_error($ch);

        curl_close($ch);

        return $html;
    }
}