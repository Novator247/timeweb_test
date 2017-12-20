<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.12.17
 * Time: 4:27
 */

namespace Model;


use Library\DBModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class SearchResult extends DBModel
{
    CONST tableName = 'search_results';
    /**
     * Site url
     * @var string
     */
    protected $url;

    /**
     * Founded objects count
     * @var int
     */
    protected $count;

    /**
     * Founded objects
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * Requests all rows from db
     * @return SearchResult[]
     */
    public static function getAll(): array
    {
        $res = [];
        $sql = new Sql(static::getDB());
        $select = $sql->select()->from(static::tableName);
        $result = $sql->prepareStatementForSqlObject($select)->execute();

        $resultSet = new ResultSet(ResultSet::TYPE_ARRAY);

        $resultSet->initialize($result);

        foreach ($resultSet->toArray() as $row) {
            $row['data'] = unserialize($row['data']);
            $res[] = new SearchResult($row);
        }

        return $res;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function save()
    {
        $sql = new Sql(static::getDB());
        $data = [
            'url' => $this->url,
            'count' => $this->count,
            'data' => serialize($this->data)
        ];

        if (!$this->created_at) {
            $act = $sql->insert(static::tableName)->values($data);
        } else {
            $act = $sql->update(static::tableName)->set($data);
        }
        $sql->prepareStatementForSqlObject($act)->execute();

    }
}