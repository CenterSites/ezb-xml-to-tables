<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:strip-space elements="*"/>

	<xsl:template match="node()|@*">
		<xsl:copy>
		<xsl:apply-templates select="node()|@*"/>
		</xsl:copy>
	</xsl:template>





<!-- TableProperties -->
	<xsl:template match="//TableProperties/Property">
		<Property>
			<xsl:variable name="headerid">
				<xsl:value-of select="@tableHeader"/>
			</xsl:variable>

			<xsl:variable name="theNode" select="../../../..//ArticleTableHeader[@id=$headerid]"/>

			<xsl:attribute name="headername">
				<xsl:value-of select="$theNode/Name/text()"/>
			</xsl:attribute>

			<xsl:attribute name="headerId">
				<xsl:value-of select="$theNode/@headerId"/>
			</xsl:attribute>

			<xsl:attribute name="toSpecification">
				<xsl:value-of select="$theNode/@toSpecification"/>
			</xsl:attribute>

			<xsl:attribute name="isHidden">
				<xsl:value-of select="$theNode/@isHidden"/>
			</xsl:attribute>

			<xsl:attribute name="isGrouped">
				<xsl:value-of select="$theNode/@isGrouped"/>
			</xsl:attribute>

			<xsl:attribute name="Icon">
				<xsl:value-of select="$theNode/Assets/Asset/OriginalFile/text()"/>
			</xsl:attribute>

			<xsl:attribute name="type">
				<xsl:value-of select="$theNode/Assets/Asset/@type"/>
			</xsl:attribute>


				<!-- get @articleSubGroup id-->
				<xsl:variable name="IdArticleSubGroup">
					<xsl:value-of select="../..//@articleSubGroup"/>
				</xsl:variable>

				<xsl:variable name="theNodeSubGroup" select="../../../..//ArticleSubGroups/ArticleSubGroup[@id=$IdArticleSubGroup]"/>

				<xsl:attribute name="ArticleSubGroupHeader">
					<xsl:value-of select="$theNodeSubGroup/Header/text()"/>
				</xsl:attribute>
				
				<xsl:attribute name="ArticleSubGroupPos">
					<xsl:value-of select="$theNodeSubGroup/@position"/>
				</xsl:attribute>


			<xsl:apply-templates select="@*|node()"/>
		</Property>
	</xsl:template>




<!-- SupplierProperties -->
	<xsl:template match="//SupplierProperties/Property">
		<Property>
			<xsl:variable name="subheaderid">
				<xsl:value-of select="@header"/>
			</xsl:variable>

			<xsl:variable name="theNode" select="../../../..//SupplierHeader[@id=$subheaderid]"/>

			<xsl:attribute name="supDesc">
				<xsl:value-of select="$theNode/Description/text()"/>
			</xsl:attribute>

			<xsl:attribute name="supName">
				<xsl:value-of select="$theNode/@name"/>
			</xsl:attribute>

			<xsl:attribute name="supUnit">
				<xsl:value-of select="$theNode/@unit"/>
			</xsl:attribute>

			<xsl:attribute name="supUnitDesc">
				<xsl:value-of select="$theNode/@ezBaseUnitDescription"/>
			</xsl:attribute>

			<xsl:attribute name="supUnitCat">
				<xsl:value-of select="$theNode/@ezBaseUnitCategory"/>
			</xsl:attribute>


			<xsl:attribute name="Icon">
				<xsl:value-of select="$theNode/Assets/Asset/OriginalFile/text()"/>
			</xsl:attribute>


			<xsl:apply-templates select="@*|node()"/>
		</Property>
	</xsl:template>




	<!-- NormalizedProperties -->
	<xsl:template match="//NormalizedProperties/Property">
		<Property>
			<xsl:variable name="norheaderid">
				<xsl:value-of select="@header"/>
			</xsl:variable>

			<xsl:variable name="theNode" select="../../../..//NormalizedHeader[@id=$norheaderid]"/>

			<xsl:attribute name="normalHeaderName">
				<xsl:value-of select="$theNode/text()"/>
			</xsl:attribute>

			<xsl:attribute name="normalHeaderType">
				<xsl:value-of select="$theNode/@type"/>
			</xsl:attribute>


			<xsl:apply-templates select="@*|node()"/>
		</Property>
	</xsl:template>





	


		<xsl:template match="//RelatedArticles/Article">
		<Article>
			<xsl:variable name="idRel">
				<xsl:value-of select="@id"/>
			</xsl:variable>

			<xsl:variable name="theNodeRelated" select="/Webshop/ArticleGroups/ArticleGroup/Articles/Article[@id=$idRel]"/>

			<xsl:attribute name="ArticleGroupName">
				<xsl:value-of select="$theNodeRelated/ancestor::ArticleGroup/Name/text()"/>
			</xsl:attribute>

			<xsl:attribute name="ArticleGroupHeader">
				<xsl:value-of select="$theNodeRelated/ancestor::ArticleGroup/Specifications/Specification/Header/text()"/>
			</xsl:attribute>

			<xsl:attribute name="ArticleGroupBrand">
				<xsl:value-of select="$theNodeRelated/ancestor::ArticleGroup/Brand/text()"/>
			</xsl:attribute>

			<xsl:attribute name="ArticleGroupKind">
				<xsl:value-of select="$theNodeRelated/ancestor::ArticleGroup/Kind/text()"/>
			</xsl:attribute>

			<xsl:attribute name="ArticleGroupImage">
				<xsl:value-of select="$theNodeRelated/ancestor::ArticleGroup/Assets/Asset[@category=1]/OriginalFile/text()"/>
			</xsl:attribute>

			<xsl:attribute name="DescErp">
				<xsl:value-of select="$theNodeRelated/Description/text()"/>
			</xsl:attribute>

			<xsl:attribute name="DescPricelist">
				<xsl:value-of select="$theNodeRelated/ProductDetailPrices/Price/ArticleDescription/text()"/>
			</xsl:attribute>

			<xsl:attribute name="ArticleNumber">
				<xsl:value-of select="$theNodeRelated/ArticleNumber/text()"/>
			</xsl:attribute>

			<xsl:attribute name="State">
				<xsl:value-of select="$theNodeRelated/States/State/text()"/>
			</xsl:attribute>

			<xsl:attribute name="LifeTime">
				<xsl:value-of select="$theNodeRelated/States/LifeTime/text()"/>
			</xsl:attribute>

			<xsl:attribute name="TypeNumber">
				<xsl:value-of select="$theNodeRelated/TypeNumber/text()"/>
			</xsl:attribute>

			<xsl:attribute name="GrossPrice">
				<xsl:value-of select="$theNodeRelated/ProductDetailPrices/Price/GrossPrice"/>
			</xsl:attribute>

			<xsl:attribute name="GrossPriceGbp">
				<xsl:value-of select="$theNodeRelated/ProductDetailPrices/Price/GrossPrice * 0.896"/>
			</xsl:attribute>

			<xsl:attribute name="PriceBaseQuantity">
				<xsl:value-of select="$theNodeRelated/ProductDetailPrices/Price/PriceBaseQuantity/text()"/>
			</xsl:attribute>

			<xsl:attribute name="UnitOfMeasurePriceBase">
				<xsl:value-of select="$theNodeRelated/ProductDetailPrices/Price/UnitOfMeasurePriceBase/text()"/>
			</xsl:attribute>

			<xsl:attribute name="MinimumBuyingQuantity">
				<xsl:value-of select="$theNodeRelated/ProductDetailPrices/Price/MinimumBuyingQuantity/text()"/>
			</xsl:attribute>

			<xsl:attribute name="AssetGrpImg1">
				<xsl:value-of select="$theNodeRelated/Assets/Asset[@category='1'][1]/OriginalFile"/>
			</xsl:attribute>
		

			<xsl:apply-templates select="@*|node()"/>
		</Article>
	</xsl:template>








	<xsl:template name="walkUp">
            <xsl:param name="id"/>
           <xsl:if test="$id !=''">
		            <xsl:call-template name="walkUp">
		               <xsl:with-param name="id" select="../../../../Classification/Node[@id=$id]/@parent"/>
		            </xsl:call-template>

	            <group>
					<xsl:attribute name="idClass">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/@id"/>
					</xsl:attribute>
					<xsl:attribute name="name">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/Name/text()"/>
					</xsl:attribute>
					<xsl:attribute name="synonym">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/Synonyms/Synonym/text()"/>
					</xsl:attribute>
					<xsl:attribute name="positionlevel">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/@position"/>
					</xsl:attribute>

					<xsl:attribute name="description">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/Description/text()"/>
					</xsl:attribute>

					<xsl:attribute name="title">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/Title/text()"/>
					</xsl:attribute>

					<xsl:attribute name="body">
						<xsl:value-of select="../../../../Classification/Node[@id=$id]/Body/text()"/>
					</xsl:attribute>

	           </group>

           </xsl:if>
       </xsl:template>

	<xsl:template name="walkUpB">
            <xsl:param name="id"/>
           <xsl:if test="$id !=''">
		            <xsl:call-template name="walkUpB">
		               <xsl:with-param name="id" select="../../../../Classification/Node[@id=$id]/@parent"/>
		            </xsl:call-template>
		            
           </xsl:if>
       </xsl:template>



	<xsl:template match="//ArticleGroups/ArticleGroup/Classifications/Classification">
		<Classification>
 		    <xsl:apply-templates select="@*"/>
                   <xsl:call-template name="walkUp">
                         <xsl:with-param name="id" select="@id"/>
                    </xsl:call-template>
                   <xsl:call-template name="walkUpB">
                         <xsl:with-param name="id" select="@id"/>
                    </xsl:call-template>
		    <xsl:apply-templates select="node()"/>
		</Classification>
	</xsl:template>


</xsl:stylesheet>



				<!-- get @supplierHeader id
				<xsl:variable name="IdSupplierHeader">
					<xsl:value-of select="../..//SupplierProperties/Property/@header"/>
				</xsl:variable>

				<xsl:variable name="theNodeSupplierHeader" select="../../../..//SupplierHeaders/SupplierHeader[@id=$IdSupplierHeader]"/>

				<xsl:attribute name="supplierHeaderDesc">
					<xsl:value-of select="$theNodeSupplierHeader/Description/text()"/>
				</xsl:attribute>
				
				<xsl:attribute name="supplierHeaderName">
					<xsl:value-of select="$theNodeSupplierHeader/@id"/>
				</xsl:attribute>

				-->


