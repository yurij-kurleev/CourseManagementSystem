<?php
class Model{
    /**
     * Checks if statement errors array empty. If it is not throws exception.
     * @param array $statementErrors
     * @throws StatementExecutionException
     */
    protected static function checkErrorArrayEmptiness(array $statementErrors){
        if (!empty($statementErrors[1])){
            throw new StatementExecutionException("Error" . $statementErrors[0] . ": " . $statementErrors[2]);
        }
    }
}