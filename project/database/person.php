<?php
    
  /**
   * Person class - parent to employee, patient, and visitor
   */
  class person
  {

    protected $name;
    protected $address;
    protected $phoneNumber;
    protected $gender;
    protected $sex;
    protected $birthDate;

    protected $personInfoAdded;

    public function addPersonInfo($name,$address,$phoneNumber,$gender,$sex,$birthDate){
      
      $personInfoStrings_Arr = array($name,$address,$gender,$sex,$birthDate);
      $personVars = array(&$this->name,&$this->address,&$this->gender,&$this->sex,&$this->birthDate);

      for ($i = 1; $i <= sizeof($personInfoStrings_Arr); $i++) {
        if ($this->checkStringValidity($personInfoStrings_Arr[$i-1])){ 
          $personVars[$i-1] = $personInfoStrings_Arr[$i-1];
        } else {
          return $i*-1; //-1 for a name error, -2 for an address error, and so on
        }
      }
      
      if ($this->checkIntegerValidity($phoneNumber)) {
        $this->phoneNumber = $phoneNumber;
      } else {
        return -6;
      }
      
      $this->personInfoAdded = True;
      return 0;
    } 

    // common methods
    protected function insertPerson(){
      global $dbh;
      $stmt = $dbh->prepare('INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) 
        VALUES (?,?,?,?,?,?)');
      $stmt->execute(array($this->name, $this->address,$this->phoneNumber,
        $this->gender,$this->sex,$this->birthDate));
    }

    protected function getLastPersonAdded(){
      // largest id is last person added (due to autoincrement)
      global $dbh;
      $stmt = $dbh->prepare('SELECT MAX(id_person) FROM person');
      $stmt->execute();
      $lastPerson_id = $stmt->fetch();
      return $lastPerson_id['MAX(id_person)'];
    }
    
    // utils
    // Check if it's a string and not empty
    protected function checkStringValidity($string_var){
      if (!empty($string_var) && is_string($string_var)){ 
        return True;
      } else {
        return false;
      }
    }

    // Check if it's an actual phone number
    protected function checkIntegerValidity($integer_var){
      if (!empty($integer_var) && $integer_var > 100000000 && $integer_var < 1000000000) {
        return True;
      } else{
        return false;
      }
    }

  }

  /**
   * Employee class
   */
  final class employee extends person
  { 

    private $id_employee;
    private $adminPrivilege;
    private $username;
    private $password;
    private $type;
    private $joiningDate;
    private $education;
    private $certification;
    private $languages;
    private $id_superior;
    private $id_specialty;

    private $employeeInfoGotten; 

    function __construct(){
      $this->employeeInfoGotten = False;
      $this->personInfoAdded = False;
    }

    public function addEmployeeInfo($adminPrivilege,$username,$password,$type,$joiningDate,$education,$certification,$languages,$id_superior,$id_specialty){

      $employeeInfoStrings_Arr = array($username,$type,$joiningDate,$education,$certification,$languages,$password);
      $employeeVars = array(&$this->username,&$this->type,&$this->joiningDate,&$this->education,&$this->certification,&$this->languages,&$this->password);

      for ($i = 1; $i <= sizeof($employeeInfoStrings_Arr); $i++) {
        if ($this->checkStringValidity($employeeInfoStrings_Arr[$i-1])){ 
          if ($i == 7){
            $employeeVars[$i-1] = sha1($employeeInfoStrings_Arr[$i-1]); // encrypt password
          } elseif ($i == 1) {
            // check if alphanumeric and that there are no other usernames
            $username = $employeeInfoStrings_Arr[$i-1];
            if (checkIfUsernameExists($username) || checkIfNotAlphaNumeric($username)){
              return $i*-1;
            } else {
              $employeeVars[$i-1] = $employeeInfoStrings_Arr[$i-1];
            }
          } else {
            $employeeVars[$i-1] = $employeeInfoStrings_Arr[$i-1];
          }
        } else {
          return $i*-1; //-1 for a userName error, -2 for a password error, and so on
        }
      }

      if (is_string($adminPrivilege)) { // used as a bool
        if ($adminPrivilege == 'Admin') { // Deal with php considering "0" empty
          $this->adminPrivilege = 1;
        } elseif ($adminPrivilege == 'NotAdmin') {
          $this->adminPrivilege = 0;
        } else{
          return -8; 
        }
      } else {
        return -8;  
      }

      // id_s have been checked - foreign keys
      $this->id_superior = $id_superior;
      $this->id_specialty = $id_specialty;

      return 0;
    }

    private function insertEmployee($lastPerson_idGotten) {
      global $dbh;
      $stmt = $dbh->prepare('INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?)'); 
      $stmt->execute(array($lastPerson_idGotten,$this->adminPrivilege,$this->username,$this->password,$this->type,$this->joiningDate,$this->education,$this->certification,$this->languages,$this->id_superior,$this->id_specialty)); 
    }

    public function insertIntoDatabase() {
      // get variables
      if ($this->personInfoAdded) {
        $this->insertPerson();
      } else {
        return -1;
      }
      
      // check last person added
      $lastPerson_id = $this->getLastPersonAdded();
      $this->id_employee = $lastPerson_id; 
      // insert Employee
      $this->insertEmployee($lastPerson_id);

      $this->employeeInfoGotten = True;
      return 0;
    }

    // utils
    public function isAdmin() {
      if ($this->employeeInfoGotten) {
        return $this->adminPrivilege;
      } else {
        return 0;
      }
    }

    public function isLoginCorrect($username, $password) {
      global $dbh;
      $stmt = $dbh->prepare('SELECT * 
                             FROM employee 
                             WHERE username = ? AND password = ?');
      $stmt->execute(array($username, sha1($password)));
      return $stmt->fetch() !== false;
    }

    public function returnType() {
      return $this->type;
    }

    public function returnSpecialty() {
      return $this->id_specialty;
    }
    
    public function returnID() {
      return $this->id_employee;
    }

    public function changePassword($pass1,$pass2,$newPass){
      if (sha1($pass1)===sha1($pass2) && sha1($pass1)===$this->password){
        global $dbh;
        $stmt = $dbh->prepare('UPDATE employee 
          SET password = ?
          WHERE id_employee = ?;');
        $stmt->execute(array(sha1($newPass),$this->id_employee));
        return 0;
      } else {
        return -1;
      }
    }

    public function returnMyPatients(){
      global $dbh;
      $stmt = $dbh->prepare('SELECT * 
        FROM patient JOIN employeePatient JOIN person
        WHERE id_employee = ? 
        AND patient.id_patient=id_person
        AND patient.id_patient=employeePatient.id_patient;');
      $stmt->execute(array($this->id_employee));
      $patientsInfo_Arr = $stmt->fetchAll();
      return $patientsInfo_Arr;
    }

    public function getEmployeeInfoFromUsername($username){
      global $dbh;
      $stmt = $dbh->prepare('SELECT * 
        FROM employee JOIN person 
        WHERE username = ? AND id_employee=id_person');
      $stmt->execute(array($username));
      $info_Arr = $stmt->fetchAll();

      // Save info
      // Person info
      $this->id_employee = $info_Arr[0]['id_employee'];
      $this->name = $info_Arr[0]['name'];
      $this->address = $info_Arr[0]['address'];
      $this->phoneNumber = $info_Arr[0]['phoneNumber'];
      $this->gender = $info_Arr[0]['gender'];
      $this->sex = $info_Arr[0]['sex'];
      $this->birthDate = $info_Arr[0]['birthDate'];

      $this->personInfoAdded = True;

      // Employee info
      $this->adminPrivilege = $info_Arr[0]['adminPrivilege'];
      $this->username = $info_Arr[0]['username'];
      $this->password = $info_Arr[0]['password'];
      $this->type = $info_Arr[0]['type'];
      $this->joiningDate = $info_Arr[0]['joiningDate'];
      $this->education = $info_Arr[0]['education'];
      $this->certification = $info_Arr[0]['certification'];
      $this->languages = $info_Arr[0]['languages'];
      $this->id_superior = $info_Arr[0]['id_superior'];
      $this->id_specialty = $info_Arr[0]['id_specialty'];

      $this->employeeInfoGotten = True;
    }

  }

  /**
   * Patient Class
   */
  final class patient extends person
  { 

    private $id_patient;
    private $acceptedDate;
    private $prescriptions;
    private $allergies;
    private $specialReqs;
    private $internedState;
    private $patientInfoGotten; 

    function __construct(){
      $this->patientInfoGotten = False;
      $this->personInfoAdded = False;
    }

    public function addPatientInfo($acceptedDate,$prescriptions,$allergies,$specialReqs,$internedState){
      $patientInfoStrings_Arr = array($acceptedDate,$prescriptions,$allergies,$specialReqs,$internedState);
      $patientVars = array(&$this->acceptedDate,&$this->prescriptions,&$this->allergies,&$this->specialReqs,&$this->internedState);
      for ($i = 1; $i <= sizeof($patientInfoStrings_Arr); $i++) {
        if ($this->checkStringValidity($patientInfoStrings_Arr[$i-1])){ 
          if ($i == 5){
            if ($patientInfoStrings_Arr[$i-1]=='Interned'){
              $patientVars[$i-1] = 1; 
            } elseif ($patientInfoStrings_Arr[$i-1]=='Not Interned') {
              $patientVars[$i-1] = 0; 
            } else {
              return $i*-1;
            }
          } else {
            $patientVars[$i-1] = $patientInfoStrings_Arr[$i-1];
          }
        } else {
          return $i*-1; //-1 for an acceptedDate error, -2 for a prescriptions error, and so on
        }
      }
      $this->patientInfoGotten = True;
      return 0;
    }

    private function insertPatient($lastPerson_idGotten) {
      global $dbh;
      $stmt = $dbh->prepare('INSERT INTO patient (id_patient,acceptedDate,prescriptions,allergies,specialReqs,internedState) 
        VALUES (?,?,?,?,?,?)'); 
      $stmt->execute(array($lastPerson_idGotten,$this->acceptedDate,$this->prescriptions,$this->allergies,$this->specialReqs,$this->internedState)); 
    }
    public function insertIntoDatabase() {
      // get variables
      if ($this->personInfoAdded && $this->patientInfoGotten) {
        $this->insertPerson();
      } else {
        return -1;
      }
      
      // check last person added
      $lastPerson_id = $this->getLastPersonAdded();
      $this->id_patient = $lastPerson_id; 
      // insert Patient
      $this->insertPatient($lastPerson_id);

      return 0;
    }
    public function checkInternedState(){
      return($this->internedState);
    }
  }

  /**
   * Visitor Class
   */
  final class visitor extends person
  {

    private $id_visitor;
    private $visitorInfoGotten; 

    function __construct() {
      $this->visitorInfoGotten = False;
      $this->personInfoAdded = False;
    }

    private function insertVisitor($lastPerson_idGotten) {
      global $dbh;
      $stmt = $dbh->prepare('INSERT INTO visitor (id_visitor) 
        VALUES (?)'); 
      $stmt->execute(array($lastPerson_idGotten)); 
    }

    public function insertIntoDatabase() {
      // get variables
      if ($this->personInfoAdded) {
        $this->insertPerson();
      } else {
        return -1;
      }
      
      // check last person added
      $lastPerson_id = $this->getLastPersonAdded();
      $this->id_visitor = $lastPerson_id;
      // insert Visitor
      $this->insertVisitor($lastPerson_id);
      $this->visitorInfoGotten = True;
      return 0;
    }

    public function getVisitorId(){
      return $this->id_visitor;
    }
    
  }

  final class visit
  {

    private $id_patient;
    private $id_visitor;

    function __construct($id_patient, $id_visitor){
      $this->id_patient = $id_patient;
      $this->id_visitor = $id_visitor;
    }

    public function insertIntoDatabase() {
      global $dbh;
      $stmt = $dbh->prepare('INSERT INTO visit (id_patient,id_visitor) 
        VALUES (?,?)'); 
      $stmt->execute(array($this->id_patient,$this->id_visitor)); 
    }
  }


  // SQL queries related to person
  function returnEmployees(){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM employee JOIN person 
      WHERE id_employee=id_person');
    $stmt->execute();
    $employeeIds_Arr = $stmt->fetchAll();
    return $employeeIds_Arr;
  }

  function returnEmployeesInfoUsingID($id_employee){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM employee JOIN person 
      WHERE id_employee=id_person AND id_employee=?');
    $stmt->execute(array($id_employee));
    $employeeIds_Arr = $stmt->fetchAll();
    return $employeeIds_Arr[0];
  }



  function checkIfUsernameExists($username){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM employee WHERE username=?;');
    $stmt->execute(array($username));
    return $stmt->fetch()!== false;
  }

  function checkIfNotAlphaNumeric($username){
    $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\º\ª\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';

    if (preg_match($pattern,$username)){
      return True;
    } else {
      return False;
    }
  }

  function returnPatients() {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM patient JOIN person
      WHERE id_patient = id_person;');
    $stmt->execute();
    $patientsInfo_Arr = $stmt->fetchAll();
    return $patientsInfo_Arr;
  }

  function returnPatientInfo_usingID($id_patient) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM patient JOIN person
      WHERE id_patient = id_person AND id_patient=?;');
    $stmt->execute(array($id_patient));
    $patientsInfo_Arr = $stmt->fetchAll();
    return $patientsInfo_Arr[0];
  }

  function pairPatientEmployee($id_patient,$id_employee){
    global $dbh;
    $stmt = $dbh->prepare('INSERT INTO employeePatient (id_employee,id_patient) 
      VALUES (?,?);');
    $stmt->execute(array($id_employee,$id_patient));
  }

  function checkIfPairAlreadyExists($id_patient,$id_employee){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM employeePatient WHERE id_employee=? AND id_patient=?;');
    $stmt->execute(array($id_employee,$id_patient));
    return $stmt->fetch()!== false;
  }

  function returnVisitors() {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM visitor JOIN person
      WHERE id_visitor = id_person;');
    $stmt->execute();
    $visitorsInfo_Arr = $stmt->fetchAll();
    return $visitorsInfo_Arr;
  }

  function returnVisitorInfo_usingID($id_visitor) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * 
      FROM visitor JOIN person
      WHERE id_visitor = id_person AND id_visitor=?;');
    $stmt->execute(array($id_visitor));
    $visitorInfo_Arr = $stmt->fetchAll();
    return $visitorInfo_Arr[0];
  }

  function checkIfVisistPairExists($id_patient,$id_visitor){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM visit WHERE id_patient=? AND id_visitor=?;');
    $stmt->execute(array($id_patient,$id_visitor));
    return $stmt->fetch()!== false;
  }

  function returnVisits($id_patient){
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM visit WHERE id_patient=?;');
    $stmt->execute(array($id_patient));
    $activeVisits = $stmt->fetchAll();
    return $activeVisits;
  }

  function returnSpecialties(){
    global $dbh;
    $stmt = $dbh->prepare('SELECT id_specialty,name FROM specialty;');
    $stmt->execute();
    $specialtyInfo_Arr = $stmt->fetchAll();
    return $specialtyInfo_Arr;
  }

?>