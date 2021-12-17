<?php


namespace HTML;

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
     * Le nombre maximum éléments par page
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * Obtenir les éléments de la page (page -> perpage)
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
     * Saisir la page actuelle
     * @param int $currentPage
     * @return Paginate
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * Page actuelle
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Nombre de donnée
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Calcule le derniere élément de la liste 
     * a partir de la page actuelle jusqu'au maximum par page
     * @return int
     */
    public function getOffsetPage(): int
    {
        $this->maxLength = $_GET['max'] ?? $this->maxLength;
        return $this->currentPage * $this->maxLength;
    }

    /**
     * Toutes les pages
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
        if(property_exists($this, $key)){
            return $this->{'get' . ucfirst($key)}();
        }
    }
}
