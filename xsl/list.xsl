<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : home.xsl
    Created on : 3 styczeń 2012, 17:27
    Author     : artur
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
    <xsl:template match="content">
        <html>
            <body>
                <xsl:for-each select="applink">
                    <p class="title" style="font-size:12px;font-weight:bold;">
                        <a href="browseapp/show/{@id}">
                            <xsl:value-of select="@app_signature"/>
                        </a>
                    </p>
                </xsl:for-each> 
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
