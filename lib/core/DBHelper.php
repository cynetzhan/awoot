<?php
	function getData($colom,$table,$where="",$order="",$limit="")
	{
		global $db;
		$query="select $colom from $table $where $order $limit";
		//echo $query;
		$sql=mysqli_query($db, $query);
		if(!$sql)
		{
			echo"select $colom from $table $where $order $limit";
			echo mysqli_error($db);
			exit;
		}
		return $sql;
	}
	
	function getNum($table,$where)
	{
		global $db;
		$query="select * from $table $where";
		$sql=mysqli_query($db, $query);
		return mysqli_num_rows($sql);
	}
	
	function deleteData($table,$where)
	{
		global $db;
		$sql = mysqli_query($db, "delete from $table $where");
		if(!$sql)
		{
			echo"delete from $table $where";
			mysqli_error($db);
			exit;
		}
	}
	
	function insertData($table,$values)
	{
		global $db;
		$values= implode(",", $values);
		//print("insert into $table values($values)");
		$sql = mysqli_query($db, "insert into $table values($values)");
		if(!$sql)
		{
			echo"insert into $table values($values)";
			echo mysqli_error($db);
			exit;
		}
	}
	
	function insertData_select($table,$field,$values)
	{
		global $db;
		$val=array();
		foreach($values as $row)
		{
			$escape=str_replace("'","\'",$row);
			array_push($val,"'$escape'");
		}
		$field= implode(",",$field);
		$val= implode(",",$val);
		$sql=mysqli_query($db, "insert into $table($field) values($val)");
		if(!$sql)
		{
			echo"insert into $table($field) values($val)";
			mysqli_error($db);
			exit;
		}
	}
	
	function updateData($table,$values,$key)
	{
		global $db;
		$newVal=array();
		foreach($values as $row)
		{
			$pecah=explode("='",$row);
			//buang ' pada akhir
			$akhir=substr($pecah[1], 0, -1);
			//buang ' pada awal
			//$depan=substr($akhir,1);
			
			$escape=str_replace("'","\'",$akhir);
			array_push($newVal,"$pecah[0]='$escape'");
		}
		$newVal= implode(",",$newVal);
		//print("update $table set $values $key");
		$sql = mysqli_query($db, "update $table set $newVal $key");
		if(!$sql)
		{
			echo"update $table set $newVal $key";
			mysqli_error($db);
			exit;
		}
		
	}
	
		
	function getIndex($colom,$table,$where,$limit)
	{
		global $db;
		$sql=mysqli_query($db, "select $colom from $table $where $limit");
		$data=mysqli_fetch_array($sql);
		return $data[$colom];
	}
 
	function escape_data($d)
	{
		if(is_array($d)){
			foreach($d as $key=>$val){
				$d[$key] = escape_data($val);
			}
		} else {
			$d=(stripslashes(strip_tags(htmlspecialchars($d, ENT_QUOTES))));
		}
		return $d;
	}

	function fetchQuery($query){
		$data = [];
		while($row = mysqli_fetch_assoc($query)){
			$data[] = $row;
		}
		return $data;
	}

	function getLastID(){
		global $db;
		return mysqli_insert_id($db);
	}

	function getEnumList($table, $column){
		global $db;
		$coldef = fetchQuery(mysqli_query($db, "desc $table"));
		$row_with_enum = array_search($column, array_column($coldef, "Field"));
		$enum_raw = $coldef[$row_with_enum]['Type'];
		$enum_raw = str_replace(["enum(",")"], "",$enum_raw);
		$enum_raw = str_replace("'", "", $enum_raw);
		return explode(",",$enum_raw);

	}
?>
<?php
// WIP: DB Query Builder/Helper
class DBHelper {
	private $connection;
	private $table;
	private $criteria;
	
	function __construct($connection, $table=""){
		$this->connection = $connection; // $connection must be mysqli_link
		if($table !== ""){
			$this->table = $table;
		}
	}

	function from($table_name){
		$this->table = $table_name;
		return $this;
	}

	function where($criteria){
		$where_query = "";
		if(is_array($criteria)){
			while($crit = current($criteria)){
				$where_query .= key($criteria)." = '".$crit."'";
				if(next($criteria)){
					$where_query .= " AND ";
				}
			}
		}
		if(is_string($criteria)){
			$where_query = $criteria;
		}
		if($this->criteria != ""){
			$this->criteria .= " AND ".$where_query;
		}
		return $this;
	}

	function insert(array $data){
		$keys = "";
		if($this->has_string_keys($data)){
			$keys = "(".implode(", ", array_keys($data)).")";
		}
		$values = "('".implode("', '", array_values($data))."')";
		$query = "INSERT INTO $this->table $keys VALUES $values";
		echo $query;
		//return $this->execute($query);
	}

	function get($column = "*"){
		$query = "SELECT ".$column." FROM $this->table ".(($this->criteria != "")?"WHERE $this->criteria":"");
		return $this->fetch($query);
	}

	function execute($query){
		return mysqli_query($this->connection, $query);
	}

	function fetch($query){
		$data = [];
		$result = $this->execute($query);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}

	private function has_string_keys(array $array){
		return count(array_filter(array_keys($array), 'is_string')) > 0;
	}

}

$dbhelper = new DBHelper($db);