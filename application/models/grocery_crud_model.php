<?php
/**
 * PHP grocery CRUD
 *
 * LICENSE
 *
 * Grocery CRUD is released with dual licensing, using the GPL v3 (license-gpl3.txt) and the MIT license (license-mit.txt).
 * You don't have to do anything special to choose one license or the other and you don't have to notify anyone which license you are using.
 * Please see the corresponding license file for details of these licenses.
 * You are free to use, modify and distribute this software, but all copyright information must remain.
 *
 * @package    	grocery CRUD
 * @copyright  	Copyright (c) 2010 through 2012, John Skoumbourdis
 * @license    	https://github.com/scoumbourdis/grocery-crud/blob/master/license-grocery-crud.txt
 * @version    	1.2
 * @author     	John Skoumbourdis <scoumbourdisj@gmail.com> 
 */

// ------------------------------------------------------------------------

/**
 * Grocery CRUD Model
 *
 *
 * @package    	grocery CRUD
 * @author     	John Skoumbourdis <scoumbourdisj@gmail.com>
 * @version    	1.2
 * @link		http://www.grocerycrud.com/documentation
 */
class grocery_CRUD_Model  extends CI_Model  {
    
	protected $primary_key = null;
	protected $table_name = null;
	protected $relation = array();
	protected $relation_n_n = array();
	protected $primary_keys = array();
	
	function __construct()
    {
        parent::__construct();
    }

    function db_table_exists($table_name = null)
    {
    	return $this->db->table_exists($table_name);
    }
    
    function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "\"{$this->table_name}\".*";
    	
    	//set_relation special queries 
    	if(!empty($this->relation))
    	{
    		foreach($this->relation as $relation)
    		{
    			list($field_name , $related_table , $related_field_title) = $relation;
    			$unique_join_name = $this->_unique_join_name($field_name);
    			$unique_field_name = $this->_unique_field_name($field_name);
    			
				if(strstr($related_field_title,'{'))
				{
					$related_field_title = str_replace(" ","&nbsp;",$related_field_title);
    				$select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE({$unique_join_name}.",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $unique_field_name";
				}
    			else
    			{    			
    				$select .= ", $unique_join_name.$related_field_title AS $unique_field_name";
    			}
    			
    			if($this->field_exists($related_field_title))
    				$select .= ",\"{$this->table_name}\".$related_field_title AS '{$this->table_name}.$related_field_title'";
    		}
    	}
    	
    	//set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
    	if(!empty($this->relation_n_n))
    	{
			$select = $this->relation_n_n_queries($select);
    	}
    		
    	$this->db->select($select, false);    	
    	
    	$results = $this->db->get($this->table_name)->result();
    	                
    	return $results;
    }
    
    public function set_primary_key($field_name, $table_name = null)
    {
    	$table_name = $table_name === null ? $this->table_name : $table_name;
    	
    	$this->primary_keys[$table_name] = $field_name;
    }
    
    protected function relation_n_n_queries($select)
    {
    	$this_table_primary_key = $this->get_primary_key();
    	foreach($this->relation_n_n as $relation_n_n)
    	{
    		list($field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
    					$primary_key_alias_to_selection_table, $title_field_selection_table, $priority_field_relation_table) = array_values((array)$relation_n_n);
    			 
    		$primary_key_selection_table = $this->get_primary_key($selection_table);
    		
	    	$field = "";
	    	$use_template = strpos($title_field_selection_table,'{') !== false;
	    	$field_name_hash = $this->_unique_field_name($title_field_selection_table);
	    	if($use_template)
	    	{
	    		$title_field_selection_table = str_replace(" ", "&nbsp;", $title_field_selection_table);
	    		$field .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$title_field_selection_table))."')";
	    	}
	    	else
	    	{
	    		$field .= "$selection_table.$title_field_selection_table";
	    	}
    			 
    		//Sorry Codeigniter but you cannot help me with the subquery!
    		$select .= ", (SELECT GROUP_CONCAT(DISTINCT $field) FROM $selection_table "
    			."LEFT JOIN $relation_table ON $relation_table.$primary_key_alias_to_selection_table = $selection_table.$primary_key_selection_table "
    			."WHERE $relation_table.$primary_key_alias_to_this_table =\"{$this->table_name}\".$this_table_primary_key GROUP BY $relation_table.$primary_key_alias_to_this_table) AS $field_name";
    	}

    	return $select;
    } 
    
    function order_by($order_by , $direction)
    {
    	$this->db->order_by( $order_by , $direction );
    }
    
    function where($key, $value = NULL, $escape = TRUE)
    {
    	$this->db->where( $key, $value, $escape);
    }
    
    function or_where($key, $value = NULL, $escape = TRUE)
    {
    	$this->db->or_where( $key, $value, $escape);
    }  

    function having($key, $value = NULL, $escape = TRUE)
    {
    	$this->db->having( $key, $value, $escape);
    }    

    function or_having($key, $value = NULL, $escape = TRUE)
    {
    	$this->db->or_having( $key, $value, $escape);
    }    
    
    function like($field, $match = '', $side = 'both')
    {   if (is_numeric($match)) //GARMASER
                $this->db->where($field, $match, TRUE);
        else
                $this->db->like($field, $match, $side);
        
    }
    
    function or_like($field, $match = '', $side = 'both')
    {   if (is_numeric($match)) 
        {//GARMASER´
                $this->db->or_where($field, $match, TRUE);
        }else{
            $this->db->or_like($field, $match, $side);
        }
    }    
    
    function limit($value, $offset = '')
    {
    	$this->db->limit( $value , $offset );
    }
    
    function get_total_results()
    {
    	//set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
    	if(!empty($this->relation_n_n))
    	{
    		$select = "{$this->table_name}.*";
    		$select = $this->relation_n_n_queries($select);
    		
    		$this->db->select($select,false);
    		
    		return $this->db->get($this->table_name)->num_rows();
    		    		
    	}
    	else 
    	{    	
    		return $this->db->get($this->table_name)->num_rows();
    	}
    }
    
    function set_basic_table($table_name = null)
    {
    	if( !($this->db->table_exists($table_name)) )
    		return false;
    	
    	$this->table_name = $table_name;
    	
    	return true;
    }
    
    function get_edit_values($primary_key_value)
    {
    	$primary_key_field = $this->get_primary_key();
    	$this->db->where($primary_key_field,$primary_key_value);
    	$result = $this->db->get($this->table_name)->row();
    	return $result;
    }
    
    function join_relation($field_name , $related_table , $related_field_title)
    {
		$related_primary_key = $this->get_primary_key($related_table);
		
		if($related_primary_key !== false)
		{
			$unique_name = $this->_unique_join_name($field_name);
			$this->db->join( $related_table.' as '.$unique_name , "$unique_name.$related_primary_key = {$this->table_name}.$field_name",'left');

			$this->relation[$field_name] = array($field_name , $related_table , $related_field_title);
			
			return true;
		}
    	
    	return false;
    }
    
    function set_relation_n_n_field($field_info)
    {    
		$this->relation_n_n[$field_info->field_name] = $field_info;
    }
    
    protected function _unique_join_name($field_name)
    {
    	return 'j'.substr(md5($field_name),0,8); //This j is because is better for a string to begin with a letter and not with a number
    }

    protected function _unique_field_name($field_name)
    {
    	return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }    
    
    function get_relation_array($field_name , $related_table , $related_field_title, $where_clause, $order_by, $limit = null, $search_like = null)
    {
    	$relation_array = array();
    	$field_name_hash = $this->_unique_field_name($field_name);
    	
    	$related_primary_key = $this->get_primary_key($related_table);
    	
    	$select = "$related_table.$related_primary_key, ";
    	
    	if(strstr($related_field_title,'{'))
    	{
    		$related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
    		$select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
    	}
    	else
    	{
	    	$select .= "$related_table.$related_field_title as $field_name_hash";
    	}
    	
    	$this->db->select($select,false);
    	if($where_clause !== null)
    		$this->db->where($where_clause);

    	if($where_clause !== null)
    		$this->db->where($where_clause);    	

    	if($limit !== null)
    		$this->db->limit($limit);    	
    	
    	if($search_like !== null)
    		$this->db->having("$field_name_hash::text LIKE '%".$this->db->escape_like_str($search_like)."%'");
    	
    	$order_by !== null 
    		? $this->db->order_by($order_by) 
    		: $this->db->order_by($field_name_hash);
    	
    	$results = $this->db->get($related_table)->result();
    	
    	foreach($results as $row)
    	{
    		$relation_array[$row->$related_primary_key] = $row->$field_name_hash;	
    	}
    	
    	return $relation_array;
    }
    
    function get_ajax_relation_array($search, $field_name , $related_table , $related_field_title, $where_clause, $order_by)
    {    
    	return $this->get_relation_array($field_name , $related_table , $related_field_title, $where_clause, $order_by, 10 , $search);
    }
    
    function get_relation_total_rows($field_name , $related_table , $related_field_title, $where_clause)
    {
    	if($where_clause !== null)
    		$this->db->where($where_clause);
    	
    	return $this->db->get($related_table)->num_rows();
    } 
    
    function get_relation_n_n_selection_array($primary_key_value, $field_info)
    {
    	$select = "";    	
    	$related_field_title = $field_info->title_field_selection_table;
    	$use_template = strpos($related_field_title,'{') !== false;;
    	$field_name_hash = $this->_unique_field_name($related_field_title);
    	if($use_template)
    	{
    		$related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
    		$select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
    	}
    	else
    	{
    		$select .= "$related_field_title as $field_name_hash";
    	}
    	$this->db->select('*, '.$select,false);
    	
    	$selection_primary_key = $this->get_primary_key($field_info->selection_table);
    	 
    	if(empty($field_info->priority_field_relation_table))
    	{
    		if(!$use_template){
    			$this->db->order_by($field_name_hash);
    		}
    	}
    	else
    	{
    		$this->db->order_by($field_name_hash);
    	}
    	$this->db->where($field_info->primary_key_alias_to_this_table, $primary_key_value);
    	$this->db->join(
    			$field_info->selection_table,
    			"{$field_info->relation_table}.{$field_info->primary_key_alias_to_selection_table} = {$field_info->selection_table}.{$selection_primary_key}"
    		);
    	$results = $this->db->get($field_info->relation_table)->result();
    	
    	$results_array = array();
    	foreach($results as $row)
    	{
    		$results_array[$row->{$field_info->primary_key_alias_to_selection_table}] = $row->{$field_name_hash};
    	}
    			 
    	return $results_array;
    }
    
    function get_relation_n_n_unselected_array($field_info, $selected_values)
    {
    	$use_where_clause = !empty($field_info->where_clause);
    	
    	$select = "";
    	$related_field_title = $field_info->title_field_selection_table;
    	$use_template = strpos($related_field_title,'{') !== false;
    	$field_name_hash = $this->_unique_field_name($related_field_title);
    	
    	if($use_template)
    	{
    		$related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
    		$select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
    	}
    	else
    	{
    		$select .= "$related_field_title as $field_name_hash";
    	}
    	$this->db->select('*, '.$select,false);
    	
    	if($use_where_clause){
    		$this->db->where($field_info->where_clause);	
    	}
    	
    	$selection_primary_key = $this->get_primary_key($field_info->selection_table);
        if(!$use_template)
        	$this->db->order_by("$field_name_hash");
        $results = $this->db->get($field_info->selection_table)->result();

        $results_array = array();
        foreach($results as $row)
        {
            if(!isset($selected_values[$row->$selection_primary_key]))
                $results_array[$row->$selection_primary_key] = $row->{$field_name_hash};
        }
        
        return $results_array;       
    }
    
    function db_relation_n_n_update($field_info, $post_data ,$main_primary_key)
    {
    	$this->db->where($field_info->primary_key_alias_to_this_table, $main_primary_key);
    	if(!empty($post_data))
    		$this->db->where_not_in($field_info->primary_key_alias_to_selection_table , $post_data);
    	$this->db->delete($field_info->relation_table);
    	
    	$counter = 0;
    	if(!empty($post_data))
    	{    
    		foreach($post_data as $primary_key_value)
	    	{
				$where_array = array(
	    			$field_info->primary_key_alias_to_this_table => $main_primary_key,
	    			$field_info->primary_key_alias_to_selection_table => $primary_key_value, 
	    		);
	    		
	    		$this->db->where($where_array);
				$count = $this->db->from($field_info->relation_table)->count_all_results();
				
				if($count == 0)
				{
					if(!empty($field_info->priority_field_relation_table))
						$where_array[$field_info->priority_field_relation_table] = $counter;
						
					$this->db->insert($field_info->relation_table, $where_array);
					
				}elseif($count >= 1 && !empty($field_info->priority_field_relation_table))
				{
					$this->db->update( $field_info->relation_table, array($field_info->priority_field_relation_table => $counter) , $where_array);
				}
				
				$counter++;
	    	}
    	}
    }
    
    function db_relation_n_n_delete($field_info, $main_primary_key)
    {
    	$this->db->where($field_info->primary_key_alias_to_this_table, $main_primary_key);
    	$this->db->delete($field_info->relation_table);
    } 
    function _field_data($table){

	$sqlGarmaser = "
                    SELECT 
                    f.attname AS \"name\", 
                    CASE  
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'timestamp without time zone' THEN 'datetime'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'date' THEN 'date'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'text' THEN 'text'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'integer' THEN 'int'  
                        ELSE 'varchar'
                    END AS \"type\",
                    CASE  
                        WHEN f.attnotnull = 'f' THEN ''
                    END AS \"max_length\",  

                    CASE  
                        WHEN p.contype = 'p' THEN '1'
                    END AS \"primary_key\"
                    
                FROM pg_attribute f  
                    JOIN pg_class c ON c.oid = f.attrelid  
                    JOIN pg_type t ON t.oid = f.atttypid  
                    LEFT JOIN pg_attrdef d ON d.adrelid = c.oid AND d.adnum = f.attnum  
                    LEFT JOIN pg_namespace n ON n.oid = c.relnamespace  
                    LEFT JOIN pg_constraint p ON p.conrelid = c.oid AND f.attnum = ANY (p.conkey)  
                    LEFT JOIN pg_class AS g ON p.confrelid = g.oid  

                WHERE c.relkind = 'r'::char 
                    AND c.relname = '".$this->table_name."'
                    AND f.attnum > 0 
                GROUP BY \"Field\", \"Type\", \"Null\", \"Key\", \"Default\", \"Extra\",f.attnum
                ORDER BY f.attnum
                ";
	$sql2 = "SELECT pg_attribute.attname as pk FROM pg_index, pg_class, pg_attribute WHERE pg_class.oid = '".$table."'::regclass AND indrelid = pg_class.oid AND pg_attribute.attrelid = pg_class.oid AND pg_attribute.attnum = any(pg_index.indkey);";
	
		$i = 0;
		$pk = $this->db->query($sql2)->result_array();
		//log_message('PHP','SQL: '. print_r($pk,true));
		foreach($this->db->query($sqlGarmaser)->result() as  $db_field_type)    	
    	{			
    		$type = explode("(",$db_field_type->type);
    		$db_type = $type[0];    		
			
    		if(isset($type[1]))
    		{
    			$length = substr($type[1],0,-1);
    		}
    		else 
    		{
    			$length = '';
    		}
			
    		$db_field_types[$i]['name'] = $db_field_type->field;
			$db_field_types[$i]['type'] = $db_type;    	
    		$db_field_types[$i]['max_length'] = $length;	
			//$db_field_types[$i]['primary_key'] = $db_field_type->primary_key;			
			$db_field_types[$i]['default'] = null;	
			
			if($pk[0]['pk'] == $db_field_type->field )
				$db_field_types[$i]['primary_key'] = 1;
			else
				$db_field_types[$i]['primary_key'] = '';
			
			$result[$i] = (object) $db_field_types[$i];
			$i++;
    	}			     
		 //log_message('PHP','Salida de _field_data: '.$table . print_r($result,true));
		 exit($table . print_r($result,true));
		 //return $result;
                return ($this->db->query($sqlGarmaser)->result());
	}
    
    function get_field_types_basic_table()
    {   $sqlGarmaser = "
                    SELECT 
                    f.attname AS \"Field\", 
                    CASE  
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'timestamp without time zone' THEN 'datetime'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'date' THEN 'date'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'text' THEN 'text'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'integer' THEN 'int'
                        WHEN pg_catalog.format_type(f.atttypid,f.atttypmod) = 'boolean' THEN 'longint' 
                        ELSE 'varchar'
                    END AS \"Type\",  

                    CASE  
                        WHEN f.attnotnull = 'f' THEN 'YES'  
                        ELSE 'NO'  
                    END AS \"Null\",  

                    CASE  
                        WHEN p.contype = 'p' THEN 'PRI'
                    END AS \"Key\",
                    CASE
                        WHEN f.atthasdef = 't' THEN d.adsrc
                    END AS \"Default\",

                   CASE
                        WHEN f.atthasdef = 't' THEN ''
                    END AS \"Extra\"

                FROM pg_attribute f  
                    JOIN pg_class c ON c.oid = f.attrelid  
                    JOIN pg_type t ON t.oid = f.atttypid  
                    LEFT JOIN pg_attrdef d ON d.adrelid = c.oid AND d.adnum = f.attnum  
                    LEFT JOIN pg_namespace n ON n.oid = c.relnamespace  
                    LEFT JOIN pg_constraint p ON p.conrelid = c.oid AND f.attnum = ANY (p.conkey)  
                    LEFT JOIN pg_class AS g ON p.confrelid = g.oid  

                WHERE c.relkind = 'r'::char 
                    AND c.relname = '".$this->table_name."'
                    AND f.attnum > 0 
                GROUP BY \"Field\", \"Type\", \"Null\", \"Key\", \"Default\", \"Extra\",f.attnum
                ORDER BY f.attnum
                ";     
        
    	$db_field_types = array();
    	//foreach($this->db->query("SHOW COLUMNS FROM \"{$this->table_name}\"")->result() as $db_field_type)
        foreach($this->db->query($sqlGarmaser)->result() as $db_field_type)
    	{
    		$type = explode("(",$db_field_type->Type);
    		$db_type = $type[0];
    		
    		if(isset($type[1]))
    		{
    			if(substr($type[1],-1) == ')')
    			{
    				$length = substr($type[1],0,-1);
    			}
    			else
    			{
    				list($length) = explode(" ",$type[1]);
    				$length = substr($length,0,-1);
    			}
    		}
    		else 
    		{
    			$length = '';
    		}
    		$db_field_types[$db_field_type->Field]['db_max_length'] = $length;
    		$db_field_types[$db_field_type->Field]['db_type'] = $db_type;
    		$db_field_types[$db_field_type->Field]['db_null'] = $db_field_type->Null == 'YES' ? true : false;
    		$db_field_types[$db_field_type->Field]['db_extra'] = $db_field_type->Extra;
    	}

    	$results = $this->db->field_data($this->table_name);
    	foreach($results as $num => $row)
    	{
    		$row = (array)$row;
                //garmaser
                if (!empty($db_field_types)) 
                   $results[$num] = (object)( array_merge($row, $db_field_types[$row['name']])  );
    	}
    	
    	return $results;
    }
    
    function get_field_types($table_name)
    {
    	$results = $this->db->_field_data($table_name);
    	
    	return $results;
    }
    
    function db_update($post_array, $primary_key_value)
    {
    	$primary_key_field = $this->get_primary_key();
    	return $this->db->update($this->table_name,$post_array, array( $primary_key_field => $primary_key_value));
    }    
    
    function db_insert($post_array)
    {
    	$insert = $this->db->insert($this->table_name,$post_array);
    	if($insert)
    	{
    		return $this->db->insert_id();
    	}
    	return false;
    }
    
    function db_delete($primary_key_value)
    {
    	$primary_key_field = $this->get_primary_key();
    	
    	if($primary_key_field === false)
    		return false;
    	
    	//garmaser
        //$this->db->limit(1);
    	$this->db->delete($this->table_name,array( $primary_key_field => $primary_key_value));
    	if( $this->db->affected_rows() != 1)
    		return false;
    	else
    		return true;
    }

    function db_file_delete($field_name, $filename)
    {
    	if( $this->db->update($this->table_name,array($field_name => ''),array($field_name => $filename)) )
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    function field_exists($field,$table_name = null)
    {
    	if(empty($table_name))
    	{
    		$table_name = $this->table_name;
    	}
    	return $this->db->field_exists($field,$table_name);
    }    
    
    function get_primary_key($table_name = null)
    {
    	if($table_name == null)
    	{
    		if(isset($this->primary_keys[$this->table_name]))
    		{
    			return $this->primary_keys[$this->table_name];
    		}
    		
	    	if(empty($this->primary_key))
	    	{
		    	$fields = $this->get_field_types_basic_table();
		    	
		    	foreach($fields as $field)
		    	{
		    		if($field->primary_key == 1)
		    		{
		    			return $field->name;
		    		}	
		    	}
		    	
		    	return false;
	    	}
	    	else
	    	{
	    		return $this->primary_key; 
	    	}
    	}
    	else
    	{
    		if(isset($this->primary_keys[$table_name]))
    		{
    			return $this->primary_keys[$table_name];
    		}
    		
	    	$fields = $this->get_field_types($table_name);
	    	
	    	foreach($fields as $field)
	    	{
	    		if($field->primary_key == 1)
	    		{
	    			return $field->name;
	    		}	
	    	}
	    	
	    	return false;
    	}
    	
    }
    
    function escape_str($value)
    {
    	return $this->db->escape_str($value);
    }
		
}