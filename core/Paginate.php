<?php


namespace Core;


class Paginate
{
    const DEFAULT_MAXLENGTH = 7;
    const DEFAULT_PAGE = 0;
    private int $perPage;
    private int $currentPage;
    private array $data;
    private static int $count;
    private static int $max;
    private static int $cp;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->perPage = count($data);
        $this->currentPage = 0;
        self::$count = count($data);
    }

    /**
     * @return int
     */
    public static function getMax(): int
    {
        return self::$max;
    }

    /**
     * @return int
     */
    public static function getCp(): int
    {
        return self::$cp;
    }

    public function getSlice(): array
    {
        $page = isset($_GET['page']) ? ($_GET['page'] - 1) : self::DEFAULT_PAGE;
        $this->setCurrentPage($page);
        $maxLength = isset($_GET['maxLength']) ? $_GET['maxLength'] : self::DEFAULT_MAXLENGTH;
        $this->setPerPage($maxLength);
        self::$max = $maxLength;
        return array_slice($this->data, $this->getOffsetPage(), $this->perPage);
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
        self::$cp = $currentPage;
    }

    /**
     * @return float|int
     */
    public function getOffsetPage()
    {
        return $this->currentPage * $this->perPage;
    }

    public static function getPages()
    {
        $count = self::$count;
        $page = intdiv($count, self::$max);
        $page = $count % self::$max > 0 ? $page + 1 : $page;
        return range(1, $page);
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

}