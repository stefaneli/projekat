<?php

class Application_Model_DbTable_CmsServices extends Zend_Db_Table_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

        protected $_name = 'cms_services';
        
        /**
         * 
         * @param int $id
         * @return null|array Associative array with keys as cms_services table columns or NULL if not found
         */
        public function getServiceById($id){
            $select = $this->select();
            $select->where('id = ?', $id);

           $row = $this->fetchRow($select);
           
           if($row instanceof Zend_Db_Table_Row){
               return $row->toArray();
           } else {
               // row is not found
               return null;
           }
        }
        
        /**
         * @param array $service Associative array with keys as colom names and values as colom new values
         * @return int The ID of the new created service (autoincrement)
         */
        public function insertService($service){
            
            $select = $this->select();
            
            //Sort rows by order_number DESCENDING and fetch one row from the top
            // with biggest order_number
            $select->where('service_category_id = ?', $service['service_category_id'])
                    ->order('order_number DESC');

            $serviceWithBiggestOrderNumber = $this->fetchRow($select);

            if ($serviceWithBiggestOrderNumber instanceof Zend_Db_Table_Row) {

                    $service['order_number'] = $serviceWithBiggestOrderNumber['order_number'] + 1;
            } else {
                    // table was empty, we are inserting first photo
                    $service['order_number'] = 1;
            }

            $id = $this->insert($service);

            return $id;
                     
        }
        
         /**
         * 
         * @param int $id
         * @param array $service Associative array with keys as colom names and values as colom new values
         */
        public function updateServiceById($id, $service){
            
            if(isset($service['id'])){
                // forbid changing of user id
                unset($service['id']);
            }
            
            $this->update($service, 'id = ' . $id);
            
        }
        
        /**
         * 
         * @param array $service Service to delete
         */
        public function deleteService($service){
            
            $this->update(array('order_number' => new Zend_Db_Expr('order_number -  1')),
                                'order_number > ' . $service['order_number'] . ' AND service_category_id = ' . $service['service_category_id']); 
            
            $this->delete('id=' . $service['id']);
        }
        
         /**
         * 
         * @param int $id ID of service to enable
         */
        public function enableService($id){
            
             $this->update(array(
                'status' => self::STATUS_ENABLED,
                ), 'id=' . $id);
        }
        
        /**
         * 
         * @param int $id ID of service to disable
         */
        public function disableService($id){
            
            $this->update(array(
                'status' => self::STATUS_DISABLED,
                ), 'id=' . $id);
        }
        
        public function updateServiceOrder($sortedIds) {
            foreach ($sortedIds as $orderNumber => $id) {
                 $this->update(array(
                'order_number' => $orderNumber +1, // +1 because order number starts from 1 not from 0
                ), 'id=' . $id);
            }
        }
      
        
        /**
         * Array $parameters is keeping search parameters.
         * Array $parameters must be in following form:
         *      array(
         *          'filters' => array(
         *              'status' => 1,
         *              'id' => array(3, 8, 11)
         *          ),
         *          'orders' => array(
         *              'username' => 'ASC',  // key is column, if value is ASC then ORDER BY ASC
         *              'first_name' => 'DESC',  // key is column, if value is DESC then ORDER BY DESC
         *          ),
         *          'limit' => 50, // limit result to 50 rows
         *          'page' => 3 // start from page 3. If no limit is set, page is ignored.     
         *      )
         * @param array $parameters Asoc array with keys "filters", "orders", "limit" and "page".
         */
        public function search(array $parameters = array()){
            
            $select = $this->select();
            
            if(isset($parameters['filters'])){
                
                $filters = $parameters['filters'];
                
                $this->processFilters($filters, $select);
            }
            
            if(isset($parameters['orders'])){
                $orders = $parameters['orders'];
                
                foreach ($orders as $field => $orderDirection) {
                    
                    switch ($field) {
                        
                        case "id":
                        case "service_category_id":
                        case "title":
                        case "description":
                        case "price";
                        case "status":
                        case "order_number":  
                            
                            if($orderDirection === 'DESC'){
                                $select->order($field . ' DESC');
                            } else{
                                $select->order($field);
                            }
                            
                            break;
                    }
                }
            }
            
            if(isset($parameters['limit'])){
               
               if(isset($parameters['page'])){
                   // page is set do limit by page
                $select->limitPage($parameters['page'], $parameters['limit']); 
               } else {
                   // page is not set, just do regular limit
                   $select->limit($parameters['limit']);
               }
            }
            
//            die($select->assemble());
            return $this->fetchAll($select)->toArray();
            
        }
        
        /**
         * 
         * @param array $filters See function search $parameters['filters']
         * @return int Count of rows that match $filters
         */
        public function count(array $filters = array()) {
            $select = $this->select();
            
            $this->processFilters($filters, $select);
            
            // reset previously set columns for result - Bilo je SELECT * a mi hocemo COUNT(*)
            $select->reset('columns');
            // set only column/filed to fetch and it is COUNT(*) function
            $select->from($this->_name, 'COUNT(*) AS total');
            
            $row = $this->fetchRow($select);
            
            return $row['total'];
            
        }
        
        /**
         * Fill $select object with WHERE conditions
         * @param array $filters
         * @param Zend_Db_Select $select
         */
        protected function processFilters(array $filters, Zend_Db_Select $select){
            
            // $select object will be modified outside this function
            // object are alowed passed by reference
            
            foreach($filters as $field => $value){
                  
                    switch ($field) {
                        
                        case "id":
                        case "title":
                        case "description":
                        case "price":
                        case "status": 
                        case "service_category_id":
                            
                            if(is_array($value)){
                                $select->where($field . ' IN (?)', $value);
                            } else {
                                $select->where($field . ' = ?', $value);
                            }
                            break;
                            
                        case "title_search":   
                            
                            $select->where('title LIKE ?', '%' . $value . '%');
                            break;
                        
                        case "description_search":   
                            
                            $select->where('description LIKE ?', '%' . $value . '%');
                            break;
                        
                        case "price_search":   
                            
                            $select->where('price LIKE ?', '%' . $value . '%');
                            break;
                        
                        case 'id_exclude':
                            
                            if(is_array($value)){
                                $select->where('id NOT IN (?)', $value);
                            } else{
                                $select->where('id != ?', $value);
                            }
                            
                            break;
                         
                    }
                }
        }

        
}

