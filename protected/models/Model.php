<?php
class Model{
    //rename
    protected static function checkErrorArrayEmptiness(array $statementErrors){
        if (!empty($statementErrors[1])){
            throw new StatementExecutionException("Error" . $statementErrors[0] . ": " . $statementErrors[2]);
        }
    }
}