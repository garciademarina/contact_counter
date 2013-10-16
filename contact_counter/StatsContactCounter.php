<?php

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
     * Stats
     */
    class StatsContactCounter
    {
        /**
         *
         * @var type
         */
        private static $instance;
        private $conn;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *
         */
        function __construct()
        {
            $conn = DBConnectionClass::newInstance();
            $data = $conn->getOsclassDb();
            $this->conn = new DBCommandClass($data);
        }

        public function count_contacts($from_date, $date = 'day')
        {
            $this->conn->select('COUNT(fk_i_item_id) as num');
            $this->conn->from(DB_TABLE_PREFIX.'t_item_counter_detail');
            $this->conn->where("dt_date > '$from_date'");

            $result = $this->conn->get();
            if(!is_array($result->result())){
                return 0;
            }

            $aux = $result->result();
            return $aux[0]['num'];
        }

        public function new_contacts_count($from_date, $date = 'day')
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_date) as d_date, COUNT(fk_i_item_id) as num');
                $this->conn->groupBy('WEEK(dt_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_date) as d_date, COUNT(fk_i_item_id) as num');
                $this->conn->groupBy('MONTH(dt_date)');
            } else {
                $this->conn->select('DATE(dt_date) as d_date, COUNT(fk_i_item_id) as num');
                $this->conn->groupBy('DAY(dt_date)');
            }
            $this->conn->from(DB_TABLE_PREFIX.'t_item_counter_detail');
            $this->conn->where("dt_date > '$from_date'");
            $this->conn->orderBy('dt_date', 'DESC');

            $result = $this->conn->get();
            return $result->result();
        }

        public function count_contacts_item($from_date, $id, $date = 'day')
        {
            $this->conn->select('COUNT(fk_i_item_id) as num');
            $this->conn->from(DB_TABLE_PREFIX.'t_item_counter_detail');
            $this->conn->where("dt_date > '$from_date'");
            $this->conn->where("fk_i_item_id = ".$id);

            $result = $this->conn->get();
            if(!is_array($result->result())){
                return 0;
            }

            $aux = $result->result();
            return $aux[0]['num'];
        }

        public function new_contacts_count_item($from_date, $id, $date = 'day')
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_date) as d_date, COUNT(fk_i_item_id) as num');
                $this->conn->groupBy('WEEK(dt_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_date) as d_date, COUNT(fk_i_item_id) as num');
                $this->conn->groupBy('MONTH(dt_date)');
            } else {
                $this->conn->select('DATE(dt_date) as d_date, COUNT(fk_i_item_id) as num');
                $this->conn->groupBy('DAY(dt_date)');
            }
            $this->conn->from(DB_TABLE_PREFIX.'t_item_counter_detail');
            $this->conn->where("dt_date > '$from_date'");
            $this->conn->where("fk_i_item_id = ".$id);
            $this->conn->orderBy('dt_date', 'DESC');

            $result = $this->conn->get();
            if(!is_array($result->result())){
                return array();
            }

            return $result->result();
        }

    }
?>