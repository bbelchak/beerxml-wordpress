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
					<td width="40%">
							<b><i>Type:</i></b> <xsl:value-of select="RECIPES/RECIPE/TYPE"/>
					</td>
					<td width="52%">
						<b><i>Date:</i></b> <xsl:value-of select="RECIPES/RECIPE/DATE"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b><i>Batch Size:</i></b> <xsl:value-of select="RECIPES/RECIPE/DISPLAY_BATCH_SIZE"/>
					</td>
					<td width="52%">
						<b><i>Brewer:</i></b> <xsl:value-of select="RECIPES/RECIPE/BREWER"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b><i>Boil Size:</i></b> <xsl:value-of select="RECIPES/RECIPE/DISPLAY_BOIL_SIZE"/>
					</td>
					<td width="52%" align="top">
						<b>
							<i>Asst Brewer</i>: 
						</b>
						<xsl:value-of select="RECIPES/RECIPE/ASST_BREWER"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b>
							<i>Boil Time</i>: 
						</b>
						<xsl:value-of select="RECIPES/RECIPE/BOIL_TIME"/> min. 
					</td>
					<td width="52%">
						<b>
							<i>Equipment</i>: 
						</b>
						<xsl:value-of select="RECIPES/RECIPE/EQUIPMENT/NAME"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b>
							<i>Taste Rating(out of 50)</i>: 
						</b>
						<xsl:value-of select="RECIPES/RECIPE/TASTE_RATING"/>
					</td>
					<td width="52%">
						<b>
							<i>Brewhouse Efficiency</i>: 
						</b>
						<xsl:value-of select="RECIPES/RECIPE/EFFICIENCY"/>% 
					</td>
				</tr>
				<tr>
					<td> </td>
					<td>
						<b>
							<i>Actual Efficiency:</i>
						</b>
						<xsl:value-of select="RECIPES/RECIPE/ACTUAL_EFFICIENCY"/>
					</td>
				</tr>
				<tr>
					<td colSpan="2">
						<b>
							<i>Taste Notes</i>: 
						</b>
						<xsl:value-of select="RECIPES/RECIPE/TASTE_NOTES"/>
					</td>
				</tr>
				<tr>
					<td colSpan="2">&#160;</td>
				</tr>
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
				<tr>
					<td colSpan="2">&#160;</td>
				</tr>
				<tr>
					<td colSpan="2" bgColor="#c0c0c0">
						<p align="center">
							<font color="#ffffff" size="4">
								<b>Beer Profile</b>
							</font>
						</p>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<p style="margin-top: 0px; margin-bottom: 0px">
							<b>
								<i>Est Original Gravity</i>: 
							</b> <xsl:value-of select="RECIPES/RECIPE/EST_OG"/>
						</p>
					</td>
					<td width="52%">
						<b>
							<i>Measured Original Gravity</i>: 
						</b> <xsl:value-of select="RECIPES/RECIPE/DISPLAY_OG"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<i>
							<b>Est Final Gravity:</b>
						</i> <xsl:value-of select="RECIPES/RECIPE/EST_FG"/>
					</td>
					<td width="52%">
						<b>
							<i>Measured Final Gravity</i>: 
						</b> <xsl:value-of select="RECIPES/RECIPE/DISPLAY_FG"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b>
							<i>Estimated ABV:</i>
						</b> <xsl:value-of select="RECIPES/RECIPE/EST_ABV"/>
					</td>
					<td width="52%">
						<i>
							<b>Actual Alcohol by Vol:</b>
						</i> <xsl:value-of select="RECIPES/RECIPE/ABV"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<i>
							<b>Bitterness:</b>
						</i> <xsl:value-of select="RECIPES/RECIPE/IBU"/>
					</td>
					<td width="52%">
						<b>
							<i>Calories:</i>
						</b> <xsl:value-of select="RECIPES/RECIPE/CALORIES"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<i>
							<b>Est Color:</b>
						</i> <xsl:value-of select="RECIPES/RECIPE/EST_COLOR"/>
					</td>
					<td width="52%"/>
				</tr>
				<tr>
					<td colSpan="2">&#160;</td>
				</tr>
				<tr>
					<td colSpan="2" bgColor="#c0c0c0">
						<p align="center">
							<font color="#ffffff" size="4">
								<b>Mash Profile</b>
							</font>
						</p>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b>
							<i>Mash Name</i>: 
						</b> <xsl:value-of select="RECIPES/RECIPE/MASH/NAME"/>
					</td>
					<td width="52%">
						<strong>
							<em>Grain Temperature</em>: 
						</strong> <xsl:value-of select="RECIPES/RECIPE/MASH/DISPLAY_GRAIN_TEMP"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<strong>
							<em>Sparge Temperature</em>: 
						</strong> <xsl:value-of select="RECIPES/RECIPE/MASH/DISPLAY_SPARGE_TEMP"/>
					</td>
					<td width="52%">
						<strong>
							<em>TunTemperature</em>: 
						</strong> <xsl:value-of select="RECIPES/RECIPE/MASH/DISPLAY_TUN_TEMP"/>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<strong>
							<em>Adjust Temp for Equipment</em>: 
						</strong> <xsl:value-of select="RECIPES/RECIPE/MASH/EQUIP_ADJUST"/>
					</td>
					<td width="52%">
						<strong>
							<em>Mash PH</em>: 
						</strong> <xsl:value-of select="RECIPES/RECIPE/MASH/PH"/>
					</td>
				</tr>
				<tr>
					<td colSpan="2">
						<p align="center">&#160;</p>
						<table cellSpacing="0" cellPadding="0" width="100%">
							<tbody>
								<tr>
									<th align="left" width="23%">Name</th>
									<th align="left" width="44%">Description</th>
									<th align="left" width="16%">Step Temp</th>
									<th align="left" width="16%">Step Time</th>
								</tr>
								<xsl:for-each select="RECIPES/RECIPE/MASH/MASH_STEPS/MASH_STEP">
									<tr>
										<td align="left">
											<xsl:value-of select="NAME"/>
										</td>
										<td align="left">
											<xsl:value-of select="DESCRIPTION"/>
										</td>
										<td align="left">
											<xsl:value-of select="round((9 div 5) * STEP_TEMP + 32)"/> F
										</td>
										<td align="left">
											<xsl:value-of select="STEP_TIME"/> min 
										</td>
									</tr>
								</xsl:for-each>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td colSpan="2">&#160;</td>
				</tr>
				<tr>
					<td colSpan="2">
						<b>
							<i>Mash Notes:</i>
						</b>
						<xsl:value-of select="RECIPES/RECIPE/MASH/NOTES"/>
					</td>
				</tr>
				<tr>
					<td colSpan="2" bgColor="#c0c0c0">
						<p align="center">
							<font color="#ffffff" size="4">
								<b>Notes</b>
							</font>
						</p>
					</td>
				</tr>
				<tr>
					<td colSpan="2">
						<xsl:value-of select="RECIPES/RECIPE/NOTES"/>
					</td>
				</tr>
			</tbody>
		</table>
		<p>&#160;</p>
	</div>
	<p style="margin-top: 0px; margin-bottom: 0px">&#160;</p>
	<p>&#160;</p>
</xsl:template>
</xsl:stylesheet>
