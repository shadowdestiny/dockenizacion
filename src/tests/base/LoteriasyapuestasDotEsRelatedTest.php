<?php
namespace tests\base;

use tests\unit\utils\CurlResponse;

trait LoteriasyapuestasDotEsRelatedTest
{
    protected $rss_results = <<<'EOD'
<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
        <image>
            <url>http://www.loteriasyapuestas.es/f/loterias/estaticos/imagenes/RSSEuromillones.jpg</url>
            <title>Resultados de Euromillones</title>
            <link><![CDATA[http://www.loteriasyapuestas.es/es/euromillones/resultados]]></link>
        </image>
        <title>Resultados de Euromillones</title>
        <atom:link href="http://www.loteriasyapuestas.es/es/euromillones/resultados" rel="self" type="application/rss+xml" />
        <link><![CDATA[http://www.loteriasyapuestas.es/es/euromillones/resultados]]></link>
        <description>Resultados de Euromillones</description>
        <language></language>
        <copyright>Loter&amp;iacute;as y Apuestas del Estado</copyright>
        <managingEditor>atencionalcliente@loteriasyapuestas.net (Loter&amp;iacute;as y apuestas del estado)</managingEditor>
        <webMaster>atencionalcliente@loteriasyapuestas.net (Loter&amp;iacute;as y apuestas del estado)</webMaster>
        <category>Avisos</category>
        <generator>mvc-version: 3.3.1</generator>
        <item>
            	<title>Euromillones: premios y ganadores del viernes 05 de junio de 2015</title>
				<pubDate>Fri, 05 Jun 2015 22:11:59 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dviernes%2D05%2Dde%2Djunio%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 05 de junio de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>02 - 07 - 08 - 45 - 48</b> Estrellas: <b>01 - 09</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>1</td>
<td>293.926.57 €</td>
<td>9</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>2</td>
<td>88.177.97 €</td>
<td>10</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>9</td>
<td>6.680.15 €</td>
<td>66</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>285</td>
<td>275.16 €</td>
<td>1.402</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>622</td>
<td>131.49 €</td>
<td>2.934</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>898</td>
<td>60.87 €</td>
<td>4.527</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>12.741</td>
<td>18.93 €</td>
<td>66.973</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>14.521</td>
<td>16.73 €</td>
<td>72.488</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>30.808</td>
<td>13.41 €</td>
<td>152.009</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>67.534</td>
<td>9.98 €</td>
<td>358.960</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>227.155</td>
<td>8.52 €</td>
<td>1.138.617</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>477.059</td>
<td>4.15 €</td>
<td>2.390.942</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150605221159d05ac2aad11cd410c2aad11cd410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del viernes 05 de junio de 2015</title>
				<pubDate>Fri, 05 Jun 2015 21:30:40 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dviernes%2D05%2Dde%2Djunio%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 05 de junio de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>02 - 07 - 08 - 45 - 48</b> Estrellas: <b>01 - 09</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015060521304037b9c2aad11cd410c2aad11cd410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del martes 02 de junio de 2015</title>
				<pubDate>Tue, 02 Jun 2015 22:11:57 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dmartes%2D02%2Dde%2Djunio%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 02 de junio de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>07 - 23 - 29 - 37 - 41</b> Estrellas: <b>01 - 08</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>1</td>
<td>510.830.88 €</td>
<td>2</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>3</td>
<td>68.110.78 €</td>
<td>5</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>8</td>
<td>4.602.08 €</td>
<td>37</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>165</td>
<td>203.26 €</td>
<td>733</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>388</td>
<td>90.96 €</td>
<td>1.638</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>306</td>
<td>68.88 €</td>
<td>1.545</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>4.840</td>
<td>20.88 €</td>
<td>23.447</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>6.609</td>
<td>14.90 €</td>
<td>31.421</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>15.178</td>
<td>11.35 €</td>
<td>69.388</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>26.236</td>
<td>11.09 €</td>
<td>124.715</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>98.437</td>
<td>8.24 €</td>
<td>454.533</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>217.310</td>
<td>3.94 €</td>
<td>972.232</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150602221157042b46cbdf1bd41056cbdf1bd410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del martes 02 de junio de 2015</title>
				<pubDate>Tue, 02 Jun 2015 21:24:05 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dmartes%2D02%2Dde%2Djunio%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 02 de junio de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>07 - 23 - 29 - 37 - 41</b> Estrellas: <b>01 - 08</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150602212405d68a46cbdf1bd41056cbdf1bd410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del viernes 29 de mayo de 2015</title>
				<pubDate>Fri, 29 May 2015 22:17:53 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dviernes%2D29%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 29 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>03 - 04 - 20 - 45 - 48</b> Estrellas: <b>06 - 08</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>0</td>
<td>499.345.31 €</td>
<td>3</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>2</td>
<td>62.418.16 €</td>
<td>8</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>9</td>
<td>8.609.40 €</td>
<td>29</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>167</td>
<td>286.70 €</td>
<td>762</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>254</td>
<td>141.68 €</td>
<td>1.542</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>414</td>
<td>71.98 €</td>
<td>2.168</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>6.974</td>
<td>20.52 €</td>
<td>34.973</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>7.420</td>
<td>17.41 €</td>
<td>39.444</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>14.638</td>
<td>14.76 €</td>
<td>78.219</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>39.487</td>
<td>10.16 €</td>
<td>199.657</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>120.679</td>
<td>8.60 €</td>
<td>639.068</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>237.630</td>
<td>4.43 €</td>
<td>1.269.067</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150529221753c2fa01ae90d9d41001ae90d9d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del viernes 29 de mayo de 2015</title>
				<pubDate>Fri, 29 May 2015 21:30:33 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dviernes%2D29%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 29 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>03 - 04 - 20 - 45 - 48</b> Estrellas: <b>06 - 08</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150529213033e03a01ae90d9d41001ae90d9d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del martes 26 de mayo de 2015</title>
				<pubDate>Tue, 26 May 2015 22:17:58 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dmartes%2D26%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 26 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>05 - 06 - 07 - 21 - 24</b> Estrellas: <b>05 - 06</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>1</td>
<td>37.682.171.00 €</td>
<td>1</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>1</td>
<td>327.012.22 €</td>
<td>3</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>1</td>
<td>54.502.04 €</td>
<td>6</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>31</td>
<td>1.513.95 €</td>
<td>108</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>296</td>
<td>127.17 €</td>
<td>1.125</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>400</td>
<td>79.97 €</td>
<td>1.789</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>806</td>
<td>33.70 €</td>
<td>3.032</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>8.105</td>
<td>14.78 €</td>
<td>31.811</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>11.623</td>
<td>9.69 €</td>
<td>46.394</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>16.916</td>
<td>10.08 €</td>
<td>75.007</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>32.980</td>
<td>10.14 €</td>
<td>130.979</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>146.617</td>
<td>5.97 €</td>
<td>602.988</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>227.016</td>
<td>3.66 €</td>
<td>1.005.578</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015052622175873fceb944bd8d410eb944bd8d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del martes 26 de mayo de 2015</title>
				<pubDate>Tue, 26 May 2015 21:24:08 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dmartes%2D26%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 26 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>05 - 06 - 07 - 21 - 24</b> Estrellas: <b>05 - 06</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150526212408052ceb944bd8d410eb944bd8d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del viernes 22 de mayo de 2015</title>
				<pubDate>Fri, 22 May 2015 22:35:53 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dviernes%2D22%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 22 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>18 - 24 - 35 - 44 - 45</b> Estrellas: <b>05 - 11</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>2</td>
<td>723.961.15 €</td>
<td>2</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>2</td>
<td>80.440.13 €</td>
<td>6</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>13</td>
<td>6.522.17 €</td>
<td>37</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>191</td>
<td>236.99 €</td>
<td>891</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>356</td>
<td>127.05 €</td>
<td>1.662</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>346</td>
<td>80.61 €</td>
<td>1.871</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>5.681</td>
<td>23.00 €</td>
<td>30.168</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>7.941</td>
<td>16.72 €</td>
<td>39.696</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>16.131</td>
<td>14.00 €</td>
<td>79.741</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>30.410</td>
<td>12.16 €</td>
<td>161.191</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>126.203</td>
<td>8.57 €</td>
<td>619.643</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>244.620</td>
<td>4.37 €</td>
<td>1.242.187</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150522223553c67b8065ff87d4109065ff87d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del viernes 22 de mayo de 2015</title>
				<pubDate>Fri, 22 May 2015 21:30:00 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dviernes%2D22%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 22 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>18 - 24 - 35 - 44 - 45</b> Estrellas: <b>05 - 11</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015052221300092aa8065ff87d4109065ff87d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del martes 19 de mayo de 2015</title>
				<pubDate>Tue, 19 May 2015 22:26:53 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dmartes%2D19%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 19 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>26 - 30 - 31 - 35 - 37</b> Estrellas: <b>08 - 11</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>2</td>
<td>243.341.64 €</td>
<td>4</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>0</td>
<td>54.075.92 €</td>
<td>6</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>6</td>
<td>7.373.99 €</td>
<td>22</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>106</td>
<td>300.74 €</td>
<td>472</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>179</td>
<td>146.95 €</td>
<td>966</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>218</td>
<td>92.94 €</td>
<td>1.091</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>3.501</td>
<td>26.71 €</td>
<td>17.459</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>4.641</td>
<td>20.17 €</td>
<td>22.122</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>10.279</td>
<td>15.83 €</td>
<td>47.390</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>20.288</td>
<td>13.22 €</td>
<td>99.729</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>77.475</td>
<td>9.73 €</td>
<td>366.744</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>173.400</td>
<td>4.57 €</td>
<td>798.965</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015051922265308bc43d5e996d41043d5e996d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del martes 19 de mayo de 2015</title>
				<pubDate>Tue, 19 May 2015 21:24:04 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dmartes%2D19%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 19 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>26 - 30 - 31 - 35 - 37</b> Estrellas: <b>08 - 11</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150519212404d41c43d5e996d41043d5e996d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del viernes 15 de mayo de 2015</title>
				<pubDate>Fri, 15 May 2015 22:13:25 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dviernes%2D15%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 15 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>05 - 35 - 42 - 44 - 47</b> Estrellas: <b>08 - 09</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>0</td>
<td>1.349.968.94 €</td>
<td>1</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>0</td>
<td>112.497.41 €</td>
<td>4</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>2</td>
<td>6.249.86 €</td>
<td>36</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>146</td>
<td>284.91 €</td>
<td>691</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>315</td>
<td>142.87 €</td>
<td>1.378</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>389</td>
<td>75.77 €</td>
<td>1.856</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>5.445</td>
<td>24.49 €</td>
<td>26.409</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>7.644</td>
<td>17.04 €</td>
<td>36.313</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>14.260</td>
<td>15.27 €</td>
<td>68.154</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>30.823</td>
<td>12.32 €</td>
<td>148.373</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>118.570</td>
<td>8.66 €</td>
<td>571.622</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>222.293</td>
<td>4.75 €</td>
<td>1.066.477</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505152213254619ebcfae45d410ebcfae45d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del viernes 15 de mayo de 2015</title>
				<pubDate>Fri, 15 May 2015 21:24:01 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dviernes%2D15%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 15 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>05 - 35 - 42 - 44 - 47</b> Estrellas: <b>08 - 09</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505152124015488ebcfae45d410ebcfae45d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del martes 12 de mayo de 2015</title>
				<pubDate>Tue, 12 May 2015 22:29:52 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dmartes%2D12%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 12 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>14 - 29 - 30 - 40 - 46</b> Estrellas: <b>03 - 06</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>1</td>
<td>47.879.234.00 €</td>
<td>1</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>0</td>
<td>180.585.59 €</td>
<td>6</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>0</td>
<td>120.390.39 €</td>
<td>3</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>6</td>
<td>5.016.27 €</td>
<td>36</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>130</td>
<td>287.30 €</td>
<td>550</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>248</td>
<td>137.28 €</td>
<td>1.151</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>436</td>
<td>57.09 €</td>
<td>1.977</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>5.994</td>
<td>19.02 €</td>
<td>27.292</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>6.670</td>
<td>17.11 €</td>
<td>29.024</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>12.597</td>
<td>14.68 €</td>
<td>56.912</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>33.552</td>
<td>9.56 €</td>
<td>153.411</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>100.051</td>
<td>8.70 €</td>
<td>456.725</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>190.151</td>
<td>4.61 €</td>
<td>881.884</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505122229523bc031ed1c54d41031ed1c54d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del martes 12 de mayo de 2015</title>
				<pubDate>Tue, 12 May 2015 21:27:11 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dmartes%2D12%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 12 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>14 - 29 - 30 - 40 - 46</b> Estrellas: <b>03 - 06</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150512212711b52031ed1c54d41031ed1c54d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del viernes 08 de mayo de 2015</title>
				<pubDate>Fri, 08 May 2015 22:05:53 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dviernes%2D08%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 08 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>07 - 14 - 19 - 47 - 49</b> Estrellas: <b>03 - 10</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>0</td>
<td>495.553.79 €</td>
<td>3</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>2</td>
<td>35.396.70 €</td>
<td>14</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>12</td>
<td>5.506.15 €</td>
<td>45</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>259</td>
<td>218.99 €</td>
<td>990</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>512</td>
<td>100.05 €</td>
<td>2.167</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>461</td>
<td>72.64 €</td>
<td>2.132</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>7.023</td>
<td>22.10 €</td>
<td>32.240</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>10.327</td>
<td>15.07 €</td>
<td>45.206</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>22.826</td>
<td>11.58 €</td>
<td>98.992</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>35.237</td>
<td>12.05 €</td>
<td>167.036</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>145.484</td>
<td>8.15 €</td>
<td>669.201</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>319.102</td>
<td>3.84 €</td>
<td>1.451.459</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505082205530d3c4a1c3d03d4104a1c3d03d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del viernes 08 de mayo de 2015</title>
				<pubDate>Fri, 08 May 2015 21:33:00 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dviernes%2D08%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del viernes 08 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>07 - 14 - 19 - 47 - 49</b> Estrellas: <b>03 - 10</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505082133006a9b4a1c3d03d4104a1c3d03d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: premios y ganadores del martes 05 de mayo de 2015</title>
				<pubDate>Tue, 05 May 2015 22:05:53 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/escrutinios/euromillones%2Dpremios%2Dy%2Dganadores%2Ddel%2Dmartes%2D05%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 05 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>01 - 10 - 17 - 20 - 42</b> Estrellas: <b>08 - 09</b></p>

<p>El reparto de premios queda como sigue:</p>

<div class="detalleSorteo euromillones fondoBuscador buscadorAvanzado">
<div class="cuerpoRegionInf tablaPremios">
<div class="contenidoRegion">
<table cellspacing="0" cellpadding="0" border="0" class="tablaDetalle"><tbody>
<tr>
<th>Categoría</th>
<th>Acertantes</th>
<th>Premios</th>
<th>Acertantes Europa</th>
</tr>

<tr class="par">
<td class="izquierda">1ª 5 + 2</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="impar">
<td class="izquierda">2ª 5 + 1</td>
<td>0</td>
<td>0.00 €</td>
<td>0</td>
</tr>

<tr class="par">
<td class="izquierda">3ª 5 + 0</td>
<td>0</td>
<td>339.051.68 €</td>
<td>4</td>
</tr>

<tr class="impar">
<td class="izquierda">4ª 4 + 2</td>
<td>8</td>
<td>5.297.68 €</td>
<td>32</td>
</tr>

<tr class="par">
<td class="izquierda">5ª 4 + 1</td>
<td>144</td>
<td>182.68 €</td>
<td>812</td>
</tr>

<tr class="impar">
<td class="izquierda">6ª 4 + 0</td>
<td>294</td>
<td>96.95 €</td>
<td>1.530</td>
</tr>

<tr class="par">
<td class="izquierda">7ª 3 + 2</td>
<td>353</td>
<td>68.31 €</td>
<td>1.551</td>
</tr>

<tr class="impar">
<td class="izquierda">8ª 2 + 2</td>
<td>5.087</td>
<td>21.11 €</td>
<td>23.085</td>
</tr>

<tr class="par">
<td class="izquierda">9ª 3 + 1</td>
<td>7.261</td>
<td>13.11 €</td>
<td>35.553</td>
</tr>

<tr class="impar">
<td class="izquierda">10ª 3 + 0</td>
<td>14.381</td>
<td>11.21 €</td>
<td>69.937</td>
</tr>

<tr class="par">
<td class="izquierda">11ª 1 + 2</td>
<td>26.404</td>
<td>11.64 €</td>
<td>118.352</td>
</tr>

<tr class="impar">
<td class="izquierda">12ª 2 + 1</td>
<td>105.270</td>
<td>7.45 €</td>
<td>500.560</td>
</tr>

<tr class="par">
<td class="izquierda">13ª 2 + 0</td>
<td>204.066</td>
<td>3.95 €</td>
<td>966.105</td>
</tr>

</tbody></table>
</div>
</div>
</div>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505052205539f2bc55e6d12d410d55e6d12d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: resultados del martes 05 de mayo de 2015</title>
				<pubDate>Tue, 05 May 2015 21:27:12 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/resultados/euromillones%2Dresultados%2Ddel%2Dmartes%2D05%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>En el sorteo de <strong><a href="/es/euromillones">Euromillones</a></strong> del martes 05 de mayo de 2015, la combinación ganadora ha correspondido a los siguientes números:</p>

<p><b>01 - 10 - 17 - 20 - 42</b> Estrellas: <b>08 - 09</b></p>

<div class="enlace negrita"><a href="/es/euromillones">Toda la información oficial sobre este sorteo de Euromillones</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505052127123e9ac55e6d12d410d55e6d12d410c3ab1cacRCRD</guid>
		</item>
		</channel>
</rss><!-- VIGN HPD cache address: b473e7f8cfa1c91ab249b40f3edd1841; generated: Mon Jun 08 07:25:46 CEST 2015 -->
EOD;

    protected $rss_jackpot = <<<'EOD'
<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
        <image>
            <url>http://www.loteriasyapuestas.es/f/loterias/estaticos/imagenes/RSSEuromillones.jpg</url>
            <title>Botes de Euromillones</title>
            <link><![CDATA[http://www.loteriasyapuestas.es/es/euromillones/botes]]></link>
        </image>
        <title>Botes de Euromillones</title>
        <atom:link href="http://www.loteriasyapuestas.es/es/euromillones/botes" rel="self" type="application/rss+xml" />
        <link><![CDATA[http://www.loteriasyapuestas.es/es/euromillones/botes]]></link>
        <description>Botes de Euromillones</description>
        <language></language>
        <copyright>Loter&amp;iacute;as y Apuestas del Estado</copyright>
        <managingEditor>atencionalcliente@loteriasyapuestas.net (Loter&amp;iacute;as y apuestas del estado)</managingEditor>
        <webMaster>atencionalcliente@loteriasyapuestas.net (Loter&amp;iacute;as y apuestas del estado)</webMaster>
        <category>Avisos</category>
        <generator>mvc-version: 3.3.1</generator>
        <item>
            	<title>Euromillones: bote de 100.000.000€</title>
				<pubDate>Tue, 02 Jun 2015 22:27:41 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D100%2D000%2D000euro%2Dviernes%2D05%2Dde%2Djunio%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 05 de junio de 2015 pone en juego un <b>BOTE de 100.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150602222741e2bb46cbdf1bd41056cbdf1bd410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 21.000.000€</title>
				<pubDate>Fri, 29 May 2015 22:28:38 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D21%2D000%2D000euro%2Dmartes%2D02%2Dde%2Djunio%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 02 de junio de 2015 pone en juego un <b>BOTE de 21.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150529222838795b01ae90d9d41001ae90d9d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 15.000.000€</title>
				<pubDate>Tue, 26 May 2015 22:24:10 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D15%2D000%2D000euro%2Dviernes%2D29%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 29 de mayo de 2015 pone en juego un <b>BOTE de 15.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150526222410556deb944bd8d410eb944bd8d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 37.000.000€</title>
				<pubDate>Fri, 22 May 2015 22:50:46 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D37%2D000%2D000euro%2Dmartes%2D26%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 26 de mayo de 2015 pone en juego un <b>BOTE de 37.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201505222250466f0c8065ff87d4109065ff87d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 31.000.000€</title>
				<pubDate>Tue, 19 May 2015 22:46:07 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D31%2D000%2D000euro%2Dviernes%2D22%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 22 de mayo de 2015 pone en juego un <b>BOTE de 31.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150519224607f64d43d5e996d41043d5e996d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 21.000.000€</title>
				<pubDate>Fri, 15 May 2015 22:35:42 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D21%2D000%2D000euro%2Dmartes%2D19%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 19 de mayo de 2015 pone en juego un <b>BOTE de 21.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150515223542a899ebcfae45d410ebcfae45d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 15.000.000€</title>
				<pubDate>Wed, 13 May 2015 00:30:19 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D15%2D000%2D000euro%2Dviernes%2D15%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 15 de mayo de 2015 pone en juego un <b>BOTE de 15.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150513003019496131ed1c54d41031ed1c54d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 47.000.000€</title>
				<pubDate>Fri, 08 May 2015 22:24:54 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D47%2D000%2D000euro%2Dmartes%2D12%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 12 de mayo de 2015 pone en juego un <b>BOTE de 47.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015050822245493ec4a1c3d03d4104a1c3d03d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 40.000.000€</title>
				<pubDate>Tue, 05 May 2015 22:41:13 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D40%2D000%2D000euro%2Dviernes%2D08%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 08 de mayo de 2015 pone en juego un <b>BOTE de 40.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150505224113fe9bc55e6d12d410d55e6d12d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 30.000.000€</title>
				<pubDate>Fri, 01 May 2015 22:22:40 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D30%2D000%2D000euro%2Dmartes%2D05%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 05 de mayo de 2015 pone en juego un <b>BOTE de 30.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150501222240fcd91d790fc0d4102d790fc0d410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 24.000.000€</title>
				<pubDate>Tue, 28 Apr 2015 23:45:53 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D24%2D000%2D000euro%2Dviernes%2D01%2Dde%2Dmayo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 01 de mayo de 2015 pone en juego un <b>BOTE de 24.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150428234553a01c9d3909dfc410ad3909dfc410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 15.000.000€</title>
				<pubDate>Fri, 24 Apr 2015 22:39:13 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D15%2D000%2D000euro%2Dmartes%2D28%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 28 de abril de 2015 pone en juego un <b>BOTE de 15.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150424223913920b70974c8ec41080974c8ec410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 51.000.000€</title>
				<pubDate>Tue, 21 Apr 2015 22:20:30 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D51%2D000%2D000euro%2Dviernes%2D24%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 24 de abril de 2015 pone en juego un <b>BOTE de 51.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015042122203056db8873579dc4108873579dc410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 39.000.000€</title>
				<pubDate>Fri, 17 Apr 2015 22:31:58 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D39%2D000%2D000euro%2Dmartes%2D21%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 21 de abril de 2015 pone en juego un <b>BOTE de 39.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015041722315886cbfc957b4cc4100d957b4cc410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 31.000.000€</title>
				<pubDate>Tue, 14 Apr 2015 23:43:39 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D31%2D000%2D000euro%2Dviernes%2D17%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 17 de abril de 2015 pone en juego un <b>BOTE de 31.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150414234339bcfb1235465bc4102235465bc410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 21.000.000€</title>
				<pubDate>Fri, 10 Apr 2015 22:44:54 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D21%2D000%2D000euro%2Dmartes%2D14%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 14 de abril de 2015 pone en juego un <b>BOTE de 21.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">201504102244544fca4dc9eb0ac4104dc9eb0ac410c3ab1cacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 15.000.000€</title>
				<pubDate>Tue, 07 Apr 2015 22:29:30 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D15%2D000%2D000euro%2Dviernes%2D10%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 10 de abril de 2015 pone en juego un <b>BOTE de 15.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150407222930294c4b163519c4104b163519c410cdab1bacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 21.000.000€</title>
				<pubDate>Fri, 03 Apr 2015 22:56:41 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D21%2D000%2D000euro%2Dmartes%2D07%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 07 de abril de 2015 pone en juego un <b>BOTE de 21.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">20150403225641cb3b1809e2d7c4101809e2d7c410cdab1bacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 15.000.000€</title>
				<pubDate>Tue, 31 Mar 2015 22:53:21 +0200</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D15%2D000%2D000euro%2Dviernes%2D03%2Dde%2Dabril%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo viernes 03 de abril de 2015 pone en juego un <b>BOTE de 15.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015033122532120bb833025d6c410833025d6c410cdab1bacRCRD</guid>
		</item>
		<item>
            	<title>Euromillones: bote de 73.000.000€</title>
				<pubDate>Fri, 27 Mar 2015 22:34:46 +0100</pubDate>
				<link>http://www.loteriasyapuestas.es/es/euromillones/botes/euromillones%2Dbote%2Dde%2D73%2D000%2D000euro%2Dmartes%2D31%2Dde%2Dmarzo%2Dde%2D2015</link>
			<description><![CDATA[
          			<p>La jornada de Euromillones del próximo martes 31 de marzo de 2015 pone en juego un <b>BOTE de 73.000.000€</b>.</p>

<div class="enlace negrita"><a href="/es/euromillones">Juega ya Euromillones en loteriasyapuestas.es</a></div>
		]]>
			</description>
			<comments></comments>
				<guid isPermaLink="false">2015032722344655aa64636c85c41074636c85c410cdab1bacRCRD</guid>
		</item>
		</channel>
</rss><!-- VIGN HPD cache address: 2e9edff26d736781d8b1d5fdba9d6ddb; generated: Tue Jun 02 22:31:11 CEST 2015 -->
EOD;

    /**
     * @return \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCurlWrapperWithJackpotRssResponse()
    {
        return $this->setCurlWrapper($this->rss_jackpot);
    }

    /**
     * @return \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCurlWrapperWithResultRssResponse()
    {
        return $this->setCurlWrapper($this->rss_results);
    }

    /**
     * @param $rss
     * @return \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setCurlWrapper($rss)
    {
		$response = new CurlResponse($rss);

        /** @var \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject $curlWrapper_stub */
        $curlWrapper_stub =
            $this->getMockBuilder(
                '\Phalcon\Http\Client\Provider\Curl'
            )->getMock();
        $curlWrapper_stub->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));
        return $curlWrapper_stub;
    }
}