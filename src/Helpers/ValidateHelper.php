<?php

namespace Vanier\Api\Helpers;

// NOTE: path is not tested
require_once("Validator.php");



/**
 * Summary of ValidateHelper
 * Modify this to match crimes-api
 */
class ValidateHelper
{

    /**
     * Summary of validatePagingParams
     * @param array $dataParams
     * @return bool
     */
    public static function validatePagingParams(array $dataParams)
    {

        // The array containing the data to be validated.
        $data = array(
            "page" => $dataParams['page'],
            "page_size" => $dataParams['pageSize'],
        );
        // An array element can be associated with one or more validation rules. 
        // Validation rules must be wrapped in an associative array where:
        // NOTE: 
        //     key => must be an existing key  in the data array to be validated. 
        //     value => array of one or more rules.    
        $rules = array(
            'page' => [
                'required',
                'numeric',
                ['min', $dataParams['pageMin']]
            ],
            'page_size' => [
                'required',
                'integer',
                ['min', $dataParams['pageSizeMin']],
                ['max', $dataParams['pageSizeMax']]
            ]
        );

        // Create a validator and override the default language used in expressing the error messages.
        $validator = new Validator($data, [], 'en');
        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Summary of validateInputId
     * @param mixed $id
     * @return mixed
     */
    public static function validateInputId(array $dataParams)
    {
        return filter_var($dataParams['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => $dataParams['min'], 'max_range' => $dataParams['max']]]);
    }

    /**
     * Summary of validateDateInput
     * @param array $date
     * @return bool
     */
    public static function validateDateInput(array $date)
    {
        $data = array('from_rentalDate' => $date['from_rentalDate'], 'to_rentalDate' => $date['to_rentalDate']);

        $rules = array(
            'from_rentalDate' => [['dateFormat', 'Y-m-d']],
            'to_rentalDate' => [['dateFormat', 'Y-m-d']]
        );
        $validator = new Validator($data, [], 'en');
        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Summary of validateDateFormat
     * @param mixed $date
     * @return bool
     */
    public static function validateDateFormat($date)
    {

        $data = array("date_data" => $date);
        $currentDate = date("Y-m-d");
        $rules = array(
            'dateBefore' => [['date_data', $currentDate]],
        );
        $validator = new Validator($data, [], 'en');
        // Important: map the validation rules before calling validate()
        $validator->rules($rules);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Summary of validateTimeStamp
     * @param mixed $time
     * @return bool
     */
    public static function validateTimeStamp($time)
    {
        $data = ['time_stamp' => $time];
        $rules = 
        ['regex' => 
            [
                ['time_stamp', '/(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)/']
            ]
        ];
        $validator = new Validator($data, [], 'en');
        // Important: map the validation rules before calling validate()
        $validator->rules($rules);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    function testValidatePersonInfo()
    {
        // The array containing the data to be validated.
        $data = array(
            "fist_name" => "Ladybug",
            "last_name" => "Bumblebee",
            "age" =>  '9',
            "price" =>  '389.53',
            "oi" =>  '5',
            "dob" =>  '1-2022-05',
        );
        // An array element can be associated with one or more validation rules. 
        // Validation rules must be wrapped in an associative array where:
        // key => must be an existing key  in the data array to be validated. 
        // value => array of one or more rules.    
        $rules = array(
            'fist_name' => array(
                'required',
                array('lengthMin', 4)
            ),
            'last_name' => array(
                'required',
                array('lengthBetween', 1, 4)
            ),
            'age' => [
                'required',
                'integer',
                ['min', 18]
            ],
            'dob' => [
                'required',
                ['dateFormat', 'Y-m-d']
            ],
            'oi' => [
                'required',
                ['equals', 'Oye']
            ]
        );

        $validator = new Validator($data);
        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);
        if ($validator->validate()) {
            echo "<br> Valid data!";
        } else {
            //var_dump($validator->errors());
            //print_r($validator->errors());
            echo $validator->errorsToString();
            echo $validator->errorsToJson();
        }
    }


    /**
     * Summary of validateNumericInput
     * @param array $data
     * @return bool
     */
    public static function validateNumericInput(array $data)
    {
        // Validate a single value.
        // The value must be passed as an array. 
        $key = array_key_first($data);
        $value = $data[$key];

        $validator = new Validator($data);
        $validator->rule('numeric', $key);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Summary of isUnique
     * @param array $data
     * @return bool
     */
    public static function isUnique(array $data)
    {
        $rules =
            [

                'containsUnique' =>
                [
                    ['id']
                ],
            ];

        $validator = new Validator($data);
        $validator->rules($rules);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    public static function validateId(array $data)
    {
        $rules = [
            'min' =>
            [
                ['id', 1]
            ]
        ];

        $validator = new Validator($data);
        $validator->rules($rules);
        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Summary of validatePutCustomer
     * @param array $data
     * @return bool
     */
    public static function validatePutCustomer(array $data)
    {
        $customer_id = $data['customer_id'] ?? '';
        $store_id = $data['store_id'] ?? '';
        $first_name = $data['first_name'] ?? '';
        $last_name = $data['last_name'] ?? '';
        $email = $data['email'] ?? '';
        $address_id = $data['address_id'] ?? '';
        $active = $data['active'] ?? '';
        $create_date  = $data['create_date'] ?? '';

        // $data = array(
        //     'customer_id' =>1,
        //     'store_id' => 2,
        //     'first_name' => "",
        //     'last_name' => "Grisoe",
        //     'email' => "jeffrey@gmail.com",
        //     'address_id' => 1,
        //     'active' => 0,
        //     'create_date' => date('Y-m-d H:i:s')
        // );


        $customer_object = array(
            'customer_id' => $customer_id,
            'store_id' => $store_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'address_id' => $address_id,
            'active' => $active,
            'create_date' => $create_date
        );


        $rules =
            [
                'min' =>
                [
                    ['customer_id', 1],
                    ['active', 0],
                    ['store_id', 1],
                    ['address_id', 1]

                ],
                'max' =>
                [
                    ['active', 1],
                    ['store_id', 2],
                ],
                'email' =>
                [
                    ['email']
                ],
                'required' =>
                [
                    ['customer_id'],
                    ['store_id'],
                    ['first_name'],
                    ['last_name'],
                    ['address_id'],
                ]

            ];

        // Change the default language to French.
        //$validator = new Validator($data, [], "fr");
        $validator = new Validator($customer_object);
        $validator->rules($rules);

        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Summary of validatePutFilms
     * @param array $data
     * @return bool
     * validates update resource field
     */
    public static function validatePutFilms(array $data)
    {

        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $release_year = $data['release_year'] ?? '';
        $language_id = $data['language_id'] ?? '';
        $original_language_id = $data['original_language_id'] ?? '';
        $rental_duration = $data['rental_duration'] ?? 3;
        $rental_rate = $data['rental_rate'] ?? 4.99;
        $length = $data['length'] ?? '';
        $replacement_cost = $data['replacement_cost'] ?? 10.99;
        $rating = $data['rating'] ?? 'G';
        $special_features = $data['special_features'] ?? '';


        $film_object = array(
            "title" => $title,
            "description" => $description,
            "release_year" => $release_year,
            "language_id" => $language_id,
            "original_language_id" => $original_language_id,
            "rental_duration" => $rental_duration,
            "rental_rate" => $rental_rate,
            "length" => $length,
            "replacement_cost" => $replacement_cost,
            "rating" => $rating,
            "special_features" => array_map('ucwords', $special_features),
        );

        $rules =
            [
                'in' => [
                    ['rating', ['G', 'PG', 'PG-13', 'R', 'NC-17']]
                ],
                'subset' => [['special_features', ['Trailers', 'Commentaries', 'Deleted Scenes', 'Behind The Scenes']]],
                'required' => [
                    'title', 'language_id', 'rental_duration', 'rental_rate', 'replacement_cost'
                ],
                'min' =>
                [
                    ['release_year', 2006],
                    ['language_id', 1],
                    ['original_language_id', 1],
                    ['rental_duration', 1],
                    ['rental_rate', 0],
                    ['length', 1],
                    ['replacement_cost', 0]
                ],
                'max' =>
                [
                    ['release_year', date("Y")]
                ]
            ];
        // Change the default language to French.
        //$validator = new Validator($data, [], "fr");
        $validator = new Validator($film_object);
        $validator->rules($rules);

        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePostMethods(array $data, string $label)
    {
        $body = array("description" => $data['description']);
        if ($label == "cases")
        {
            $rules = 
            [
               
                'required' => 'description',
                // 'min' => 
                // [
                //     ['misdemeanor', 0]
                // ],
                // 'lengthMax' =>
                // [
                //     ['description', 300]
                // ],
                // 'numeric' => 
                // [
                //     ['misdemeanor'],
                //     ['crime_sceneID'],
                //     ['investigator_id'],
                //     ['court_id']
                // ],
                // 'date' =>
                // [
                //     ['date_reported']
                // ]
            ]; 

        }  

        $validator = new Validator($body);
        $validator->rule("required", 'description')->message('{field} is required')->label('Desc');

        if ($validator->validate())
        {
            echo "not valid";
            return true;
        } else {
            echo "not valid";
            return false;
        }
    }

    /**
     * Summary of validatePostActor
     * @param array $data
     * @return bool
     */
    public static function validatePostActor(array $data)
    {
        $first_name = $data['first_name'] ?? '';
        $last_name = $data['last_name'] ?? '';

        $data = array(
            "fist_name" => $first_name,
            "last_name" => $last_name
        );

        $rules = [
            'alpha' => [
                'fist_name', 'last_name'
            ],
            // We can apply the same rule to multiple elements.
            'required' => [
                'fist_name', 'last_name'
            ],
            // Validate the max length of list of elements.
            'lengthMax' => array(
                array('fist_name', 20),
                array('last_name', 20)
            )
        ];
        // Change the default language to French.
        //$validator = new Validator($data, [], "fr");
        $validator = new Validator($data);
        $validator->rules($rules);

        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    public static function testIn()
    {

        $data = array(
            "title" => "Web",
            "description" => "desc",
            "release_year" => 2006,
            "language_id" => 1,
            "original_language_id" => 1,
            "rental_duration" => 2,
            "rental_rate" => 0.99,
            "length" => 2,
            "replacement_cost" => 50.99,
            "rating" => "G",
            "special_features" => array_map('ucwords', ['Commentaries', 'deleted scenes']),
        );

        var_dump($data['special_features']);

        $validator = new Validator($data);

        $validator->rules(
            [
                'in' => [
                    ['rating', ['G', 'PG', 'PG-13', 'R', 'NC-17']]
                ],
                'subset' => [['special_features', ['Trailers', 'Commentaries', 'Deleted Scenes', 'Behind The Scenes']]],
                'required' => [
                    'title', 'language_id', 'rental_duration', 'rental_rate', 'replacement_cost'
                ],
                'min' =>
                [
                    ['release_year', 2006],
                    ['language_id', 1],
                    ['original_language_id', 1],
                    ['rental_duration', 1],
                    ['rental_rate', 0],
                    ['length', 1],
                    ['replacement_cost', 0]
                ]
            ]
        );

        if ($validator->validate()) {
            echo "valid";
        } else {
            echo "not valid";
        }
    }

    /**
     * Summary of validatePostFilm
     * @param array $data
     * @return bool
     */
    public static function validatePostFilm(array $data)
    {
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $release_year = $data['release_year'] ?? '';
        $language_id = $data['language_id'] ?? '';
        $original_language_id = $data['original_language_id'] ?? '';
        $rental_duration = $data['rental_duration'] ?? 3;
        $rental_rate = $data['rental_rate'] ?? 4.99;
        $length = $data['length'] ?? '';
        $replacement_cost = $data['replacement_cost'] ?? 10.99;
        $rating = $data['rating'] ?? 'G';
        $special_features = $data['special_features'] ?? '';


        $film_object = array(
            "title" => $title,
            "description" => $description,
            "release_year" => $release_year,
            "language_id" => $language_id,
            "original_language_id" => $original_language_id,
            "rental_duration" => $rental_duration,
            "rental_rate" => $rental_rate,
            "length" => $length,
            "replacement_cost" => $replacement_cost,
            "rating" => $rating,
            "special_features" => array_map('ucwords', $special_features),
        );

        $rules =
            [
                'in' => [
                    ['rating', ['G', 'PG', 'PG-13', 'R', 'NC-17']]
                ],
                'subset' => [['special_features', ['Trailers', 'Commentaries', 'Deleted Scenes', 'Behind The Scenes']]],
                'required' => [
                    'title', 'language_id', 'rental_duration', 'rental_rate', 'replacement_cost'
                ],
                'min' =>
                [
                    ['release_year', 2006],
                    ['language_id', 1],
                    ['original_language_id', 1],
                    ['rental_duration', 1],
                    ['rental_rate', 0],
                    ['length', 1],
                    ['replacement_cost', 0]
                ],
                'max' =>
                [
                    ['release_year', date("Y")]
                ]
            ];
        // Change the default language to French.
        //$validator = new Validator($data, [], "fr");
        $validator = new Validator($film_object);
        $validator->rules($rules);

        if ($validator->validate()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Summary of validatePageNumbers
     * @param mixed $page
     * @param mixed $pageSize
     * @return bool
     */
    public static function validatePageNumbers($page, $pageSize)
    {
        if (!is_numeric($page) || !is_numeric($pageSize)) {
            return false;
        }
        return true;
    }

    /**
     * Summary of validateParams
     * @param array $param
     * @param array $params
     * @return bool
     */
    public static function validateParams($param, array $params)
    {
        if (in_array($param, $params)) {
            return true;
        }
        return false;
    }
}
