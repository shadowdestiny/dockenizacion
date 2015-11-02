{% extends "main.volt" %}

{% block bodyClass %}translation{% endblock %}

{% block meta %}<title>Tanslation Overview - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
 <div class="wrapper">
    <div class="container">
        <div class="module">
            <div class="module-body">
                <h1 class="h1 purple">Translation Overview</h1>
                <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th class="trans">Translation</th>
                        <th class="lang">Languages</th>
                        <th class="approved">Approved</th>
                        <th class="waiting">Pending<br>Verification</th>
                        <th class="review">Refine Content</th>
                        <th class="modif">Pending Update</th>
                        <th class="missing">Missing Content</th>
                        <th class="page">Page Name</th>
                    </tr>
                    
                    <tr class="id-1">
                        <td class="trans">
                            <a href="detail">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravi&hellip;</a>
                        </td>
                        <td class="lang">
                            <a href="detail"><span class="en active">EN</span></a>
                            <a href="detail"><span class="es active">ES</span></a>
                            <a href="detail"><span class="de check">DE</span></a>
                        </td>
                        <td class="approved"><i class="ico icon-check"></i></td>
                        <td class="verify"><i class="ico icon-flag"></i></td>
                        <td class="review">&nbsp;</td>
                        <td class="modif">&nbsp;</td>
                        <td class="missing">&nbsp;</td>
                        <td class="page"><a href="#" target="_blank">Home</a></td>
                    </tr>
                    <tr class="id-2">
                        <td class="trans">
                            <a href="detail">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravi&hellip;</a>
                        </td>
                        <td class="lang">
                            <a href="detail"><span class="en active">EN</span></a>
                             <a href="detail"><span class="es active">ES</span></a>
                             <a href="detail"><span class="de active">DE</span></a>
                        </td>
                        <td class="approved"><i class="ico icon-check"></i></td>
                        <td class="verify">&nbsp;</td>
                        <td class="review">&nbsp;</td>
                        <td class="modif">&nbsp;</td>
                        <td class="missing">&nbsp;</td>
                        <td class="page"><a href="#" target="_blank">Home</a></td>
                    </tr>
                    <tr class="id-3">
                        <td class="trans">
                            <a href="detail">Consectetur adipiscing elit. Sed finibus ac lectus Lorem ipsum dolor sit&hellip;</a>
                        </td>
                        <td class="lang">
                            <a href="detail"><span class="en active">EN</span></a>
                            <a href="detail"><span class="es active">ES</span></a>
                            <a href="detail"><span class="de change">DE</span></a>
                        </td>
                        <td class="approved">&nbsp;</td>
                        <td class="verify">&nbsp;</td>
                        <td class="review">&nbsp;</td>
                        <td class="modif"><i class="ico icon-warning-sign"></i></td>
                        <td class="missing">&nbsp;</td>
                        <td class="page"><a href="#" target="_blank">Success Cart</a></td>
                    </tr>
                    <tr class="id-4">
                        <td class="trans">
                            <a href="detail">Lectus adipiscing sed finibus ac lectus Lorem ipsum dolor sit&hellip;</a>
                        </td>
                        <td class="lang">
                            <a href="detail"><span class="en active">EN</span></a>
                            <a href="detail"><span class="es change">ES</span></a>
                            <a href="detail"><span class="de miss">DE</span></a>
                        </td>
                        <td class="approved">&nbsp;</td>
                        <td class="verify">&nbsp;</td>
                        <td class="review">&nbsp;</td>
                        <td class="modif"><i class="ico icon-warning-sign"></i></td>
                        <td class="missing"><i class="ico icon-comment"></td>
                        <td class="page"><a href="#" target="_blank">Play</a></td>
                    </tr>
                    <tr class="id-5">
                        <td class="trans">
                            <a href="detail">Ipsum alqua, sed dura lectus adipiscing sed finibus ac lectus torem ipsum dolor&hellip;</a>
                        </td>
                        <td class="lang">
                            <a href="detail"><span class="en change">EN</span></a>
                            <a href="detail"><span class="es miss">ES</span></a>
                            <a href="detail"><span class="de miss">DE</span></a>
                        </td>
                        <td class="approved">&nbsp;</td>
                        <td class="verify">&nbsp;</td>
                        <td class="review"><i class="ico icon-warning-sign"></i></td>
                        <td class="modif">&nbsp;</td>
                        <td class="missing"><i class="ico icon-comment"></td>
                        <td class="page"><a href="#" target="_blank">Numbers</a></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
{% endblock %}