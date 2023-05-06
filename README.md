# Crimes-api 👮‍♂️

- [Crimes-api 👮‍♂️](#crimes-api-️)
  - [Project Overview 📃](#project-overview-)
  - [Api Version ✨](#api-version-)
  - [Project Features 🏆](#project-features-)
  - [Resources ⛑](#resources-)
  - [URI Relationship 👫](#uri-relationship-)
  - [composite resource 🎑](#composite-resource-)
  - [Authentication / Token 🔑](#authentication--token-)
  - [Rate Limit](#rate-limit)
  - [Versioning](#versioning)
  - [Base Uri 🕶](#base-uri-)
  - [Pagination](#pagination)
- [Resources](#resources)
  - [Root](#root)
  - [cases](#cases)
    - [GET /Cases](#get-cases)
    - [GET/cases/{id}](#getcasesid)
    - [GET/cases/{id}/victims](#getcasesidvictims)
    - [GET/cases/{id}/offenders](#getcasesidoffenders)
    - [POST /cases](#post-cases)
    - [PUT /cases](#put-cases)
    - [DELETE /cases](#delete-cases)
  - [offenses](#offenses)
    - [GET /offenses](#get-offenses)
    - [GET/offenses/{id}](#getoffensesid)
    - [POST /offenses](#post-offenses)
      - [Condition](#condition)
    - [PUT /offenses](#put-offenses)
      - [Condition](#condition-1)
    - [DELETE /offenses](#delete-offenses)
  - [investigators](#investigators)
    - [GET /investigators](#get-investigators)
    - [GET/investigator/{id}](#getinvestigatorid)
    - [POST /investigators](#post-investigators)
      - [Condition](#condition-2)
    - [PUT /investigator](#put-investigator)
      - [Condition](#condition-3)
    - [DELETE /offenses](#delete-offenses-1)
  - [Victims](#victims)
    - [GET /victims](#get-victims)
    - [GET/victims/{id}](#getvictimsid)
    - [POST /victims](#post-victims)
      - [Condition](#condition-4)
    - [PUT /victims](#put-victims)
      - [Condition](#condition-5)
    - [DELETE /victims](#delete-victims)
  - [judges](#judges)
    - [GET /judges](#get-judges)
    - [GET/judges/{id}](#getjudgesid)
    - [POST /judges](#post-judges)
      - [Condition](#condition-6)
    - [PUT /judges](#put-judges)
      - [Condition](#condition-7)
    - [DELETE /judges](#delete-judges)
  - [crime scenes](#crime-scenes)
    - [GET /crime\_scenes](#get-crime_scenes)
    - [GET/crime\_scenes/{id}](#getcrime_scenesid)
    - [POST /crime\_scenes](#post-crime_scenes)
      - [Condition](#condition-8)
    - [PUT /crime\_scenes](#put-crime_scenes)
      - [Condition](#condition-9)
    - [DELETE /crime\_scenes](#delete-crime_scenes)
- [Teams ⚔](#teams-)
- [Teacher 🎓](#teacher-)


## Project Overview 📃
- Our project, developed for the Web Services course in computer science, is aimed at creating a professional-grade RESTful API that will provide extensive information about crimes. The API will consist of several resources covering various aspects of crimes, including cases, offenders, victims, defendants, prosecutors, investigators, courts, verdicts, and judges.

## Api Version ✨
- V1 and v2

## Project Features 🏆
- Error Handling
- Inputs & Data Validations
- Filtering, Pagination and sorting on exposed collection resources
- Content Negotiation handling
- Composite Resource
- Root Resource
- Logging
- Identity Management
- Versioning
- Caching
- Computation functionality / Remote processing

## Resources ⛑
- /cases
- /offenders
- /victims
- /defendants
- /prosecutors
- /investigators
- /courts
- /verdicts
- /judges

## URI Relationship 👫
- /cases/{case_id}/crime_scenes
- /cases/{case_id}/{offense_id}
- /cases/{case_id}/{court_id}
- /cases/{case_id}/{offender_id}
- /cases/{case_id}/{investigator_id}
- /cases/{case_id}/{victim_id}
- /victims/{victim_id}/{prosecutor_id}
- /victims/{victim_id}/{case_id}
- /offenders/{offender_id}/{defender_id}
- /offenders/{offender_id}/{case_id}
- /courts/{court_id}/{address_id}
- /courts/{court_id}/{judge_id}
- /courts/{court_id}/{verdict_id}

## composite resource 🎑
- /wanter-api
- /news-api

## Authentication / Token 🔑
- coming soon

## Rate Limit
- coming soon

## Versioning
- Clients can choose which version of the crimes-api they want to consume.
- coming soon

## Base Uri 🕶
- To access the resources, you need to set up the base URI
- /crimes-api

## Pagination
- You can pass a pagination filters to all the supported resources
- `?page=x` for the current page
- `?pageSize=z` refers to the size of the page

# Resources
## Root
- contains information of the crimes-api
- example request
  - GET /crimes-api/
## cases
### GET /Cases
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
| description  | returns any resource(s) that matches the given value   | description: On 21st   | NA
|misdemeanor   |returns any resource(s) that matches the given value. 1 for true and 0 for false   | misdemeanor: 0  | 0 = false, 1 = true |
|date_from and  date_to   |returns any resource(s) in between two date| date_from : 2022-04-01 date_to : 2023-03-17| it has to be yyy-mm-dd format|
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : case_id.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

### GET/cases/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |

### GET/cases/{id}/victims
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |

### GET/cases/{id}/offenders
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |

### POST /cases
- to create a new case, follow this body structure
```json
[
    {
        "description" : "On 21st …",
        "misdemeanor" : 0,
        "crime_sceneID" : 1,
        "investigator_id" : 1,
        "court_id" : 1,
        "offense_id" : [1,2,3],
        "victim_id" : [1,2,3],
        "offender_id" : [1,2,3]
    }
]
```
- You can also create multiple cases
```json
[
    {
        "description" : "On 21st …",
        "misdemeanor" : 0,
        "crime_sceneID" : 1,
        "investigator_id" : 1,
        "court_id" : 1,
        "offense_id" : [1,2,3],
        "victim_id" : [1,2,3],
        "offender_id" : [1,2,3]
    }
     {
        "description" : "On 1st …",
        "misdemeanor" : 0,
        "crime_sceneID" : 1,
        "investigator_id" : 1,
        "court_id" : 1,
        "offense_id" : [1],
        "victim_id" : [2],
        "offender_id" : [2,3]
    }
]

```
### PUT /cases
- To update an existing case, follow this structure
```json
[
    {
        "case_id": 1,
        "description" : "On 21st …",
        "date_reported" : "2023-02-14 22:08:49",
        "misdemeanor" : 0,
        "crime_sceneID" : 1,
        "investigator_id" : 1,
        "court_id" : 1,
        "offense_id" : [1,2,3],
        "victim_id" : [1,2,3],
        "offender_id" : [1,2,3]
    }
]

```
- You can also update multiple cases
```json
[
    {
        "case_id": 1,
        "description" : "On 21st …",
        "date_reported" : "2023-02-14 22:08:49",
        "misdemeanor" : 0,
        "crime_sceneID" : 1,
        "investigator_id" : 1,
        "court_id" : 1,
        "offense_id" : [1,2,3],
        "victim_id" : [1,2,3],
        "offender_id" : [1,2,3]
    }
     {
        "case_id": 2,
        "description" : "On 1st …",
        "date_reported" : "2023-02-14 22:08:49",
        "misdemeanor" : 0,
        "crime_sceneID" : 1,
        "investigator_id" : 1,
        "court_id" : 1,
        "offense_id" : [1,2,3],
        "victim_id" : [1,2,3],
        "offender_id" : [1,2,3]
    }
]
```

### DELETE /cases
- To delete a case(s)
```json
{
    "case_id" : [1,2]
}
```
## offenses
### GET /offenses
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
| description  | returns any resource(s) that matches the given value| description: Hacker stole 2m...   | NA
|name   |returns any resource(s) that matches the given value | name: Arson | NA |
|classification|returns any resource(s) that matches the given value| classification : Cyber-crime| NA |
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : offense_id.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

### GET/offenses/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |


### POST /offenses
- to create a new offense, follow this body structure
```json
[
    {
        "name": "Test post offense",
        "description" : "adding new offense",
        "classification": "Cyber-crime"
    }
]

```
- You can also create multiple offenses
```json
[
    {
        "name": "Test post offense 1",
        "description" : "adding new offense",
        "classification": "Cyber-crime"
    },
     {
        "name": "Test post offense 2",
        "description" : "adding new offense",
        "classification": "Cyber-crime"
    }
]

```
#### Condition
- for classification, you can choose from Felony, Misdemeanor, White-collar crime, Violent crime, Property crime, Drug crime, Cyber-crime
- If you try to add different classificatory, you'll be greeted by a http error
- 
### PUT /offenses
- To update an existing offense, follow this structure
```json
[
    {
        "offense_id" : 1,
        "name": "Test post offense",
        "description" : "adding new offense",
        "classification": "Cyber-crime"
    }
]

```
- You can also update multiple offenses
```json
[
    {
        "offense_id" : 1,
        "name": "Test post offense 1",
        "description" : "adding new offense",
        "classification": "Cyber-crime"
    },
     {
        "offense_id" : 2,
        "name": "Test post offense 2",
        "description" : "adding new offense",
        "classification": "Cyber-crime"
    }
]
```
#### Condition
- for classification, you can choose from Felony, Misdemeanor, White-collar crime, Violent crime, Property crime, Drug crime, Cyber-crime
- If you try to add different classificatory, you'll be greeted by a http error

### DELETE /offenses
- To delete a offense(s)
```json
{
    "offense" : [1,2]
}
```
## investigators
### GET /investigators
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
| first_name  | returns any resource(s) that matches the given value| first_name: John   | NA
|last_name   |returns any resource(s) that matches the given value | last_name: Doe | NA |
|badge_number|returns any resource(s) that matches the given value| badge_number : 1234| NA |
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : investigator_id.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

### GET/investigator/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |


### POST /investigators
- to create a new investigator, follow this body structure
```json
[
    {
        "badge_number": "2611",
        "first_name": "Mark",
        "last_name": "Marcus",
        "rank": "Certified Legal Investigator"
    }
]

```
- You can also create multiple investigators
```json
[
    {
        "badge_number": "2611",
        "first_name": "Mark",
        "last_name": "Marcus",
        "rank": "Certified Legal Investigator"
    }
    {
        "badge_number": "3111",
        "first_name": "John",
        "last_name": "Doe",
        "rank": "Certified Legal Investigator"
    }
]

```
#### Condition
- badge_number has to be unique, it will throw an exception if the badge_number already exists in the database
- for rank, you can choose from Certified Legal Investigator, Board Certified Investigator, Certified Forensic Investigator, Certified Fraud Examiner
- If you try to add different rank, you'll be greeted by a http error
  
### PUT /investigator
- To update an existing investigator, follow this structure
```json
[
    {
        "investigator_id" : 1,
        "badge_number": "2611",
        "first_name": "Mark",
        "last_name": "Marcus",
        "rank": "Certified Legal Investigator"
    }
]
```
- You can also update multiple investigators
```json
[
    {
        "investigator_id" : 1,
        "badge_number": "2611",
        "first_name": "Mark",
        "last_name": "Marcus",
        "rank": "Certified Legal Investigator"
    },
       {
        "investigator_id" : 2,
        "badge_number": "27611",
        "first_name": "John",
        "last_name": "Marcus Ark",
        "rank": "Certified Legal Investigator"
    }
]
```
#### Condition
- badge_number has to be unique, it will throw an exception if the badge_number already exists in the database
- for rank, you can choose from Certified Legal Investigator, Board Certified Investigator, Certified Forensic Investigator, Certified Fraud Examiner
- If you try to add different rank, you'll be greeted by a http error

### DELETE /offenses
- To delete a offense(s)
```json
{
    "offense" : [1,2]
}
```
## Victims
### GET /victims
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|last_name   |returns any resource(s) that matches the given value | last_name: Doe | NA |
|marital_status|returns any resource(s) that matches the given value| marital_status : single| NA |
|age|returns any resource(s) that matches the given value| age : 20| only numeric value |
|victim_id|returns any resource(s) that matches the given value| victim_id : 12| only numeric value |
|prosecutor_id|returns any resource(s) that matches the given value| prosecutor_id : 2| only numeric value |
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : victim.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

### GET/victims/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |


### POST /victims
- to create a new victim, follow this body structure
```json
{
    "first_name": "Alice",
    "last_name": "Jones",
    "age": 32,
    "marital_status": "single",
    "prosecutor_id": 1
} 
```
#### Condition
- for marital status, you can choose from single, married, divorce
- If you try to add different rank, you'll be greeted by a http error
  
### PUT /victims
- To update an existing victim, follow this structure
```json
[
    {
        "victim_id" : 1,
        "first_name": "Alice",
        "last_name": "Jones",
        "age": 32,
        "marital_status": "single",
        "prosecutor_id": 1
    }
]
```
- You can also update multiple victims
```json
[
    {
        "victim_id" : 1,
        "first_name": "Alice",
        "last_name": "Jones",
        "age": 32,
        "marital_status": "single",
        "prosecutor_id": 1
    },
       {
        "victim_id" : 2,
        "first_name": "Drake",
        "last_name": "Jones",
        "age": 32,
        "marital_status": "single",
        "prosecutor_id": 1
    }
]
```
#### Condition
- for marital status, you can choose from single, married, divorce
- If you try to add different rank, you'll be greeted by a http error

### DELETE /victims
- To delete a victims(s)
```json
{
    "victim_id" : [1,2]
}
```
## judges
### GET /judges
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|last_name   |returns any resource(s) that matches the given value | last_name: Doe | NA |
|age|returns any resource(s) that matches the given value| age : 20| only numeric value |
|judge_id|returns any resource(s) that matches the given value| victim_id : 12| only numeric value |
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : judge_id.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

### GET/judges/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |


### POST /judges
- to create a new judge, follow this body structure
```json
[    
    {
      "first_name": "bob6",
      "last_name": "test6",
      "age": 50
    }
]

```

- To create multiple judges
```json
[    
    {
      "first_name": "bob6",
      "last_name": "test6",
      "age": 50
    },
    {
      "first_name": "bob8",
      "last_name": "test10",
      "age": 55
    }
]

```
#### Condition
- NA
  
### PUT /judges
- To update an existing victim, follow this structure
```json
[    
    {
        "judge_id" : 1,
        "first_name": "bob6",
        "last_name": "test6",
        "age": 50
    }
]
```
- You can also update multiple victims
```json
[    
    {
        "judge_id" : 1,
        "first_name": "bob6",
        "last_name": "test6",
        "age": 50
    },
     {
        "judge_id" : 2,
        "first_name": "bob6",
        "last_name": "test6",
        "age": 50
    }
]
```
#### Condition
- NA

### DELETE /judges
- To delete judge(s)
```json
{
    "judge_id" : [1,2]
}
```
## crime scenes
### GET /crime_scenes
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|street|returns any resource(s) that matches the given value | last_name: Doe | NA |
|city|returns any resource(s) that matches the given value| age : 20| only numeric value |
|crime_sceneID|returns any resource(s) that matches the given value| crime_sceneID : 12| only numeric value |
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : crime_sceneID.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

### GET/crime_scenes/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |


### POST /crime_scenes
- to create a new judge, follow this body structure
```json
{
    "province": "Washington",
    "city": "Washington D.C",
    "street": "Pennsylvania Avenue NW",
    "building_number": "1600"
}

```
#### Condition
- NA
  
### PUT /crime_scenes
- To update an existing crime scene, follow this structure
```json
[    
    {
        "crime_sceneID" : 1,
        "province": "Washington",
        "city": "Washington D.C",
        "street": "Pennsylvania Avenue NW",
        "building_number": "1600"
    }
]
```
- You can also update multiple crime scenes
```json
[    
    {
        "crime_sceneID" : 1,
        "province": "Washington",
        "city": "Washington D.C",
        "street": "Pennsylvania Avenue NW",
        "building_number": "1600"
    },
    {
        "crime_sceneID" : 2,
        "province": "Ontario",
        "city": "Toronto",
        "street": "Ottawa Street",
        "building_number": "2233"
    }
]
```
#### Condition
- NA

### DELETE /crime_scenes
- To delete crime scene(s)
```json
{
    "crime_sceneID" : [1,2]
}
```
# Teams ⚔
- Jeffrey Grospe (Team Leader)
- Md Saqliyan Islam
- Alex Nguyen
- Theodore Tsimiklis


# Teacher 🎓
- SLEIMAN RABAH (Vanier College Teacher)

