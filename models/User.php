<?php 
namespace models;
class User extends BaseModel
{
    public $tableName='users';
    public function getName()
    {
        return 'tom';
    }
}