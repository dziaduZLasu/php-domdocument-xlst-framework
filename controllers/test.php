<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test
 *
 * @author artur
 */
class test {

    function test(Frenzid &$context) {

        $this->context = $context;
    }

    public function index() {

        $this->context->startXmlOutput(0, 'text/xml');
    }

    public function method($args) {

         $this->context->startXmlOutput(0, 'text/xml');
    }

}
