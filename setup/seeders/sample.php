<?php
    /**
     * $recordSize  : defines the total number of records to be generated
     * $tableName   : Defines the table to seed
     * $data        : The data to be seeded
     * 
     * Fake methods : fullName(a,b), userName(a,b), age(a,b), dateTime(a,b), text(a,b), email(a,b), firstName(a,b), lastName(a,b)
     * 
     * Where:
     * a    : An integer, which specifies the number of records for that column, if = $recordSize, then its reuired, else it is optional
     * b    : A string which specifies the coniguration needed to operate on the generated data for that column, the configuration depends 
     *        on the methods, more than 1 configuration, could be specified, by sepearting with pipe "|", as lomg as it is a supported option for that method
     *  
     *          fullName()  : "unique" [optional]
     *          userName()  : "length:maxCharacters|unique" [optional]
     *          age()       : "range:start,end"
     *          dateTime()  : "format:unix" [optional] if not unix, any other dateObject format is suported example: format:Y-m-d
     *          text()      : "chars:numberOfCharacter" [required]
     *          email()     : "unique|length:maxCharacters" [optional]
     *          firstName() : "unique|length:maxCharacters" [optional]
     *          lastName()  : "unique|length:maxCharacters" [optional]
     * 
     * Note
     *  - You can pass additional local argument to the Fake constructor, it must be supported by fakerphp/faker library, Example: new Fake($recordSize, "en_NG"), the default is "en_US"
     *  - The $data array keys represents the table column names 
     */

    $recordSize = 20;
    $tableName  = "test";
    $fake       = new Fake($recordSize);

    /* SAMPLE SEEDER TEMPLATE*/

    
    $data = [
        "name" => $fake->fullName($recordSize, ""),
        "user_name" => $fake->userName($recordSize, "length:15|unique"),
        "age" => $fake->age($recordSize, "range:18,48"),
        "reg_date" => $fake->dateTime(3, "format:unix"),
        "text" => $fake->text(8, "chars:200"),
        "email" => $fake->email(7, ""),
        "first_name" => $fake->firstName(15, ""),
        "last_name" => $fake->lastName(10, "")
    ];
?>