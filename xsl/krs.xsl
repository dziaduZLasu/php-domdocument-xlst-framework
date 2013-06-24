<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
    <xsl:template match="application">
        <html>
            <body>
   
                <div class="application" style="width:800px;margin:0 auto;">
                    <xsl:if test="/addapp/content/infobox/@alert">
                        <div class="infobox" style="border:1px dotted;color:red;padding:12px;"> 
                            <xsl:value-of select="/addapp/content/infobox/@alert"/>
                        </div>
                    </xsl:if>
                    <form method="POST" action="/addapp/processform">
                        <input value="{@doccode}"  type="hidden" name="doccode" />
                        <xsl:for-each select="section">
            
                            <xsl:choose>
                                <xsl:when test="@type='header'">
			<!-- HEADER BOX START -->
                                    <div class="header" style="padding:5px;margin:0 0 12px 0;">
                            
                                        <p class="title" style="background:#CCC;padding:3px;font-weight:bold;">
                                            <xsl:value-of select="@title"/>
                                        </p>
				<!-- SUBSECTIONS START -->
                                        <xsl:for-each select="subsection">
                                            <div class="subsection" style="background:#F2F2F2;padding:5px;">
                                                <p class="title" style="font-size:12px;font-weight:bold;">
                                                    <xsl:value-of select="@title"/>
                                                </p>
						<!-- FIELDS START -->
                                                <xsl:for-each select="field">
                                                    <div class="field" style="background:#FFF;padding:5px;margin:0 0 6px 0;">
                                                        <p class="title" style="font-size:12px;">
                                                            <xsl:value-of select="position()" />. 
                                                            <xsl:value-of select="@title"/>
                                                        </p>
                                                        <div class="input">
                                                            <xsl:value-of select="@default"/>
                                                                
                                                            <input name="{@id}"  type="text" value="{@genratedVal}" />
                                                                
                                                        </div>
                                                    </div>
                                                </xsl:for-each>
                                            </div>
                                        </xsl:for-each>
                                    </div>
                                </xsl:when>
                                <xsl:otherwise>
			<!-- OTHER BOXES START -->
                                    <div class="data"  style="padding:5px;margin:0 0 12px 0;">
                                        <p class="title" style="background:#CCC;padding:3px;font-weight:bold;">
                                            <xsl:value-of select="@title"/>
                                        </p>
				<!-- SUBSECTIONS START -->
                                        <xsl:for-each select="subsection">
                                            <div class="subsection" style="background:#F2F2F2;padding:5px;">
                                                <p class="title" style="font-size:12px;font-weight:bold;">
                                                    <xsl:value-of select="@title"/>
                                                </p>
						<!-- FIELDS START -->
                                                <xsl:for-each select="field">
                                                    <div class="field" style="background:#FFF;padding:5px;margin:0 0 6px 0;">
                                                        <p class="title" style="font-size:12px;">
                                                            <xsl:value-of select="position()" />. 
                                                            <xsl:value-of select="@title"/>
                                                        </p>
                                                        <div clas="input">
                                                            <xsl:value-of select="@default"/>
                                                            <input name="{@id}" type="text" value="{@genratedVal}" />
                                                        </div>
                                                    </div>
                                                </xsl:for-each>
                                            </div>
                                        </xsl:for-each>
                                    </div>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:for-each>
                        <input type="submit" value="Zapisz" />
                    </form>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>