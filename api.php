<?php

class Test{
  public $servername = 'server';
  public $dbname = 'db';
  public $table_name='table';
  public $username = 'user';
  public $password = 'pass';
  public $api_key="key";

  function domain_search(){
    //Get data from input field
    $data=$_POST;
    $url='https://hunter.io/api/v2/domain-search';
    //company name
    $company = explode(".", $data['domain']);
    $company=$company[0];
    //api key
    $api_key=$this->api_key;
    //generate url for api request
    $url = 'https://api.hunter.io/v2/domain-search?domain='.$data['domain'].'&api_key='.$company.'&api_key='.$api_key;

    // Construct cURL resource
    $data = curl_init($url);
    curl_setopt ($data, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($data, CURLOPT_HTTPGET, 1);    
    $result = curl_exec($data);
    $result = json_decode($result, true);
    curl_close($data);
    return $result;
  }

  function save_data(){
    $servername=$this->servername;
    $dbname=$this->dbname;
    $username=$this->username;
    $password=$this->password;
    $table_name = $this->table_name;
    //connectin to database
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //get data of api
      $result =$this->domain_search();
      $domain=$result['data']['domain'];
      foreach($result['data']['emails'] as $value){
        $query_check = "SELECT id FROM $table_name WHERE email='$value[value]'";
        $data_check = $conn->query($query_check);
        $data_check =$data_check->fetch();
        if($data_check == ''){
          
          $query = "INSERT INTO $table_name (domain,email, first_name, last_name, types ) VALUES ('$domain','$value[value]', '$value[first_name]', '$value[last_name]','$value[type]'); ";
          $conn->query($query);
        
      
        }
      }
        
        return 'OK';
      }
      catch(PDOException $e)
        {
        $result ="Connection failed: " . $e->getMessage();
        
        }

        return $result;
        $conn = null;
  }

  function get_data(){
    $data=$_POST;
    $servername=$this->servername;
    $dbname=$this->dbname;
    $username=$this->username;
    $password=$this->password;
    $table_name = $this->table_name;
    //connectin to database
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //get data from database
      $query_check = "SELECT domain, email, first_name, last_name, types  FROM $table_name WHERE domain='$data[domain]'";
      $users = $conn->query($query_check);
      $result = $users->fetchAll();
    
        return $result;
      }
      catch(PDOException $e)
        {
        $result ="Connection failed: " . $e->getMessage();
        return $result;
        }

        $conn = null;
  }

  function create_table(){
    $data=$_POST;
    $servername=$this->servername;
    $dbname=$this->dbname;
    $username=$this->username;
    $password=$this->password;
    $table_name = $this->table_name;
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      
      //check table
      $table = $conn->query("SHOW TABLES LIKE '" . $table_name . "'")->rowCount();
      if($table == 1) {
       
        $result="OK";
      }else{
      //create table
        $query = "CREATE TABLE IF NOT EXISTS $table_name (
					id int(11) AUTO_INCREMENT PRIMARY KEY,
					domain varchar(255) character set utf8 NOT NULL,
					email varchar(255) character set utf8 NOT NULL,
					first_name varchar(255) character set utf8 NOT NULL,
          last_name varchar(255) character set utf8 NOT NULL,
          types varchar(255) character set utf8 NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        $conn->exec($query);
        $result="OK";
      }
    
        return $result;
      }
      catch(PDOException $e)
        {
        $result ="Connection failed: " . $e->getMessage();
        return $result;
        }

        $conn = null;
  }
}


