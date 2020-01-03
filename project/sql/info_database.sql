-- ESIN 2019/2020

/*DROP TABLE IF EXISTS users;
CREATE TABLE users (
  username VARCHAR PRIMARY KEY,
  password VARCHAR
    
);*/

-- People
DROP TABLE IF EXISTS person;
CREATE TABLE person (
  id_person INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR,
  address VARCHAR,
  phoneNumber INTEGER,
  gender VARCHAR,
  sex VARCHAR,
  birthDate VARCHAR
);

DROP TABLE IF EXISTS patient; 
CREATE TABLE patient (
  id_patient INTEGER REFERENCES person,
  acceptedDate VARCHAR, -- date of registry
  prescriptions VARCHAR, -- may be NULL
  allergies VARCHAR, -- may be NULL
  specialReqs VARCHAR, -- "No benzodiazepines;" for ex.
  internedState BOOLEAN -- if patient is interned or not    
);

DROP TABLE IF EXISTS employee;
CREATE TABLE employee (
  id_employee INTEGER REFERENCES person,
  adminPrivilege BOOL NOT NULL,
  username VARCHAR NOT NULL UNIQUE,
  password VARCHAR NOT NULL,
  type VARCHAR,
  joiningDate VARCHAR, -- date employee started working
  education VARCHAR, -- alma mater
  certification VARCHAR, -- degree 
  languages VARCHAR, -- "english,french,portuguese." for ex.
  id_superior INTEGER REFERENCES employee, --employee(id_employee), -- id of superior
  id_specialty INTEGER REFERENCES specialty
);

DROP TABLE IF EXISTS visitor;
CREATE TABLE visitor (
  id_visitor INTEGER REFERENCES person
);

DROP TABLE IF EXISTS employeePatient;
CREATE TABLE employeePatient (
  id_employee INTEGER REFERENCES employee,
  id_patient INTEGER REFERENCES patient
);

-- Records - medical info
DROP TABLE IF EXISTS clinicalRecord;
CREATE TABLE clinicalRecord (
  id_cr INTEGER PRIMARY KEY AUTOINCREMENT,
  id_specialty INTEGER REFERENCES specialty, -- "[Specialty] Exam"
  dateExam VARCHAR,
  id_hc INTEGER REFERENCES healthCentre,
  id_patient INTEGER REFERENCES patient NOT NULL,
  path_cr VARCHAR
);

-- Remove?
DROP TABLE IF EXISTS specialty;
CREATE TABLE specialty (
  id_specialty INTEGER PRIMARY KEY,
  name VARCHAR
);

-- Physical spaces
DROP TABLE IF EXISTS room;
CREATE TABLE room (
  id_room INTEGER PRIMARY KEY,
  name VARCHAR,
  id_department INTEGER REFERENCES department
);

DROP TABLE IF EXISTS department;
CREATE TABLE department (
  id_department INTEGER PRIMARY KEY,
  name VARCHAR
);

DROP TABLE IF EXISTS healthCentre;
CREATE TABLE healthCentre (
  id_hc INTEGER PRIMARY KEY AUTOINCREMENT,
  address VARCHAR,
  name VARCHAR
);

-- ACLs
DROP TABLE IF EXISTS ACLMedicalRec;
CREATE TABLE ACLMedicalRec (
  id_aclMR INTEGER PRIMARY KEY AUTOINCREMENT,
  id_cr INTEGER REFERENCES clinicalRecord,
  id_employee_requester INTEGER REFERENCES employee,
  id_employee_technician INTEGER REFERENCES employee, 
  completed BOOLEAN NOT NULL
);

DROP TABLE IF EXISTS ACLRooms;
CREATE TABLE ACLRooms (
  id_aclRS INTEGER PRIMARY KEY AUTOINCREMENT,
  id_room INTEGER REFERENCES room,
  id_visitor INTEGER REFERENCES visitor,
  id_employee INTEGER REFERENCES employee,
  startDate VARCHAR NOT NULL,
  endDate VARCHAR NOT NULL
);

-- Relational tables
DROP TABLE IF EXISTS visit; -- only allowed if patient_interned = true (php side)
CREATE TABLE visit (
  id_visit INTEGER PRIMARY KEY AUTOINCREMENT,
  id_patient INTEGER REFERENCES patient,
  id_visitor INTEGER REFERENCES visitor  
);

DROP TABLE IF EXISTS memberShip;
CREATE TABLE memberShip (
  memberNumber INTEGER PRIMARY KEY,
  id_person INTEGER REFERENCES person UNIQUE,
  id_hc INTEGER REFERENCES healthCentre UNIQUE
);

-- Exam Requests
DROP TABLE IF EXISTS examRequests;
CREATE TABLE examRequests (
  id_examRequests INTEGER PRIMARY KEY AUTOINCREMENT,
  status VARCHAR NOT NULL,
  id_specialty INTEGER NOT NULL,
  id_cr INTEGER REFERENCES clinicalRecord NOT NULL
);

DROP TABLE IF EXISTS examSchedule;
CREATE TABLE examSchedule (
  id_examSchedule INTEGER PRIMARY KEY AUTOINCREMENT,
  id_employee_technician INTEGER REFERENCES employee,
  dateExam VARCHAR NOT NULL,
  uploadStatus BOOLEAN NOT NULL,
  id_aclMR INTEGER REFERENCES ACLMedicalRec
);

-- Admin (HR)
INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('João Santos','Porto',910000001,'M','M','1984-12-25');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (1,1,'Admin','d033e22ae348aeb5660fc2140aec35850c4da997','HR','2012-03-22','feup','BSc','english',NULL,1);


-- Doctor 
INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Alberto Reis','Vila Real',910000002,'M','M','1985-08-03');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (2,0,'testDoctor1','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','doctor','2012-03-22','ICBAS','MSc','Portuguese',NULL,1);

INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Ana Gonçalves','Coimbra',910000003,'F','F','1989-11-28');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (3,0,'testDoctor2','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','doctor','2013-12-11','FMUP','MSc','Portuguese',1,2);

INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Alberto Sá','Guarda',910000004,'F','F','1972-04-12');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (4,0,'testDoctor3','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','doctor','2014-09-29','ICBAS','MSc','Portuguese,French',2,3);


-- Technician
INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Fernando Damasco','Guimarães',910000005,'M','M','1978-03-21');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (5,0,'testTech1','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','technician','2012-03-22','ESS','BSc','Portuguese,Spanish',2,1);

INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Aníbal Saúde','Setúbal',910000006,'F','F','1974-04-25');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (6,0,'testTech2','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','technician','2017-05-24','ESS','MSc','Portuguese',NULL,2);

INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Otelo Carvalho','Chaves',910000007,'M','M','1986-10-19');
INSERT INTO employee (id_employee,adminPrivilege,username,password,type,joiningDate,education,certification,languages,id_superior,id_specialty) VALUES (7,0,'testTech3','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','technician','2018-10-28','ESS','PhD','Portuguese,English',1,3);


-- Patient
INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Daniel Ferraz','Algarve',910000008,'M','M','1995-02-26');
INSERT INTO patient (id_patient,acceptedDate,prescriptions,allergies,specialReqs,internedState) VALUES (8,'2019-02-26','Benuron','Peanuts','High [O2] Needed',1);

INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Ana Fonseca','Coimbra',910000009,'F','F','1985-07-22');
INSERT INTO patient (id_patient,acceptedDate,prescriptions,allergies,specialReqs,internedState) VALUES (9,'2019-02-26','Hydrocodone','Dog hair','Constant supervision',1);

INSERT INTO person (name,address,phoneNumber,gender,sex,birthDate) VALUES ('Almeida Garret','Porto',910000010,'M','M','1956-11-17');
INSERT INTO patient (id_patient,acceptedDate,prescriptions,allergies,specialReqs,internedState) VALUES (10,'2019-02-26','Norvasc','Soy beans','Avoid Loud Noises',1);


-- Health Centres
INSERT INTO healthCentre (address,name) VALUES ('Porto','ICBAS');
INSERT INTO healthCentre (address,name) VALUES ('Coimbra','HUC');
INSERT INTO healthCentre (address,name) VALUES ('Porto','FMUP');


-- Already Established Employee-Patient relationships
INSERT INTO employeePatient (id_employee,id_patient) VALUES (2,8);
INSERT INTO employeePatient (id_employee,id_patient) VALUES (3,9);


-- Specialties
INSERT INTO specialty (id_specialty,name) VALUES (1,'Cardiology');
INSERT INTO specialty (id_specialty,name) VALUES (2,'Ophthalmology');
INSERT INTO specialty (id_specialty,name) VALUES (3,'Endocrinology');



