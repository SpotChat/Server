<?php
class database{
	private $host,$user,$dbname,$pass,$con;
	public function __Construct($database=Array()){
		$this->host=(isset($database['host']))?$database['host']:"";
		$this->user=(isset($database['user']))?$database['user']:"";
		$this->dbname=(isset($database['dbname']))?$database['dbname']:"";
		$this->pass=(isset($database['pass']))?$database['pass']:"";
	}
	public function connect(){
		$this->con=mysqli_connect($this->host,$this->user,$this->pass,$this->dbname)or die("Error". mysqli_connect_error());
	}
	public function tables(){
		$this->connect();
		$tableList = array();
		$res = mysqli_query($this->con,"SHOW TABLES")or die("Error description: " . mysqli_error($this->con));
		while($row = mysqli_fetch_array($res)){
			$tableList[] = $row[0];
			trace($row);
		}
		return $tableList;
	}
	public function select($table,$fields="*",$where="",$debug=false){
		$sql="select $fields from $table $where";
        $data=Array();
        $this->connect();
        if($debug==true){
                $result=mysqli_query($this->con,$sql)or die("Error in the statement".$sql." i.e. ".mysql_error());
        }else{
                $result=mysqli_query($this->con,$sql)or die();
        }
        
        $m=$result->field_count;
        $n=$result->num_rows;
        $data['rows']=$n;
        $flds=mysqli_fetch_fields($result);
        for($j=0;$j<$m;$j++){
        	$fld[$j]=$flds[$j]->name;
        }
        $data['fields']=$fld;
        for($i=0;$i<$n;$i++){
            $d=mysqli_fetch_array($result);
            for($j=0;$j<$m;$j++){
            	$data[$i][$j]=$d[$j];
            }
        }
        $this->disconnect();
        return $data;
	}
	public function insert($table,$data,$debug=false){
		$sql="insert into $table set ";
		$fld=Array();
		foreach($data as $key=>$value){
			$fld[]=(is_numeric($value))?" $key={$value}":" $key='{$value}'";
			
		}
		$sql.=$fld[0];
		for($i=1;$i<count($fld);$i++)$sql.=" , ".$fld[$i];
		return $this->query($sql,$debug);
	}
	public function update($table,$data,$where="",$debug=false){
		$sql="update `{$table}` set ";
		$fld=Array();
		foreach($data as $key=>$value){
			$fld[]=(is_numeric($value))?" $key={$value}":" $key='{$value}'";
			
		}
		$sql.=$fld[0];
		for($i=1;$i<count($fld);$i++)$sql.=" , ".$fld[$i];
		$sql.=" ".$where;
		return $this->query($sql,$debug);
	}
	public function delete($table,$where="",$debug=false){
		$sql="delete from `{$table}` ";
		$sql.=" ".$where;
		return $this->query($sql,$debug);	
	}
   	public function resa($var){
        $data=Array();
        foreach ($var as $key => $value) {
                $data[$key]=$this->res($value);
        }
        return $data;
	}
	public function res($var){
		$this->connect();
		$val=mysqli_escape_string($this->con,$var);
        $this->disconnect();
        return $val;
	}
	public function disconnect(){

	}
	public function query($sql,$debug=false){
	    $this->connect();
	    $result=mysqli_query($this->con,$sql);
	    $this->disconnect();
	    return $result;
    }
}
?>