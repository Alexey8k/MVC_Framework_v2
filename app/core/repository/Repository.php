<?php

namespace App\Core\Repository;

abstract class Repository
{
    /**
     * @var DbFactoryInterface
     */
    protected $_dbFactory;

    public function __construct(string $repositoryName = null)
    {
        $connectionOptions = $this->getConnectionOptions($repositoryName ?? $this->getDefaultConnectionOptionsName());
        $this->_dbFactory = Db::createDbFactory($connectionOptions);
    }

    protected function getLink() {
        return $this->_dbFactory->dbLink();
    }

    protected function executeQuery(string $query, callable $rowMapper = null)
    {
        return $this->_dbFactory->queryService()->executeQuery($query, $rowMapper);
    }

    protected function executeNonQuery(string $query) : bool
    {
        return $this->_dbFactory->queryService()->executeNonQuery($query);
    }

    protected function storedProcedureCall(string $name, bool $isFunction = false)
    {
        return $this->_dbFactory->storedProcedure($name, $isFunction);
    }

    private function getConnectionOptions(string $repositoryName) : array
    {
        $sectionConnections = ((array)simplexml_load_file('app/Web.config')->mysql->connections)['add'];

        $connections = array_map(
            function ($item) {
                return ((array)$item)['@attributes'];
            },
            is_array($sectionConnections) ? $sectionConnections : array($sectionConnections));

        return array_pop(array_filter($connections, function ($item) use($repositoryName) {
            return $item['name'] === $repositoryName; }));
    }

    private function getDefaultConnectionOptionsName() {
        return array_pop(explode('\\', get_called_class()));
    }


//    protected function callStoredProcedure(string $name, array $inArgs, callable $fetchResult)
//    {
//        $queryStr = "CALL `$name`('" . implode("','", $inArgs) . "')";
//        $this->link->real_query($queryStr);
//        if (!$queryResult = $this->link->store_result()) return null;
//
//        $result = $fetchResult($queryResult);
//
//        $this->resetNextResults();
//
//        $queryResult->free();
//
//        return $result;
//    }
//
//    private function resetNextResults()
//    {
//        if ($this->link->more_results() && $this->link->next_result()) $this->resetNextResults();
//        return;
//    }

}
