<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author artur
 */
class home {

    function home(Frenzid &$context) {

        $this->context = $context;
        $this->domCilipboard = new DOMDocument('1.0', SYSTEM_ENCODING);
    }

    public function index() {
        $this->domCilipboard->Load(PREFABRICATE_PATH . 'home.xml');
        $node = $this->domCilipboard->getElementsByTagName("root")->item(0);
        /*
         * Start creating output doc
         */
        $this->context->startXmlOutput(0, 'text/xml');
        $page = $this->context->dom->createElement('content');
        $page->setAttribute('rendered', date('Y-m-d', time()));
        $page->setAttribute('renderedFrom', $this->domCilipboard->documentElement->tagName);


        /*
         * append prefabricated
         */
        $node = $this->context->dom->importNode($node, true); // true = rec import
        $page->appendChild($node);


        /*
         * append to root
         */
        $this->context->domRoot->appendChild($page);

        /*
         * parse output
         */
        $xsl = new DOMDocument();
        $xsl->load(XSL_PATH . 'home.xsl');
        $xslt = new XsltProcessor();
        $xslt->importStylesheet($xsl);
        $this->context->dom = $xslt->transformToDoc($this->context->dom);
    }

    /*
     * test method
     */

    public function method($args) {

        $this->context->startXmlOutput(0, 'text/xml');
    }

}
