<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of browseapp
 *
 * @author artur
 */
class browseapp {

    function browseapp(Frenzid &$context) {

        $this->context = $context;

        $this->domCilipboard = new DOMDocument('1.0', SYSTEM_ENCODING);
    }

    public function index() {

        /*
         * Start creating output doc
         */
        $this->context->startXmlOutput(0, 'text/xml');
        $page = $this->context->dom->createElement('content');
        $page->setAttribute('rendered', date('Y-m-d', time()));
        $page->setAttribute('renderedFrom', $this->context->controller);


        /*
         * Generate saved applications list
         */

        /*
         * load data from model, may be static in future
         */
        $this->appsCollection = Application::all();
        foreach ($this->appsCollection as $k => $v) {

            $node = $this->context->dom->createElement('applink');
            $node->setAttribute('id', $v->id);
            $node->setAttribute('app_signature', $v->app_identifier);

            $page->appendChild($node);
        }

        /*
         * append to root
         */
        $this->context->domRoot->appendChild($page);

        /*
         * parse output
         */
        $xsl = new DOMDocument();
        $xsl->load(XSL_PATH . 'list.xsl');
        $xslt = new XsltProcessor();
        $xslt->importStylesheet($xsl);
        $this->context->dom = $xslt->transformToDoc($this->context->dom);
    }

    public function show($args) {

        if (isset($args[0]) && (int) $args[0] != 0) {
            $appId = (int) $args[0];
        } else {
            return $this->index();
        }

        $this->appObject = Application::find($appId);
        $this->appObjectData = unserialize($this->appObject->app_serialized);


        /*
         * load raw container for ObjectData
         */
        $this->domCilipboard->Load(PREFABRICATE_PATH . 'krs.xml');


        /*
         * fill conteiner with data
         */


        $xpath = new DOMXPath($this->domCilipboard);

        foreach ($this->appObjectData as $id => $generatedVal) {
            $xpathStr = '//field[@id="' . $id . '"]';
            $element = $xpath->query($xpathStr)->item(0);
            if ($element instanceof DomElement)
                $element->setAttribute("genratedVal", $generatedVal);
        }



        /*
         * start creating output
         */
        $this->context->startXmlOutput(0, 'text/xml');
        $page = $this->context->dom->createElement('content');
        $page->setAttribute('rendered', date('Y-m-d', time()));
        $page->setAttribute('renderedFrom', $this->domCilipboard->documentElement->tagName);



        /*
         * append prefabricated
         */
        $node = $this->domCilipboard->getElementsByTagName("application")->item(0);
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
        $xsl->load(XSL_PATH . 'krs.xsl');
        $xslt = new XsltProcessor();
        $xslt->importStylesheet($xsl);
        $this->context->dom = $xslt->transformToDoc($this->context->dom);
    }

    //put your code here
}

?>
