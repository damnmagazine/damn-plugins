<?php
/**
 * Search & Filter Pro
 * 
 * @package   Search_Filter_Relationships
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2014 Designs & Code
 */

class Search_Filter_Relationships
{
	private $has_form_posted = false;
	private $hasqmark = false;
	private $hassearchquery = false;
	private $urlparams = "/";
	private $meta_query_var = array();
	private $count_table = array();
	//private $has_joined = false;
	
	private $frmreserved = array(); // a list of all field types that are reserved - good for deducing the field type, from the field name, by subtracting known types in this list...
	//private $frmqreserved = array();
	
	public function __construct($plugin_slug)
	{
		$this->plugin_slug = $plugin_slug;
		
	}
	
	public function get_taxonomy_term_counts_x3($taxonomies)
	{
		global $wpdb;
		
		if(!is_array($taxonomies))
		{
			$taxonomies = array($taxonomies);
		}
		
		foreach ($taxonomies as $taxonomy)
		{
			$taxonomy_join = "";
			$taxonomy_where = "";
			$tc = 1;
			
			if(isset($this->rel_query_args['taxonomies']))
			{
				$taxonomy_query_array = array();
				
				if(count($this->rel_query_args['taxonomies'] )>0)
				{
					//loop through all taxonomies and terms
					foreach ($this->rel_query_args['taxonomies'] as $qtaxonomy => $terms)
					{
						if($qtaxonomy!=$taxonomy)
						{
							unset($term_array);
							$term_array = array();
							//echo $qtaxonomy."<br />";
							//var_dump($terms);
							foreach($terms as $term)
							{
								//get term IDs						
								$term_object = get_term_by('slug', $term, $qtaxonomy);
								
								//$taxonomy_qarray[] = "trl.term_taxonomy_id='".$term_object->term_id."'";
								//echo $term_object->term_id;
								//echo $term;
								
								$term_array[] = "ttt$tc.term_id='".$term_object->term_id."'";
							}
							//var_dump($term_array);
							//echo "<hr />";
							if(count($term_array)>0)
							{
								$taxonomy_where .= ' AND ('.implode(" OR ", $term_array).')';
								
								$taxonomy_join .= " LEFT JOIN $wpdb->term_relationships as trr$tc ON (trr$tc.object_id = tr.object_id)";
								$taxonomy_join .= " LEFT JOIN $wpdb->term_taxonomy as ttt$tc ON (ttt$tc.term_taxonomy_id = trr$tc.term_taxonomy_id)";
								
								$tc++;
							}
						}
					}
				}
			}
			
			
			//$query = "SELECT t.term_id, t.name, tt.term_taxonomy_id as t_tax_id, tt.taxonomy, tt.count as wp_count, COUNT(tr.term_taxonomy_id)";
			$query = "SELECT t.term_id, t.name, tt.term_taxonomy_id as t_tax_id, tt.taxonomy, tt.count as wp_count";
			$query .= " FROM $wpdb->terms as t";
			$query .= " LEFT JOIN $wpdb->term_taxonomy as tt ON (tt.term_id = t.term_id)";
			$query .= " WHERE (tt.taxonomy='".$taxonomy."')";
			
			$terms = $wpdb->get_results($query);
			
			//echo "<h4>".$taxonomy."</h4>";
			
			//echo $this->pretty_table($terms);
			//echo "NO RESULTS: ".count($terms)."<br />";
			
			foreach ($terms as $term)
			{
				//echo $term->name." | ";
				//var_dump($term);
				$queryt = "SELECT COUNT(*) as counts";
				//$queryt = "SELECT *";
				$queryt .= " FROM $wpdb->term_relationships as tr";
				$queryt .= " LEFT JOIN $wpdb->term_taxonomy as tt ON (tt.term_taxonomy_id = tr.term_taxonomy_id)";
				
				$queryt .= $taxonomy_join;
				/*$queryt .= " LEFT JOIN $wpdb->term_relationships as trr1 ON (trr1.object_id = tr.object_id)"; //add for sizes
				$queryt .= " LEFT JOIN $wpdb->term_taxonomy as ttt1 ON (ttt1.term_taxonomy_id = trr1.term_taxonomy_id)"; //add for sizes
				
				$queryt .= " LEFT JOIN $wpdb->term_relationships as trr2 ON (trr2.object_id = tr.object_id)"; //add for weights
				$queryt .= " LEFT JOIN $wpdb->term_taxonomy as ttt2 ON (ttt2.term_taxonomy_id = trr2.term_taxonomy_id)"; //add for weights
				*/
				$queryt .= " WHERE (tt.term_taxonomy_id='".$term->t_tax_id."')";
				
				$queryt .= $taxonomy_where;
				//$queryt .= " AND (ttt1.term_id='62' OR ttt1.term_id='64')"; //add for sizes
				//$queryt .= " AND (ttt2.term_id='73')"; //add for weights
				
				$term_counts = $wpdb->get_results($queryt);
				
				foreach($term_counts as $term_count)
				{
					//$term->term_id = $term_count->counts;
					$this->count_table[SF_TAX_PRE.$term->term_id] = $term_count->counts;
				}
				//echo "<pre>";
				
				//echo $queryt;
				
				//echo "</pre>";
				
				//$this->pretty_table($term_counts);

			}
			
		}
	}
	
	public function get_taxonomy_term_counts_x2($taxonomies)
	{
		global $wpdb;
		
		if(!is_array($taxonomies))
		{
			$taxonomies = array($taxonomies);
		}
		//var_dump($taxonomies);
		
		foreach ($taxonomies as $taxonomy)
		{
			
			//$query = "SELECT t.term_id, t.name, tt.term_taxonomy_id as t_tax_id, tt.taxonomy, tt.count as wp_count, COUNT(tr.term_taxonomy_id)";
			$query = "SELECT t.term_id, t.name, tt.term_taxonomy_id as t_tax_id, tt.taxonomy, tt.count as wp_count, tr.*, COUNT(tr.term_taxonomy_id)";
			$query .= " FROM $wpdb->terms as t";
			$query .= " LEFT JOIN $wpdb->term_taxonomy as tt ON (tt.term_id = t.term_id)";
			$query .= " JOIN $wpdb->term_relationships as tr ON (tr.term_taxonomy_id = tt.term_taxonomy_id)";
			$query .= " INNER JOIN $wpdb->term_relationships as trr ON (trr.object_id = tr.object_id)";
			$query .= " LEFT JOIN $wpdb->term_taxonomy as ttt ON (trr.term_taxonomy_id = ttt.term_taxonomy_id)";
			$query .= " WHERE (tt.taxonomy='".$taxonomy."') AND ((ttt.term_id=62 OR ttt.term_id=64 OR ttt.term_id=73)";
			$query .= " GROUP BY tr.term_taxonomy_id AND GROUP BY ttt.term_id";
			
			$terms = $wpdb->get_results($query);
			
			echo "<h4>".$taxonomy."</h4>";
			
			echo $this->pretty_table($terms);
			echo "NO RESULTS: ".count($terms)."<hr />";
			
			/*$query = "SELECT tr.*, tt.*, p.post_title, t.name FROM $wpdb->term_relationships as tr";
			$query .= " JOIN $wpdb->term_taxonomy as tt ON (tt.term_taxonomy_id = tr.term_taxonomy_id)";
			$query .= " JOIN $wpdb->posts as p ON (p.ID = tr.object_id)";
			$query .= " JOIN $wpdb->terms as t ON (t.term_id = tt.term_id)";
			$query .= " WHERE (tt.taxonomy='".$taxonomy."')";
			$query .= " ORDER BY tt.taxonomy ASC, tt.term_id ASC";
			
			$terms = $wpdb->get_results($query);
			
			echo "<h4>".$taxonomy."</h4>";
			
			echo $this->pretty_table($terms);
			echo "NO RESULTS: ".count($terms)."<hr />";*/
			
		}
	}
	public function get_taxonomy_term_counts_x($taxonomies)
	{
		global $wpdb;
		
		if(!is_array($taxonomies))
		{
			$taxonomies = array($taxonomies);
		}
		//var_dump($taxonomies);
		
		foreach ($taxonomies as $taxonomy)
		{
		//if($taxonomy == "category")
		//{
			$query_post_type = "";
			if(isset($this->rel_query_args['post_types']))
			{
				$post_type_qarray = array();
				if(count($this->rel_query_args['post_types'] )>0)
				{
					foreach ($this->rel_query_args['post_types'] as $post_type)
					{
						$post_type_qarray[] = "p.post_type='".$post_type."'";
					}
					
					$query_post_type = ' AND ('.implode(" OR ", $post_type_qarray).')';
				}
			}
			
			
			$query_taxonomy = "";
			$join_taxonomy = "";
			$tax_count = 0;
			if(isset($this->rel_query_args['taxonomies']))
			{
				$taxonomy_qarray = array();
				
				if(count($this->rel_query_args['taxonomies'] )>0)
				{
					foreach ($this->rel_query_args['taxonomies'] as $qtaxonomy => $terms)
					{
						//echo $taxonomy."<hr />";
						if($qtaxonomy!=$taxonomy)
						{
							$tax_count++;
							foreach($terms as $term)
							{
								$term_object = get_term_by('slug', $term, $qtaxonomy);
								
								$taxonomy_qarray[] = "trl.term_taxonomy_id='".$term_object->term_id."'";
							}
						}
					}
					if($qtaxonomy!=$taxonomy)
					{
						if(count($taxonomy_qarray>0))
						{
							$query_taxonomy = ' AND ('.implode(" OR ", $taxonomy_qarray).')';
							$join_taxonomy  = " INNER JOIN $wpdb->term_relationships as trl ON (trl.object_id =  p.ID)";
						}
					}
				}
			}
			
			
			$join = "INNER JOIN $wpdb->term_relationships as tr ON (tr.object_id =  p.ID)";
			$join .= " INNER JOIN $wpdb->term_taxonomy as tt ON (tt.term_taxonomy_id =  tr.term_taxonomy_id)";
			$join .= " INNER JOIN $wpdb->terms as terms ON (terms.term_id =  tt.term_id)";
			$join  .= " LEFT JOIN $wpdb->term_relationships as trl ON (trl.object_id =  p.ID)";
			
			//$query = "SELECT p.ID, tr.object_id, p.post_title, tt.term_taxonomy_id as tt_term_taxonomy_id, tr.term_taxonomy_id as tr_term_taxonomy_id, tt.taxonomy, tt.term_id, terms.name FROM $wpdb->posts as p $join WHERE (tt.taxonomy='".$taxonomy."') AND p.post_status='publish'";
			//$query = "SELECT p.ID, tr.object_id, p.post_title, tt.term_taxonomy_id as tt_term_taxonomy_id, tr.term_taxonomy_id as tr_term_taxonomy_id, tt.taxonomy, count(tt.term_id), terms.name FROM $wpdb->posts as p $join WHERE (tt.taxonomy='".$taxonomy."') AND p.post_status='publish' GROUP BY tt.term_id";
			//$query = "SELECT count(tt.term_id), tt.term_id, terms.name FROM $wpdb->posts as p $join WHERE (tt.taxonomy='".$taxonomy."') AND p.post_status='publish' $query_post_type GROUP BY tt.term_id";
			$query = "SELECT p.ID as post_id, tt.taxonomy, tt.term_id, terms.name FROM $wpdb->posts as p $join WHERE (tt.taxonomy='".$taxonomy."') AND p.post_status='publish' $query_post_type";
			
			$terms = $wpdb->get_results($query);
			
			//$pretty = function($v='',$c="&nbsp;&nbsp;&nbsp;&nbsp;",$in=-1,$k=null)use(&$pretty){$r='';if(in_array(gettype($v),array('object','array'))){$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").'<br>';foreach($v as $sk=>$vl){$r.=$pretty($vl,$c,$in+1,$sk).'<br>';}}else{$r.=($in!=-1?str_repeat($c,$in):'').(is_null($k)?'':"$k: ").(is_null($v)?'&lt;NULL&gt;':"<strong>$v</strong>");}return$r;};
			
			
		//	if($taxonomy == "category")
		//{
				echo "<h4>".$taxonomy."</h4>";
				
				
				echo $this->pretty_table($terms);
				echo "NO RESULTS: ".count($terms)."<hr />";
		//}
			
		//}
		}
	}
	
	public function pretty_table($assoc_array)
	{
		$output = "";
		$output .= "<div style='padding:20px;'><table>";
		if(count($assoc_array)>0)
		{
			//set table headers
			$object_vars = get_object_vars ($assoc_array[0]);
			if(count($object_vars)>0)
			{
				$output .= "<tr>";
				foreach($object_vars as $key => $value)
				{
					$output .= "<th>".$key."</th>";
				}
				$output .= "</tr>";
			}
			
			foreach ($assoc_array as $array)
			{
				$output .= "<tr>";
				foreach ($array as $key=>$val)
				{
					$output .= "<th>".$val."</th>";
				}
				$output .= "</tr>";
			}
		}
		$output .= "</table></div>";
		echo $output;
	}

	public function get_taxonomy_term_counts($taxonomies)
	{
		global $wpdb;
		
		if(!is_array($taxonomies))
		{
			$taxonomies = array($taxonomies);
		}
		//var_dump($taxonomies);
		
		foreach ($taxonomies as $taxonomy)
		{
			$query_post_type = "";
			if(isset($this->rel_query_args['post_types']))
			{
				$post_type_qarray = array();
				if(count($this->rel_query_args['post_types'] )>0)
				{
					foreach ($this->rel_query_args['post_types'] as $post_type)
					{
						$post_type_qarray[] = "p.post_type='".$post_type."'";
					}
					
					$query_post_type = ' AND ('.implode(" OR ", $post_type_qarray).')';
				}
			}
			
			
			$query_taxonomy = "";
			$tax_count = 0;
			if(isset($this->rel_query_args['taxonomies']))
			{
				$taxonomy_qarray = array();
				
				if(count($this->rel_query_args['taxonomies'] )>0)
				{
					foreach ($this->rel_query_args['taxonomies'] as $qtaxonomy => $terms)
					{
						//echo $taxonomy."<hr />";
						//if($qtaxonomy!=$taxonomy)
						//{
							$tax_count++;
							foreach($terms as $term)
							{
								$term_object = get_term_by('slug', $term, $qtaxonomy);
								
								$taxonomy_qarray[] = "t.term_id='".$term_object->term_id."'";
							}
						//}
					}
					
					if($tax_count!=0)
					{
						$query_taxonomy = ' AND ('.implode(" OR ", $taxonomy_qarray).')';
					}
				}
			}
			
			$join = "INNER JOIN $wpdb->term_taxonomy as tt ON (tt.term_taxonomy_id =  tr.term_taxonomy_id)";
			$join .= " INNER JOIN $wpdb->posts as p ON (p.ID =  tr.object_id)";
			$join .= " INNER JOIN $wpdb->term_relationships as ptr ON (ptr.object_id = p.ID) INNER JOIN $wpdb->term_taxonomy as t ON (t.term_taxonomy_id = ptr.term_taxonomy_id)";
			
			$query = "SELECT DISTINCT tt.term_id, p.ID FROM $wpdb->term_relationships as tr $join WHERE (tt.taxonomy='".$taxonomy."') AND p.post_status='publish' $query_post_type $query_taxonomy";
			
			$terms = $wpdb->get_results($query);
			
			/*if($taxonomy=="colours")
			{
				echo "<pre>".$query."</pre>";
				
				echo "<h2>$taxonomy</h2>";
				
				echo "<pre>";
				
				
				var_dump($terms);
			}*/
			
			foreach ($terms as $term)
			{
				//echo $term->post_title."<br />";
				if(!array_key_exists(SF_TAX_PRE.$term->term_id, $this->count_table))
				{
					$this->count_table[SF_TAX_PRE.$term->term_id] = 0;
				}
				
				$this->count_table[SF_TAX_PRE.$term->term_id]++;
			}
			//var_dump($this->count_table);
			
			//echo "</pre>";
			//echo count($terms);
			
		}
	}
	
	
	public function get_term_count($term_id)
	{
		global $wpdb;
		
		$query_post_type = "";
		if(isset($this->rel_query_args['post_types']))
		{
			$post_type_qarray = array();
			if(count($this->rel_query_args['post_types'] )>0)
			{
				foreach ($this->rel_query_args['post_types'] as $post_type)
				{
					$post_type_qarray[] = "p.post_type='".$post_type."'";
				}
				
				$query_post_type = ' AND ('.implode(" OR ", $post_type_qarray).')';
			}
		}
		
		
		$query_taxonomy = "";
		if(isset($this->rel_query_args['taxonomies']))
		{
			$taxonomy_qarray = array();
			
			if(count($this->rel_query_args['taxonomies'] )>0)
			{
				foreach ($this->rel_query_args['taxonomies'] as $taxonomy => $terms)
				{
					//$taxonomy_qarray[] = "p.post_type='".$taxonomy."'";
					foreach($terms as $term)
					{
						$term_object = get_term_by('slug', $term, $taxonomy);
						
						$taxonomy_qarray[] = "t.term_id='".$term_object->term_id."'";
					}
				}
				
				$query_taxonomy = ' AND ('.implode(" OR ", $taxonomy_qarray).')';
			}
		}
		
		$join = "INNER JOIN $wpdb->term_taxonomy as tt ON (tt.term_taxonomy_id =  tr.term_taxonomy_id)";
		$join .= " INNER JOIN $wpdb->posts as p ON (p.ID =  tr.object_id)";
		$join .= " INNER JOIN $wpdb->term_relationships as ptr ON (ptr.object_id = p.ID) INNER JOIN $wpdb->term_taxonomy as t ON (t.term_taxonomy_id = ptr.term_taxonomy_id)";
		
		$query = "SELECT DISTINCT tr.*, p.post_title, p.post_status FROM $wpdb->term_relationships as tr $join WHERE (tt.term_id='".$term_id."') AND p.post_status='publish' $query_post_type $query_taxonomy";
		
		
		$terms = $wpdb->get_results($query);
		
		foreach ($terms as $term)
		{
			//echo $term->post_title."<br />";
		}
		
		//var_dump($terms);
		
		return count($terms);
		//var_dump($terms);
	}
	public function get_count_table()
	{
		return $this->count_table;
	}
	public function display_table($taxonomies)
	{
		if(!is_array($taxonomies))
		{
			$taxonomies = array($taxonomies);
		}
		
		
		$terms = get_terms($taxonomies);
		
		//foreach ($terms as $term)
		//{
		//	$this->count_table[SF_TAX_PRE.$term->term_id] = $this->get_term_count($term->term_id);

		//}
		
		
		
		echo "<h4>".implode($taxonomies, ", ")."</h4>";
		echo "<h5>Terms</h5>";
		echo "<table>";
		echo "<tr><th>ID</th><th>Name</th><th>Count</th><th>Dev Count</th><th></th></tr>";
		foreach ($terms as $term)
		{
			echo "<tr>";
			
			//echo $term->term_id.": ".$term->name." (".$term->count.")";
			echo "<td>".$term->term_id."</td>";
			echo "<td>".$term->name."</td>";
			echo "<td>".$term->count."</td>";
			echo "<td>".$this->get_term_count($term->term_id)."</td>";
			
			//echo $term->term_id.": ".$this->get_term_count($term->term_id);
			echo "</tr>";
		}
		
		echo "</table>";
		
	}
	
	public function init_relationships($query_args = array())
	{
		$this->rel_query_args = $query_args;
		
		//if(!is_admin())
		//{echo "HERE";
			//echo '<div style="padding:20px;">';
			//echo "INIT RELATIONSHIPS<hr />";
			
			$taxonomies = array("post_tag", "category", "sizes", "colours", "weights" );
			
			//$this->get_taxonomy_term_counts($taxonomies);
			
			$taxonomies = array("sizes", "colours", "weights" );
			//$this->get_taxonomy_term_counts_x($taxonomies);
			$taxonomies = array( "sizes", "colours", "category", "weights", "post_tag" );
			//$this->get_taxonomy_term_counts_x2($taxonomies);
			$this->get_taxonomy_term_counts_x3($taxonomies);
			//$this->display_table($taxonomies);
			
			/*echo "<pre>";
			
			echo "</pre>";
			
			echo "</div>";*/
		//}
	}
	
}
