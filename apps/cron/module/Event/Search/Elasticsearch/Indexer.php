<?php
/**
 * @namespace
 */
namespace Event\Search\Elasticsearch;

use Engine\Search\Elasticsearch\Indexer as BaseIndexer,
    Engine\Exception,
    Engine\Search\Elasticsearch\Query\Builder,
    Engine\Search\Elasticsearch\Type,
    Engine\Search\Elasticsearch\Query,
    Engine\Crud\Grid,
    Engine\Crud\Grid\Filter,
    Engine\Crud\Grid\Filter\Field;

/**
 * Class Elasticsearch
 *
 * @package Search
 * @subpackage Elasticsearch
 */
class Indexer extends BaseIndexer
{
    /**
     * Delete index type flag
     * @var bool
     */
    protected $_deleteType = false;

    /**
     * Add data from grid to search index
     *
     * @param integer $page
     * @param integer $pages
     * @param integer $breakPage
     * @return array
     */
    public function setData($page = 0, $pages = false, $breakPage = 0)
    {
        $type = $this->getType();
        if ($this->_deleteType && $type->exists()) {
            $type->delete();
        }
        $this->setMapping();
        $grid = ($this->_grid instanceof \Engine\Crud\Grid) ? $this->_grid : new $this->_grid([], $this->getDi());

        $config = [];
        $config['model'] = $grid->getModel();
                
        $config['conditions'] = $grid->getConditions();
        $config['joins'] = $grid->getJoins();
        $modelAdapter = $grid->getModelAdapter();
        if ($modelAdapter) {
            $config['modelAdapter'] = $modelAdapter;
        }
        $container = new \Engine\Crud\Container\Grid\Mysql($grid, $config);

        $columns = $grid->getColumns();
        foreach ($columns as $column) {
            $column->updateContainer($container);
        }

        $grid->getData();
        $params = $grid->getFilterParams();
        $model = $container->getModel();
        $shardCriteria = $params['location'];
        $model->setShardByCriteria($shardCriteria);
        $dataSource = $container->getDataSource();

        foreach ($columns as $column) {
            $column->updateDataSource($dataSource);
        }

        $filter = $grid->getFilter();
        if ($params['location'] == 0) {
            $params['location'] = null;
        }
  
        $filter->setParams($params);
        $filter->applyFilters($dataSource);
        $data = $container->getData($dataSource);
        echo "... found ".$data['total_items']." objects\n";
//die();

        $pages = $data['pages'];
        $i = 0;
        do {
            foreach ($data['data'] as $values) {
		      	$response = $this->addItem($values, $grid, $shardCriteria);
		        if ($response->hasError()) {
		            var_dump($response->getError());
		        }
            }
            ++$i;
            $grid->clearData();
            $grid->setParams(['page' => $i + 1]);
            $data = $container->getData($dataSource);
        } while ($i < $pages);
        
        $mem_usage = memory_get_usage();
        echo "Use memory ".round($mem_usage/1048576,2)." megabytes\n\r\n\r";
    }

    /**
     * Add new item to index
     *
     * @param array $data
     * @param \Engine\Crud\Grid $grid
     * @param string $shardCriteria
     * @throws \Engine\Exception
     */
    public function addItem(array $data, $grid = null, $shardCriteria = null)
    {
        if (!$grid) {
            $grid = new $this->_grid([], $this->getDi());
        }
        $itemDocument = $this->_processItemData($data, $grid, $shardCriteria);
        if (!$itemDocument) {
            return;
        }
        return $this->getType()->addDocument($itemDocument);
    }

    /**
     * Build elastica document
     *
     * @param array $data
     * @param \Engine\Crud\Grid $grid
     * @return \Elastica\Document
     * @param string $shardCriteria
     * @throws \Engine\Exception
     */
    protected function _processItemData(array $data, \Engine\Crud\Grid $grid, $shardCriteria = null)
    {
        $primaryKey = $grid->getPrimaryColumn()->getName();
        $filterFields = $grid->getFilter()->getFields();

        $item = [];
        foreach ($filterFields as $key => $field) {
            if (
                $field instanceof Field\Search ||
                $field instanceof Field\Compound ||
                $field instanceof Field\Match ||
                $field instanceof Field\Submit
            ) {
                continue;
            }
            // check if filter field is a join field
            if ($field instanceof Field\Join) {
                $this->_processJoinFieldData($item, $key, $field, $data, $grid, $shardCriteria);
            } elseif ($field instanceof Field\Date) {
                $this->_processDateFieldData($item, $key, $field, $data, $grid);
            } else {
                $this->_processStandartFieldData($item, $key, $field, $data, $grid);
            }
        }

        if (!(isset($item[$primaryKey]))) {
            $item[$primaryKey] = $data[$primaryKey];
        }
        $id = $item[$primaryKey];

        return $itemDocument = new \Elastica\Document($id, $item);
    }

    /**
     * Process data field value for search document
     *
     * @param array $item
     * @param string $key
     * @param \Engine\Crud\Grid\Filter\Field $field
     * @param array $data
     * @param \Engine\Crud\Grid $grid
     * @param string $shardCriteria
     * @throws \Exception
     * @return void
     */
    protected function _processJoinFieldData(array &$item, $key, Field\Join $field, array $data, Grid $grid, $shardCriteria = null)
    {
        $name = $field->getName();
        $path = $field->getPath();
        $primaryKey = $grid->getPrimaryColumn()->getName();
        $gridColums = $grid->getColumns();


        // if count of path models more than one, means that is many to many relations
        if (count($path) > 1) {
            $workingModelClass = array_shift($path);
            $workingModel = new $workingModelClass;
            
            $refModelClass = array_shift($path);
            $refModel = new $refModelClass;
            $relationsRefModel = $workingModel->getRelationPath($refModel);
            
            if (!$relationsRefModel) {
                throw new \Engine\Exception("Did not find relations between '".get_class($workingModel)."' and '".$refModel."' for filter field '".$key."'");
            }
            $mainModel = $grid->getContainer()->getModel();
            $relationsMainModel = $workingModel->getRelationPath($mainModel);
            if (!$relationsMainModel) {
                throw new \Engine\Exception("Did not find relations between '".get_class($workingModel)."' and '".get_class($mainModel)."' for filter field '".$key."'");
            }
            $refKey = array_shift($relationsRefModel)->getFields();
            $keyParent = array_shift($relationsMainModel)->getFields();
// $mem_usage = memory_get_usage();
// echo "Use memory before ".round($mem_usage/1048576,2)." megabytes\n";
            $workingModel->setShardByCriteria($shardCriteria);
// $mem_usage = memory_get_usage();
// echo "Use memory after ".round($mem_usage/1048576,2)." megabytes\n\n";
            $workingModel->setShardByCriteria($shardCriteria);
            
            $queryBuilder = $workingModel->queryBuilder();
            $db = $workingModel->getReadConnection();
            $queryBuilder->columns([$keyParent,$refKey]);
            // if field have category model, we add each type of category like separate item values
            if ($field->category) {
                $category = $field->category;

                $temp = explode("\\", $category);
                $subKey = array_pop($temp);
                $name .= "_".strtolower($subKey);

                $model = new $category;
                $primary = $model->getPrimary();
                $relationsCategoryModel = $refModel->getRelationPath($category);
                $categoryKey = array_shift($relationsCategoryModel)->getFields();

                $queryBuilder->columnsJoinOne($refModelClass, [$categoryKey => $categoryKey]);
                $queryBuilder->orderBy($categoryKey.', name');
                $queryBuilder->andWhere($keyParent." = '".$data[$primaryKey]."'");
                $sql = $queryBuilder->getPhql();
                $sql = str_replace(
                    [trim($workingModelClass, "\\"), trim($refModelClass, "\\"), "[", "]"],
                    [$workingModel->getSource(), $refModel->getSource(), "", ""],
                    $sql
                );
                $filterData = $db->fetchAll($sql);
                //$filterData = (($result = $queryBuilder->getQuery()->execute()) === null) ? [] : $result->toArray();

                foreach ($filterData as $filter) {
                    $newName = $name."_".$filter[$categoryKey];
                    if (!isset($item[$newName])) {
                        $item[$newName] = [];
                    }
                    $item[$newName][] = $filter[$refKey];
                }
            } else {
                $queryBuilder->andWhere($keyParent." = '".$data[$primaryKey]."'");
                $queryBuilder->columnsJoinOne($refModel, ['name' => 'name', 'id' => 'id']);
                $queryBuilder->orderBy('name');
                $sql = $queryBuilder->getPhql();
                $sql = str_replace(
                    [trim($workingModelClass, "\\"), trim($refModelClass, "\\"), "[", "]"],
                    [$workingModel->getSource(), $refModel->getSource(), "", ""],
                    $sql
                );

                $savedData = $db->fetchAll($sql);
                //$savedData = (($result = $queryBuilder->getQuery()->execute()) === null) ? [] : $result->toArray();
                if ($savedData) {
                    $item[$key] = \Engine\Tools\Arrays::assocToLinearArray($savedData, 'name');
                    $item[$key . "_id"] = \Engine\Tools\Arrays::assocToLinearArray($savedData, 'id');
                    //$item[$key] = \Engine\Tools\Arrays::resultArrayToJsonType($savedData);
                }
            }
        } else {
            if (
                (
                    (isset($gridColums[$key]) && $column = $gridColums[$key]) ||
                    $column = $grid->getColumnByName($name)
                ) &&
                ($column instanceof \Engine\Crud\Grid\Column\JoinOne)
            ) {
                if (null !== $data[$key]) {
                    $item[$key] = [];
                    $item[$key] = $data[$key];
                    $item[$key . "_id"] = $data[$key . "_" . \Engine\Mvc\Model::JOIN_PRIMARY_KEY_PREFIX];
                }
            } else {
                if (null !== $data[$key]) {
                    $item[$key] = $data[$key];
                }
            }
        }
    }

}