<?xml version='1.0' encoding='utf-8'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:NonHtml="http://www.progress.com/StylusStudio/NonHtml" exclude-result-prefixes="NonHtml">
<xsl:output method="html" encoding="UTF-8"/>
<xsl:template match="/">
	<div align="left">
		<table id="table2" width="100%" bgColor="#c0c0c0" border="0">
			<tbody>
				<tr>
					<td><font color="#ffffff" size="6"><i><xsl:value-of select="RECIPES/RECIPE/NAME"/></i></font></td>
				</tr>
				<tr>
					<td><font color="#ffffff"><i><xsl:value-of select="RECIPES/RECIPE/STYLE/NAME"/></i></font></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div align="center">
		<table id="table1" width="100%" border="0">
			<tbody>
				<tr>
					<td colSpan="2" bgColor="#c0c0c0">
						<p align="center">
							<b>
								<font color="#ffffff" size="4">Ingredients</font>
							</b>
						</p>
					</td>
				</tr>
				<tr>
					<td colSpan="2">
						<table border="1" cellSpacing="0" cellPadding="2" width="100%">
							<tbody>
								<tr>
									<th align="left" width="15%">Amount</th>
									<th align="left" width="54%">Item</th>
									<th align="left" width="14%">Type</th>
								</tr>
								<xsl:for-each select="RECIPES/RECIPE/FERMENTABLES/FERMENTABLE">
									<tr>
										<td align="left">
											<xsl:value-of select="DISPLAY_AMOUNT"/>
										</td>
										<td align="left">
											<xsl:value-of select="NAME"/> (<xsl:value-of select="DISPLAY_COLOR"/>) 
										</td>
										<td align="left">
											<xsl:value-of select="TYPE"/>
										</td>
									</tr>
								</xsl:for-each>
								<xsl:for-each select="RECIPES/RECIPE/HOPS/HOP">
									<tr>
										<td align="left">
											<xsl:value-of select="DISPLAY_AMOUNT"/>
										</td>
										<td align="left">
											<xsl:value-of select="NAME"/> [<xsl:value-of select="ALPHA"/>%] (<xsl:value-of select="USE"/> - <xsl:value-of select="DISPLAY_TIME"/>) 
										</td>
										<td align="left">Hops </td>
									</tr>
								</xsl:for-each>
								<xsl:for-each select="RECIPES/RECIPE/MISCS/MISC">
									<tr>
										<td align="left">
											<xsl:value-of select="DISPLAY_AMOUNT"/>
										</td>
										<td align="left">
											<xsl:value-of select="NAME"/> (<xsl:value-of select="USE"/> - <xsl:value-of select="DISPLAY_TIME"/>) 
										</td>
										<td align="left">
											<xsl:value-of select="TYPE"/>
										</td>
									</tr>
								</xsl:for-each>
								<xsl:for-each select="RECIPES/RECIPE/YEASTS/YEAST">
									<tr>
										<td align="left">
											<xsl:value-of select="DISPLAY_AMOUNT"/>
										</td>
										<td align="left">
											<xsl:value-of select="NAME"/> (<xsl:value-of select="LABORATORY"/> &#160;#<xsl:value-of select="PRODUCT_ID"/>) 
										</td>
										<td align="left">
											<xsl:value-of select="TYPE"/>
										</td>
									</tr>
								</xsl:for-each>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</xsl:template>
</xsl:stylesheet>
