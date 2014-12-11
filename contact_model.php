<?php
require_once 'configz.php';
class contact_model {

  private $connection, $real_escape_string_exists, $magic_quotes_active;

  function __construct() {
      $this->open_connection();
      $this->magic_quotes_active=get_magic_quotes_gpc();
      $this->real_escape_string_exists=function_exists('mysql_real_escape_string');
  }

  public function mysql_prep($value) {

    if ($this->real_escape_string_exists) { //PHP v4.3.0 or higher
      //undo any magic quote effects to mysql_real_escape_string can do the work
      if ($this->magic_quotes_active) { $value = stripslashes($value); }
      $value = mysql_real_escape_string($value);
    } else { // before  PHP V4.3.0
      //if magic quotes aren't already on then add slashes manually
      if (!$this->magic_quotes_active) { $value = addslashes($value); }
      //if magic quotes are active, then the slashes already exist.
    }
    return $value;
   }

  public function open_connection() {
      $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
      if (!$this->connection) {
        die("Database connection failed: ".mysql_error());
      } else {
      $db_select = mysql_select_db(DB_NAME, $this->connection);
        if (!$db_select) {
           die("Database selection failed: ".mysql_error());
        }
      }
  }

  public function close_connection() {
      if (isset($this->connection)) {
        mysql_close($this->connection);
        unset($this->connection);
      }
  }

  public function query_db($sql) {
    $result=mysql_query($sql);
    if (!$result) {
      die("Database query failed: ".mysql_error());
    }
    return $result;
}


   public function get_contacts() {
      $sql="SELECT * FROM tbl_contact ORDER BY name ASC;";
      $result_set=$this->query_db($sql);
      return $result_set;
   }

   public function update_contact($accesstype=0) {
     $sntz_id=$_POST['id'];
     $sntz_name=ucwords(strtolower($_POST['name']));
     $sntz_phone=$_POST['phone'];
     $sntz_address=ucwords(strtolower($_POST['address']));
     $sntz_email=trim($_POST['email']);
     $sntz_status=ucwords(strtolower($_POST['status']));
     $sntz_name=str_replace('"', '', $sntz_name);
     $sntz_address=str_replace('"', '', $sntz_address);
     //$sntz_name=trim(mysql_real_escape_string($sntz_name));
     $sntz_name=trim($this->mysql_prep($sntz_name));
     //$sntz_address=trim(mysql_real_escape_string($sntz_address));
     $sntz_address=trim($this->mysql_prep($sntz_address));
     if ($accesstype) {

           /*   $sql="UPDATE tbl_contact SET name={$sntz_name},
                                      phone={$sntz_phone},
                                      address={$sntz_address},
                                      email={$sntz_email},
                                      status={$sntz_status}
                                 WHERE id = {$sntz_id}"; */
              $sql='UPDATE tbl_contact SET name="'.$sntz_name.'",
                                      phone="'.$sntz_phone.'",
                                      address="'.$sntz_address.'",
                                      email="'.$sntz_email.'",
                                      status="'.$sntz_status.'"
                                 WHERE id = "'.$sntz_id.'"';



     } else {
         $sql='INSERT INTO tbl_contact (name, phone, address, email, status)
              VALUES ("'.$sntz_name.'", "'.$sntz_phone.'", "'.$sntz_address.'", "'.$sntz_email.'", "'.$sntz_status.'")';

     }
    $this->query_db($sql);
      return TRUE;
   }

   public function delete_contact($id="") {
     $sql="DELETE FROM tbl_contact WHERE id={$id}";
     $this->query_db($sql);
      return TRUE;
   }

   public function get_contact($id="") {
     $sql="SELECT * FROM tbl_contact WHERE id={$id}";
     $result_set=$this->query_db($sql);
     return mysql_fetch_array($result_set);
   }


}
?>