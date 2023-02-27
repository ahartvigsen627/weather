<?php
class Database
{
    protected $connection = null;
    
    /**
     * The constructor establishes a database connection
     */
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
    	
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }
    
    /**
     * This calls executeStatement then gets and returns the results of the executed statement.
     * 
     * @param query - the database query string
     * @param params - an array of parameters to be inserted into the query. initialize to blank array if params not provided
     */
    public function select($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }
    
     /**
     * This takes the query string and prepares and executes the query in the database
     * 
     * @param query - the database query string
     * @param params - an array of parameters to be inserted into the query. initialize to blank array if params not provided
     */
    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if( $params ) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }
}