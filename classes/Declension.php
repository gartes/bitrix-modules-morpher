<?php

namespace Morpher;

class Declension {
 
    const URL = 'https://ws3.morpher.ru/russian/declension?s=';
    const FIELDS = [1 => 'Р', 'Д', 'В', 'Т', 'П'];
 
    private $_cache = [];
 
    private function getFromTable($text) {
        global $DB;
        $text = $DB->ForSql($text);
        $query = 'select * from morpher where NAME = "' . $text . '"';
        $res = $DB->Query($query);
        if($row = $res->Fetch()) {
            return $row;
        }
    }
    
    private function insertIntoTable($text, $result) {
        global $DB;
        $text = $DB->ForSql($text);
        foreach ($result as $key => $value) {
            $result[$key] = $DB->ForSql($value);
        }
        $sql = "insert into morpher (NAME, NAME_FORM1, NAME_FORM2, NAME_FORM3, NAME_FORM4, NAME_FORM5) "
             . " values ('{$text}', '{$result['NAME_FORM1']}', '{$result['NAME_FORM2']}', '{$result['NAME_FORM3']}', '{$result['NAME_FORM4']}', '{$result['NAME_FORM5']}');";
        $res = $DB->Query($sql);
    }
 
    public function getForm($text, $form) {
        if($data = $this->get($text)) {
            return $data['NAME_FORM' . $form];
        }
    }
 
    private function request($text) {
        $url = self::URL . urlencode($text);
        $data = (array) simplexml_load_file($url); 
        foreach (self::FIELDS as $num => $letter) {
            $result['NAME_FORM' . $num] = $data[$letter];
        } 
        $this->insertIntoTable($text, $result);
        return $result;
    }
 
    public function get($text) { 
        if(!$text) {
            return false;
        }
        if (!$this->_cache[$text]) { 
            if ($data = $this->getFromTable($text)) {
                $this->_cache[$text] = $data;
            } else { 
                $this->_cache[$text] = $this->request($text);
            }
        }
        return $this->_cache[$text];
    }

}