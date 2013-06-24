<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Frenzid
 *
 * @author artur
 */
class Frenzid {

    public $controller = MAIN_CONTROLLER;
    public $method = MAIN_METHOD;
    public $args = array();

    public function Frenzid($request) {
        $this->dom = new DOMDocument('1.0', SYSTEM_ENCODING);

        $params = explode('/', $request);
        /*
         * Cleanup empty values
         */
        foreach ($params as $key => $value) {
            if (!$value) {
                unset($params[$key]);
            }
        }
        /*
         * Prepare for popping params /controller/method/array(parmas) 
         */
        $params = array_reverse($params);

        $this->controller = ($tmp = array_pop($params)) ? $tmp : $this->controller;

        $this->method = ($tmp = array_pop($params)) ? $tmp : $this->method;

        $this->args = array_reverse($params);

        unset($params);


        $this->runExternalController();
    }

    public function startXmlOutput($formatting = false, $mime = false) {
        if ($mime)
            header("Content-Type:{$mime}");

        $this->domRoot = new DOMElement($this->controller);
        $this->domRoot = $this->dom->appendChild($this->domRoot);
        $this->dom->formatOutput = $formatting;
    }

    protected function runExternalController() {

        try {

            if (!@include_once( 'controllers/' . "{$this->controller}.php" )) {
                throw new Exception($this->controller);
            } else {

                $this->context = new $this->controller($this);
                try {
                    $this->runExternalMethod($this->method);
                } catch (Exception $e) {
                    $this->handleException($e);
                }
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function validateDTD($docObj) {

        if (!$validator = @$docObj->validate()) {
            throw new Exception($validator);
        }
    }

    public function noPlUrl($string) {
        $converter = Array(
            //WIN
            "\xb9" => "a", "\xa5" => "A", "\xe6" => "c", "\xc6" => "C",
            "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L",
            "\xf3" => "o", "\xd3" => "O", "\x9c" => "s", "\x8c" => "S",
            "\x9f" => "z", "\xaf" => "Z", "\xbf" => "z", "\xac" => "Z",
            "\xf1" => "n", "\xd1" => "N",
            //UTF
            "\xc4\x85" => "a", "\xc4\x84" => "A", "\xc4\x87" => "c", "\xc4\x86" => "C",
            "\xc4\x99" => "e", "\xc4\x98" => "E", "\xc5\x82" => "l", "\xc5\x81" => "L",
            "\xc3\xb3" => "o", "\xc3\x93" => "O", "\xc5\x9b" => "s", "\xc5\x9a" => "S",
            "\xc5\xbc" => "z", "\xc5\xbb" => "Z", "\xc5\xba" => "z", "\xc5\xb9" => "Z",
            "\xc5\x84" => "n", "\xc5\x83" => "N",
            //ISO
            "\xb1" => "a", "\xa1" => "A", "\xe6" => "c", "\xc6" => "C",
            "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L",
            "\xf3" => "o", "\xd3" => "O", "\xb6" => "s", "\xa6" => "S",
            "\xbc" => "z", "\xac" => "Z", "\xbf" => "z", "\xaf" => "Z",
            "\xf1" => "n", "\xd1" => "N");

        $strAscii = strtr($string, $converter);

        $string = strtolower($strAscii);
        $string = preg_replace("'[[:punct:][:space:]]'", '-', $string);

        $signs = '-';
        $repet = 1;
        $string = preg_replace_callback('#([' . $signs . '])\1{' . $repet . ',}#', create_function('$a', 'return substr($a[0], 0,' . $repet . ');'), $string);
        return $string;
    }
    
    
    

    protected function runExternalMethod($methodName) {


        if (method_exists($this->context, $methodName))
            $this->context->$methodName($this->args);
        else
            throw new Exception($methodName);
    }

    private function handleException($e) {

        $this->startXmlOutput(0, 'text/xml');

        $messageText = $e->getMessage();

        $error = $this->dom->createElement('error', $e->getTraceAsString());

        $error->setAttribute('causedBy', $messageText);

        $this->domRoot->appendChild($error);

        return;
    }

}
