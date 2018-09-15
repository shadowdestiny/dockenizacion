{% extends "main.volt" %}
{% block bodyClass %}{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_css %}
    <link Rel="Canonical" href="{{ postData.getCanonical() }}" />
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content">
        <div class="blog--page">
            <div class="banner"></div>
            <div class="wrapper">
                <div class="content">
                    <div class="image-title-blog">
                        <img src="{{ postData.getImage() }}" {% if mobile == 1 %}width="450" {% else %}width="960"{% endif %} />
                    </div>

                    <div class="title-blog-big{% if mobile == 1 %}-mobile{% endif %}">
                        <h1>
                            {{ postData.getTitle() }}
                        </h1>
                    </div>

                    <div class="wrapper txt-left">
                        {{ postData.getContent() }}
                    </div>
                    <a href="/{{ language.translate("link_blogindex") }}" class="link-blog">{{ language.translate("gotoindex_btn") }}</a>
                </div>
            </div>

			<div class="wrapper">
				<table width="100%" align="center">
					<tr>
						<td width="450">
						{% if not((prev is empty)) %}
							<a href="/{{ language.translate('link_blogindex') }}/{{ prev.getUrl() }}"  style="text-decoration:none">
								<strong>{{ language.translate('previous') }} </strong></br>
								 <table>
                                 	<tr>
                                 	{% if mobile != 1 %}
                                 		<td>
                                 			<img src="{{ prev.getImage() }}" width="250" />
                                 		</td>
                                 	{% endif %}
                                 		<td class="title-blog" style="vertical-align: middle;">
                                 			{{ prev.getTitle() }}
                                 		</td>
                                    </tr>
                                  </table>
							</a>
						{% endif %}
						</td>
						{% if mobile == 1 %}
							</tr>
							<tr>
						{% else %}
							<td width="60"></td>
						{% endif %}
						<td width="450">
						{% if not((next is empty)) %}
							<a href="/{{ language.translate('link_blogindex') }}/{{ next.getUrl() }}"  style="text-decoration:none">
                            	<strong>{{ language.translate('next') }} </strong></br>
								<table>
									<tr>
									 {% if mobile != 1 %}
									 	<td>
											<img src="{{ next.getImage() }}" width="250" />
										</td>
									 {% endif %}
										 <td class="title-blog" style="vertical-align: middle;">
										   {{ next.getTitle() }}
										</td>
                               		</tr>
                               	</table>
                            </a>
						{% endif %}
						</td>
					</tr>
				</table>
			</div>
			</br></br>
		</div>
    </main>
{% endblock %}
