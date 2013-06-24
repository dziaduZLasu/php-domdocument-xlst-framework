<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of addapp
 *
 * @author artur
 */
class addapp {

    private $domClipboard;

    function addapp(Frenzid &$context) {

        $this->context = $context;
        $this->domCilipboard = new DOMDocument('1.0', SYSTEM_ENCODING);
    }

    public function index($infoNode = null, $repopArr=null) {

        /*
         * Check if was post
         */
        if (isset($_SESSION['postCheck'])) {
            $infoNode = 'Dodano. Sygnatura Twojego wniosku to: '.$_SESSION['app_identifier'];
            unset($_SESSION['postCheck']);
            unset($_SESSION['app_identifier']);
        }

        $this->domCilipboard->Load(PREFABRICATE_PATH . 'krs.xml');
        $this->context->validateDTD($this->domCilipboard);

        $node = $this->domCilipboard->getElementsByTagName("application")->item(0);

        /*
         * Start creating output doc
         */
        $this->context->startXmlOutput(0, 'text/xml');
        $page = $this->context->dom->createElement('content');

        if ($infoNode) {

            $infoBox = $this->context->dom->createElement('infobox');
            $infoBox->setAttribute('alert', $infoNode);
            $page->appendChild($infoBox);
        }


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
        $xsl->load(XSL_PATH . 'krs.xsl');
        $xslt = new XsltProcessor();
        $xslt->importStylesheet($xsl);
        $this->context->dom = $xslt->transformToDoc($this->context->dom);
    }

    public function processform($args=null) {

        /*
         * id               int(11) PK
         * app_identifier   varchar(45)
         * app_title        text
         * app_serialized   text
         */

        foreach ($_POST as $k => $v) {
            if (!$_POST[$k]) {
                return $this->index('wypełnij wszystkie pola');
            }
        }


        $this->postData = $this->sanitizePost($_POST);
        unset($_POST);

        $_SESSION['app_identifier'] = $app_identifier = date("Y-m-d", time()) . '/' . time() . '/' . rand();

        $this->newApplication = Application::create(
                        array('app_identifier' => $app_identifier,
                            'app_code' => $this->postData['doccode'],
                            'app_serialized' => serialize($this->postData))
        );

        $_SESSION['postCheck'] = true;

        header("Location: " . BASE_URL.'addapp');
    }

    private function sanitizePost($data) {

        $sanePost = array();

        foreach ($data as $k => $v) {

            $sanePost[$this->context->noPlUrl($k)] = $v;
        }

        return $sanePost;
    }

}
