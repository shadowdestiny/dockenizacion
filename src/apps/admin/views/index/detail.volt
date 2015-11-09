{% extends "main.volt" %}

{% block bodyClass %}trans-detail{% endblock %}

{% block meta %}<title>Tanslation Detail - Euromillions Admin System</title>{% endblock %}

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
                <h1 class="h1 purple">Translation Detail</h1>
                <form class="form-horizontal">
                    <h1 class="h1 purple">Translations</h1>

                    /* CASE 1 - NEED TO REFINE ENGLISH TRANSLATION */
                    <div class="box-trans">
                        <div class="alert alert-error">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Error, the system is unable to save the content.</strong> &nbsp;&nbsp; You might want to try again.
                        </div>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Content sucessfully saved.</strong> 
                        </div>
                        <div class="row-fluid">
                            <div class="span1 cl">
                                <div class="lang">EN</div>
                                <div class="box-verify not">Unverified</div>
                                <a class="lnk" href="#">Page Link</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>Old Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label for="textarea2">Refine Translation</label>
                                        <textarea id="textarea2" rows="5"></textarea>
                                    </div>
                                    <div class="cl">
                                        <a class="btn btn-inverse left">Cancel</a>
                                        <a class="btn btn-primary right">Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    /* CASE 2 - FIRST TIME INSERTING TRANSLATED CONTENT (Step 1) */
                    <div class="box-trans">
                        <div class="row-fluid">
                            <div class="span1 cl">
                                <div class="lang">EN</div>
                                <div class="box-verify">Verified</div>
                                <a class="lnk" href="#">Page Link</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 cl">
                                <div class="control-group">
                                    <a class="btn btn-danger">Refine Translation</a>
                                    <a class="btn btn-success">Verified Translation</a>
                                </div>
                                <div class="control-group">
                                    <select class="select">
                                        <option>DE - German</option>
                                        <option>ES - Spanish</option>
                                        <option>FR - French</option>
                                        <option>IT - Italian</option>
                                    </select>
                                    <a class="btn btn-primary">Add New Language</a>
                                </div>
                                <div class="control-group">
                                    <a class="btn">Add a Note to the translator</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    /* CASE 3 - FIRST TIME INSERTING TRANSLATED CONTENT (From Case 2) */

                    <div class="box-trans">
                        <div class="row-fluid">
                            <div class="span1 cl">
                                <div class="lang">EN</div>
                                <div class="box-verify">Verified</div>
                                <a class="lnk" href="#">Page Link</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="span1 cl">
                                <div class="lang">ES</div>
                                <a class="lnk" href="#">Preview Translated Content</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label for="textarea2">Insert Translation</label>
                                        <textarea id="textarea2" rows="5"></textarea>
                                    </div>
                                    <div class="cl">
                                        <a class="btn btn-inverse left">Cancel</a>
                                        <a class="btn btn-primary right">Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    /* CASE 4 - ADDING A NOTE (From Case 2) */
                    
                    <div class="box-trans">
                        <div class="row-fluid">
                            <div class="span1 cl">
                                <div class="lang">EN</div>
                                <div class="box-verify">Verified</div>
                                <a class="lnk" href="#">Page Link</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                    <div class="note">
                                        <strong>Note for the translator</strong>
                                        <br><em>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</em>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label for="textarea2">Insert Note</label>
                                        <textarea id="textarea2" rows="5"></textarea>
                                    </div>
                                    <div class="cl">
                                        <a class="btn btn-inverse left">Cancel</a>
                                        <a class="btn btn-primary right">Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    /* CASE 5 - NEED TO UPDATE INFO FOR SPANISH */
                    <div class="box-trans">
                        <div class="row-fluid">
                            <div class="span1 cl">
                                <div class="lang">EN</div>
                                <div class="box-verify">Verified</div>
                                <a class="lnk" href="#">Page Link</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>Old Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>New Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                </div>
                            </div>
                        </div>                          
                        <div class="row-fluid">
                            <div class="span1 cl">
                                <div class="lang">ES</div>
                                <div class="box-verify not">Unverified</div>
                                <a class="lnk" href="#">Page Link</a>
                            </div>
                            <div class="span5 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label>Old Translation</label>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus ac lectus ut gravida. Nulla sit amet felis nec elit bibendum varius sed quis orci. Cras accumsan venenatis dui vel consequat.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 cl">
                                <div class="content">
                                    <div class="control-group">
                                        <label for="textarea2">Insert Translation</label>
                                        <textarea id="textarea2" rows="5"></textarea>
                                    </div>
                                    <div class="cl">
                                        <a class="btn btn-inverse left">Cancel</a>
                                        <a class="btn btn-primary right">Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>   

            </div>
        </div>
    </div>
</div>
{% endblock %}