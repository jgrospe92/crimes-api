# Crimes-api 👮‍♂️

- [Crimes-api 👮‍♂️](#crimes-api-️)
  - [Project Overview 📃](#project-overview-)
  - [Api Version ✨](#api-version-)
  - [Project Features 🏆](#project-features-)
  - [Resources ⛑](#resources-)
  - [URI Relationship 👫](#uri-relationship-)
  - [composite resource 🎑](#composite-resource-)
  - [Authentication / Token 👛](#authentication--token-)
  - [Rate Limit](#rate-limit)
  - [Versioning](#versioning)
  - [Base Uri 🕶](#base-uri-)
  - [Pagination](#pagination)
- [Resources](#resources)
  - [Root](#root)
  - [GET /Cases](#get-cases)
  - [GET/cases/{id}](#getcasesid)
  - [GET/cases/{id}/victims](#getcasesidvictims)
  - [GET/cases/{id}/offenders](#getcasesidoffenders)
  - [POST /cases](#post-cases)
  - [PUT /cases](#put-cases)
  - [DELETE /cases](#delete-cases)
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

## Authentication / Token 👛
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
- You pass it pagination filters to all the supported resources
- `?page=x` for the current page
- `?pageSize=z` refers to the size of the page

# Resources
## Root
- contains information of the crimes-api
- example request
  - GET /crimes-api/

## GET /Cases
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
| description  | returns any resource(s) that matches the given value   | description: On 21st   | NA
|misdemeanor   |returns any resource(s) that matches the given value. 1 for true and 0 for false   | misdemeanor: 0  | 0 = false, 1 = true |
|date_from and  date_to   |returns any resource(s) in between two date| date_from : 2022-04-01 date_to : 2023-03-17| it has to be yyy-mm-dd format|
|sort_by|returns resources in ascending or descending order based on the parameter value|sort_by : case_id.asc| asc = ascending, desc = descending|
|page|returns the specified page|page : 1| default is 1|
|pageSize|limit the number of items being displayed base on the value|pageSize : 1| default is 10|

## GET/cases/{id}
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |

## GET/cases/{id}/victims
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |

## GET/cases/{id}/offenders
|**Parameter**   |**Description**   |**Example**| **Condition** |
|---|---|---|--|
|NA|NA   |NA   |NA   |

## POST /cases
- to create a new case, follow this body structure
```json
[
    {
    “description” : “On 21st …”,
    “misdemeanor” : 0,
    “crime_sceneID” : 1,
    “investigator_id : 1,
    “court_id” : 1,
    “offense_id” : [1,2,3],
    “victim_id” : [1,2,3],
    “offender_id” : [1,2,3]
    }
]

```
- You can also create multiple cases
```json
[
    {
    “description” : “On 21st …”,
    “misdemeanor” : 0,
    “crime_sceneID” : 1,
    “investigator_id : 1,
    “court_id” : 1,
    “offense_id” : [1,2,3],
    “victim_id” : [1,2,3],
    “offender_id” : [1,2,3]
    },
    {
    “description” : “On 1st …”,
    “misdemeanor” : 1,
    “crime_sceneID” : 1,
    “investigator_id : 1,
    “court_id” : 1,
    “offense_id” : [1],
    “victim_id” : [2],
    “offender_id” : [3]
    }
]

```
## PUT /cases
- To update an existing case, follow this structure
```json
[
    {
    “case_id”: 1,
    “description” : “On 21st …”,
    “date_reported” : “2023-02-14 22:08:49”,
    “misdemeanor” : 0,
    “crime_sceneID” : 1,
    “investigator_id : 1,
    “court_id” : 1,
    “offense_id” : [1,2,3],
    “victim_id” : [1,2,3],
    “offender_id” : [1,2,3]
    }
]

```
- You can also create multiple cases
```json
[
    {
    “case_id”: 1,
    “description” : “On 21st …”,
    “date_reported” : “2023-02-14 22:08:49”,
    “misdemeanor” : 0,
    “crime_sceneID” : 1,
    “investigator_id : 1,
    “court_id” : 1,
    “offense_id” : [1,2,3],
    “victim_id” : [1,2,3],
    “offender_id” : [1,2,3]
    },
    {
    “case_id”: 2,
    “description” : “On 1st …”,
    “misdemeanor” : 1,
    “crime_sceneID” : 1,
    “investigator_id : 1,
    “court_id” : 1,
    “offense_id” : [1],
    “victim_id” : [2],
    “offender_id” : [3]
    }
]
```

## DELETE /cases
- To delete a case(s)
```json
{
    "case_id" : [1,2]
}
```
# Teams ⚔
- Jeffrey Grospe
- Md Saqliyan Islam
- Alex Nguyen
- Theodore Tsimiklis


# Teacher 🎓
- SLEIMAN RABAH (Vanier College Teacher)

