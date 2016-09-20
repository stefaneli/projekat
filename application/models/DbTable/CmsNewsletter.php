<?php

class Application_Model_DbTable_CmsNewsletter extends Zend_Db_Table_Abstract
{   

    protected $_name = 'cms_newsletter';
    
    /**
     * @param int $id
     * @return null|array Associative array with keys as cms_newsletters table columns or NULL if not found
     */
    public function getNewsletterById($id) {
        
        $select = $this->select();
        $select->where('id = ?', $id);
        
        $row = $this->fetchRow($select);
        
        if ($row instanceof Zend_Db_Table_Row) {
            
            return $row->toArray();
        } else {
            // row is not found
            return null;
        }
    }

    /**
     * @param int $id
     * @param array $newsletter Associative array with keys as column names and values as coumn new values
     */
    public function updateNewsletter($id, $newsletter) {
        
        if (isset($newsletter['id'])) {
            //Forbid changing of user id
            unset($newsletter['id']);
        }
        
        $this->update($newsletter, 'id = ' . $id);
    }
    
    /**
     * 
     * @param array $newsletter Associative array with keys as column names and values as coumn new values
     * @return int The ID of new photoNewsletter (autoincrement)
     * 
     */
    public function insertNewsletter($newsletter) {
        
        //$select = $this->select();
        
        $id = $this->insert($newsletter);
        return $id;
    }
    
    /**
     * 
     * @param int $id ID of newsletter to delete
     */
    public function deleteNewsletter($id) {
        //newsletter who is going to be deleted
        $newsletter = $this->getNewsletterById($id);
        $this->delete('id = ' . $id);
    }
    

    /**
     * Array $parameters is keeping search parameters.
     * Array $parameters must be in following format:
     *      array(
     *          'filters' => array(
     *              'status' => 1,
     *              'id' => array(3, 8, 11)
     *          ),
     *          'orders' => array(
     *              'username' => 'ASC', // key is column , if value is ASC then ORDER BY ASC,
     *              'first_name' => 'DESC', // key is column, if value is DESC then ORDER BY DESC
     *          ),
     *          'limit' => 50, //limit result set to 50 rows
     *          'page' => 3 // start from page 3. If no limit is set, page is ignored
     *      )
     * @param array $parameters Asoc array with keys "filters", "orders", "limit" and "page".
     */
    public function search(array $parameters = array()) {
        
        $select = $this->select();
        
        if (isset($parameters['filters'])) {
            
            $filters = $parameters['filters'];
            
            $this->processFilters($filters, $select);
        }
        
        if (isset($parameters['orders'])) {
            
            $orders = $parameters['orders'];
            
            foreach ($orders as $field => $orderDirection) {
                
                switch ($field) {
                    case 'id':
                    case 'name':
                    case 'email':
                                        case 'date_subscribed':
                                        case 'group_id':
                        if ($orderDirection === 'DESC') {
                            
                            $select->order($field . ' DESC');
                        } else {
                            $select->order($field);
                        }
                        break;
                }
            }
        }
        
        if (isset($parameters['limit'])) {
            
            if (isset($parameters['page'])) {
                // page is set do limit by page
                $select->limitPage($parameters['page'], $parameters['limit']);
            } else {
                // page is not set, just do regular limit
                $select->limit($parameters['limit']);
            }
        }
        
        //die($select->assemble());
        
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
        
        // reset previously set columns for resultset
        $select->reset('columns');
        // set one column/field to fetch and it is COUNT function
        $select->from($this->_name, 'COUNT(*) as total');
        
        $row = $this->fetchRow($select);
        
        return $row['total'];
    }
    
    /**
     * Fill $select object with WHERE conditions
     * @param array $filters
     * @param Zend_Db_Select $select
     */
    protected function processFilters(array $filters, Zend_Db_Select $select) {
        
        //$select object will be modified outside this function
        // object are always passed by reference
        
        foreach ($filters as $field => $value) {
                
                switch ($field) {
                    
                    case 'id':
                    case 'name':
                    case 'email':
                                        case 'date_subscribed':
                                        case 'group_id':
                        if (is_array($value)) {
                            $select->where($field . ' IN (?)', $value);
                        } else {
                            $select->where($field . ' = ?', $value);
                        }
                        break;
                    
                    case 'name_search':
                        $select->where('name LIKE ?', '%' . $value . '%');
                        break;
                                        case 'date_subscribed_search':
                        $select->where('date_subscribed LIKE ?', '%' . $value . '%');
                        break;    
                    case 'email_search':
                        $select->where('email LIKE ?', '%' . $value . '%');
                        break;
                                        case 'group_id_search':
                        $select->where('group_id LIKE ?', '%' . $value . '%');
                        break;    
                    case 'id_exclude':
                        if (is_array($value)) {
                            
                            $select->where('id NOT IN (?)', $value);
                        } else {
                            $select->where('id != ?', $value);
                        }
                        break;
                }
            }
    }
}