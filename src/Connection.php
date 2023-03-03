<?php
class Connection
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $pdo; 
					

     function __construct()
    {
        $this->username = 'root'; //database server username;
        $this->password = ''; //database server password;
		 
// 		    $this->connect();
//             $preparedQuery = $this->pdo->query("SELECT Expiry FROM Activiation WHERE ID = 884");
// 		 var_dump( $preparedQuery->fetchAll());die;
    }

    private function connect()
    {
        $this->pdo =new PDO("odbc:mssql_odbc", $this->username, $this->password);
		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
   }


    public function runQuery($sql,$value,$nam)
    {

        try {

            $this->connect();
            $preparedQuery = $this->pdo->prepare($sql);
            $preparedQuery->execute(array($value,$nam, PDO::PARAM_STR));
        } 
        catch (PDOException $e) {
            $this->pdo->rollBack();
            return $e->getMessage();
        }
    }
}
