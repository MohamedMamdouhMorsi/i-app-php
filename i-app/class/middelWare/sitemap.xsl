<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xhtml="http://www.w3.org/1999/xhtml">
  <xsl:template match="/">
    <html>
      <head>
        <title>XML Sitemap</title>
        <style type="text/css">
          body {font-family: Arial, sans-serif;}
          table {border-collapse: collapse; width: 100%;}
          th, td {border: 1px solid #ccc; padding: 8px; text-align: left;}
          th {background-color: #f4f4f4;}
        </style>
      </head>
      <body>
        <h1>XML Sitemap</h1>
        <h3>Scured By <a href="https://i-app.org/">i-app Framework</a></h3>
         
        <table>
          <tr>
            <th>URL</th>
            <th>Language Alternate Links</th>
          </tr>
          <xsl:for-each select="urlset/url">
            <tr>
              <td><xsl:value-of select="loc"/></td>
              <td>
                <xsl:for-each select="xhtml:link">
                  <xsl:value-of select="@hreflang"/>: <xsl:value-of select="@href"/><br/>
                </xsl:for-each>
              </td>
            </tr>
          </xsl:for-each>
        </table>
          <a href="https://m-w-n.com/">Powered By MWN Software</a>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
