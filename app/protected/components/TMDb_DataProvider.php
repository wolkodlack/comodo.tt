<?php
/**
 * Created by IntelliJ IDEA.
 * User: wolkodlack
 * Date: 3/4/17
 * Time: 4:54 AM
 */

class TMDb_DataProvider extends  CArrayDataProvider {


    public function __construct(array $rawData, array $config = array()) {
        parent::__construct($rawData, $config);
        if(isset($config['totalItemCount'])) {
            $this->setTotalItemCount($config['totalItemCount']);
        }
    }

    /**
     * Fetches the data from current respond of api. Overrides parent behaviour
     * @return array list of data items
     */
    protected function fetchData() {
        if(($sort=$this->getSort())!==false && ($order=$sort->getOrderBy())!='')
            $this->sortData($this->getSortDirections($order));

        if(($pagination=$this->getPagination())!==false)
        {
            $pagination->setItemCount($this->getTotalItemCount());
            return $this->rawData;
        }
        else
            return $this->rawData;
    }

}