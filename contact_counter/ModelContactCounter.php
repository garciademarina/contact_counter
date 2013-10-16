<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Model database for contactCounter plugin
     *
     * @package OSClass
     * @subpackage Model
     * @since 3.0
     */
    class ModelContactCounter extends DAO
    {
        /**
         * It references to self object: ModelContactCounter.
         * It is used as a singleton
         *
         * @access private
         * @since 3.0
         * @var ModelContactCounter
         */
        private static $instance ;

        /**
         * It creates a new ModelContactCounter object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since 3.0
         * @return ModelContactCounter
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Construct
         */
        function __construct()
        {
            parent::__construct();
        }

        /**
         * Return table name
         * @return string
         */
        public function getTable()
        {
            return DB_TABLE_PREFIX.'t_item_counter';
        }
        public function getTable_detail()
        {
            return DB_TABLE_PREFIX.'t_item_counter_detail';
        }

        /**
         * Import sql file
         * @param type $file
         */
        public function import($file)
        {
            $path   = osc_plugin_resource($file) ;
            $sql    = file_get_contents($path);

            if(! $this->dao->importSQL($sql) ){
                throw new Exception( $this->dao->getErrorLevel().' - '.$this->dao->getErrorDesc() ) ;
            }
        }

        /**
         * Remove data and tables related to the plugin.
         */
        public function uninstall()
        {
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable()) ) ;
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_detail()) ) ;
        }

        /**
         * Insert house attributes
         * @param array $array
         */
        public function insertItemStat($item_id)
        {
            $aSet = array(
                'fk_i_item_id'      => $item_id,
                'i_contacts'        => 0
                );
            $this->dao->insert( $this->getTable(), $aSet) ;
        }

        public function increaseItemStat($item_id)
        {
            $aSet = array(
                'fk_i_item_id'      => $item_id,
                'dt_date'           => date("Y-m-d H:i:s")
                );
            $this->dao->insert( $this->getTable_detail(), $aSet) ;

            $sql = "UPDATE ".$this->getTable()." SET i_contacts = i_contacts + 1 WHERE fk_i_item_id = '" . $item_id . "'";
            return $this->dao->query($sql) ;
        }

        public function getTotalContactsByUser($user_id)
        {
            $sql = 'SELECT  i_contacts as total FROM '.$this->getTable(). ' as ic LEFT JOIN oc_t_item as i on i.pk_i_id = ic.fk_i_item_id WHERE i.fk_i_user_id = ' . $user_id ;
            $result = $this->dao->query($sql) ;

            $items = $result->result() ;
            if( is_array($items) && count($items)>0) {
                return $items[0]['total'];
            } else {
                return 0;
            }
        }

        public function getTotalContactsByItemId($item_id)
        {
            $sql = 'SELECT  i_contacts as total FROM '.$this->getTable(). ' WHERE fk_i_item_id = ' . $item_id ;
            $result = $this->dao->query($sql) ;

            $items = $result->result() ;
            if( is_array($items) && count($items)>0) {
                return $items[0]['total'];
            } else {
                return 0;
            }
        }

        /**
         * Delete house attributes given a item id
         * @param type $item_id
         */
        public function deleteItemStat($item_id)
        {
            return $this->dao->query("DELETE FROM ".$this->getTable()." WHERE fk_i_item_id = '" . $item_id . "'") ;
            return $this->dao->query("DELETE FROM ".$this->getTable_detail()." WHERE fk_i_item_id = '" . $item_id . "'") ;
        }

        public function init()
        {
            $result = $this->dao->query('SELECT pk_i_id FROM '.DB_TABLE_PREFIX.'t_item') ;

            $items = $result->result() ;
            foreach ($items as $item) {
                $this->insertItemStat($item['pk_i_id']);
            }
        }
    }

?>