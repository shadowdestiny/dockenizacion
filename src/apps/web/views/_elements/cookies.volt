{% block template_scripts %}
<script>
$(function(){
    if($.cookie('EM_law')) {
        $('.box-cookies').hide();
    }
}
)
</script>
{%  endblock %}
<div class="box-cookies">
    <a href="/legal/cookies/" class="lnk">We use cookies</a>. If that’s okay with you, just keep browsing.
</div>

