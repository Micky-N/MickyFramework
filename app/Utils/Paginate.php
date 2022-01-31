<?php


namespace App\Utils;

class Paginate
{
    const DEFAULT_MAXLENGTH = 7;
    const DEFAULT_PAGE = 0;
    private int $currentPage = 0;
    private array $data;
    private int $count;
    private int $maxLength;

    /**
     * @param array $data
     * @param int $maxLength
     */
    public function __construct(array $data, int $maxLength = null)
    {
        $this->data = $data;
        $this->maxLength = !is_null($maxLength) ? $maxLength : self::DEFAULT_MAXLENGTH;
        $this->count = count($data);
    }

    /**
     * Get the max number of elements by page
     * 
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * Get current page elements
     * 
     * @return array
     */
    public function getSlice(): array
    {
        $page = isset($_GET['page']) ? ($_GET['page'] - 1) : self::DEFAULT_PAGE;
        $this->setCurrentPage($page);
        $this->maxLength = $_GET['max'] ?? $this->maxLength;
        return array_slice($this->data, $this->offsetPage, $this->maxLength);
    }

    /**
     * Set the current page
     *  
     * @param int $currentPage
     * @return Paginate
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * Get the current page
     * 
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get the number of elements
     * 
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Calculates the last item in the list from 
     * the current page to the maximum per page
     * 
     * @return int
     */
    public function getOffsetPage(): int
    {
        $this->maxLength = $_GET['max'] ?? $this->maxLength;
        return $this->currentPage * $this->maxLength;
    }

    /**
     * Get all pages
     * 
     * @return array
     */
    public function getPages(): array
    {
        $this->maxLength = $_GET['max'] ?? $this->maxLength;
        $page = intdiv($this->count, $this->maxLength);
        $page = $this->count % $this->maxLength > 0 ? $page + 1 : $page;
        return range(1, $page);
    }

    public function __get($key)
    {
        if (method_exists($this, 'get' . ucfirst($key))) {
            return $this->{'get' . ucfirst($key)}();
        }
    }
}
