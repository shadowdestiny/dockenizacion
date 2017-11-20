{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/legal.css">
    <link Rel=”Canonical” href=”{{ language.translate('canonical_legal_index') }}” />
{% endblock %}
{% block bodyClass %}terms{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass":"terms"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("terms_head") }}</h1>

            <p>{{ language.translate("terms_head_text") }}</p>

            <h2 class="no-margin h3">{{ language.translate("terms_subhead") }}</h2>
            <h2 class="h3">{{ language.translate("terms_subhead2") }}</h2>

            <p>{{ language.translate("terms_subhead_text") }}</p>

            <p>{{ language.translate("terms_subhead_text2") }}</p>

            <h2 class="h3">{{ language.translate("terms_definitions") }}</h2>

            <p>{{ language.translate("terms_definitions1") }}</p>

            <p>{{ language.translate("terms_definitions2") }}</p>

            <p>{{ language.translate("terms_definitions3") }}</p>

            <p>{{ language.translate("terms_definitions4") }}</p>

            <p>{{ language.translate("terms_definitions5") }}</p>

            <p>{{ language.translate("terms_definitions6") }}</p>

            <p>{{ language.translate("terms_definitions7") }}</p>

            <p>{{ language.translate("terms_definitions8") }}</p>
            
            <p>{{ language.translate("terms_definitions9") }}</p>

            <p>{{ language.translate("terms_definitions10") }}</p>

 {#           <p>{{ language.app("<strong>Ticket ID</strong> means a unique identification number given to a Ticket that is sent into play. It is available for review under the My Bets menu.") }}</p> #}

{#             <p>{{ language.app("<strong>Transaction ID</strong> means a unique identifier of a payment. It is available for review under the My Transactions menu.") }}</p> #}

{#             <p>{{ language.app('<strong>Valid Ticket</strong> means a paid Ticket that has received a valid Ticket ID and has the status of "Paid, Valid".')}}</p> #}

            <p>{{ language.translate("terms_definitions11") }}</p>

            <div class="numbered">
                <h2 class="h3">1. {{ language.translate("terms_introduction") }}</h2>
                <div class="gap">
                    <p>
                        <span class="n">1.1</span> <span class="txt">{{ language.translate("terms_introduction11")}}</span>
                    </p>
                    <p>
                        <span class="n">1.2</span> <span class="txt"><strong>{{ language.translate("terms_introduction12") }}</strong></span>
                    </p>
                    <div class="gap">
                        <p>
                            <span class="n">1.2.1</span> <span class="txt">{{ language.translate("terms_introduction121") }}</span>
                        </p>
                        <p>
                            <span class="n">1.2.2</span> <span class="txt">{{ language.translate("terms_introduction122") }}</span>
                        </p>
                        <p>
                            <span class="n">1.2.3</span> <span class="txt">{{ language.translate("terms_introduction123") }}</span>
                        </p>
                        <p>
                            <span class="n">1.2.4</span> <span class="txt">{{ language.translate("terms_introduction124") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">1.3</span> 
                        <span class="txt">{{ language.translate("terms_introduction13") }}</span>
                    </p>

                    <p><span class="n">1.4</span> <strong><span class="txt">{{ language.translate("terms_introduction14") }}</span></strong></p>

                    <div class="gap">
                        <p>
                            <span class="n">1.4.1</span>
                            <span class="txt">{{ language.translate("terms_introduction141") }}</span>
                        </p>

                        <p>
                            <span class="n">1.4.2</span>
                            <span class="txt">{{ language.translate("terms_introduction142") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">1.5</span>
                        <span class="txt">{{ language.translate("terms_introduction15") }}</span>
                    </p>

                    <p>
                        <span class="n">1.6</span>
                        <span class="txt">{{ language.translate("terms_introduction16") }}</span>
                    </p>

                    <p>
                        <span class="n">1.7</span>
                        <span class="txt">{{ language.translate("terms_introduction17") }}</span>
                    </p>

                    <p>
                        <span class="n">1.8</span>
                        <span class="txt">{{ language.translate("terms_introduction18") }}</span>
                    </p>

                    <p>
                        <span class="n">1.9</span>
                        <span class="txt">{{ language.translate("terms_introduction19") }}</span>
                    </p>
                </div>


                <h2 class="h3">2. {{ language.translate("terms_participation2") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">2.1</span>
                        <span class="txt">{{ language.translate("terms_participation21") }}</span>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">2.1.1</span>
                            <span class="txt">{{ language.translate("terms_participation211") }}</span>
                        </p>

                        <p>
                            <span class="n">2.1.2</span>
                            <span class="txt">{{ language.translate("terms_participation212") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">2.2</span>
                        <span class="txt">{{ language.translate("terms_participation22") }}</span>
                    </p>

                    <p>
                        <span class="n">2.3</span>
                        <span class="txt">{{ language.translate("terms_participation23") }}</span>
                    </p>

                    <p>
                        <span class="n">2.4</span>
                        <span class="txt">{{ language.translate("terms_participation24") }}</span>
                    </p>

                    <p>
                        <span class="n">2.5</span>
                        <span class="txt">{{ language.translate("terms_participation25") }}</span>
                    </p>

                    <p>
                        <span class="n">2.6</span>
                        <span class="txt">{{ language.translate("terms_participation26") }}</span>
                    </p>

                    <p>
                        <span class="n">2.7</span>
                        <span class="txt">{{ language.translate("terms_participation27") }}</span>
                    </p>

                    <p>
                        <span class="n">2.8</span>
                        <span class="txt">{{ language.translate("terms_participation28") }}</span>
                    </p>
                </div>

                <h2 class="h3">3. {{ language.translate("terms_declarations3") }}</h2>

                <p class="gap">
                    <span class="n">3.1</span>
                    <span class="txt">{{ language.translate("terms_declarations31") }}</span>
                </p>

                <h2 class="h3">4. {{ language.translate("terms_registration4") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">4.1</span>
                        <span class="txt">{{ language.translate("terms_registration41") }}</span>
                    </p>

{#                    <div class="gap">
                        <p>
                            <span class="n">4.1.1</span>
                            <span class="txt">{{ language.app("The first step is to complete the registration form with valid data. Participants must ensure that their data are true, and must inform Euromillions.com immediately of any change in the data supplied by them during registration, including changes in their credit card or bank account data, by modifying their own accounts on this website.") }}</span>
                        </p>

                        <p>
                            <span class="n">4.1.2</span>
                            <span class="txt">{{ language.app("The second step is confirmation, which is performed by clicking a confirmation link sent to the registrant via e-mail to the e-mail address provided in the first step.") }}</span>
                        </p>
                    </div> #}

                    <p>
                        <span class="n">4.2</span>
                        <span class="txt">{{ language.translate("terms_registration42") }}</span>
                    </p>

                    <p>
                        <span class="n">4.3</span>
                        <span class="txt">{{ language.translate("terms_registration43") }}</span>
                    </p>

                    <p>
                        <span class="n">4.4</span>
                        <span class="txt">{{ language.translate("terms_registration44") }}</span>
                    </p>

                    <h2 class="h3">5. {{ language.translate("terms_playerAccount5") }}</h2>

                    <p>
                        <span class="n">5.1</span>
                        <span class="txt">{{ language.translate("terms_playerAccount51") }}</span>
                    </p>

                    <p>
                        <span class="n">5.2</span>
                        <span class="txt">{{ language.translate("terms_playerAccount52") }}</span>
                    </p>

                    <p>
                        <span class="n">5.3</span>
                        <span class="txt">{{ language.translate("terms_playerAccount53") }}</span>
                    </p>

                    <p>
                        <span class="n">5.4</span>
                        <span class="txt">{{ language.translate("terms_playerAccount54") }}</span>
                    </p>

                    <p>
                        <span class="n">5.5</span>
                        <span class="txt">{{ language.translate("terms_playerAccount55") }}</span>
                    </p>
                </div>

                <h2 class="h3">6. {{ language.translate("terms_confidentiality6") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">6.1</span>
                        <span class="txt">{{ language.translate("terms_confidentiality61") }}</span>
                    </p>

                    <p>
                        <span class="n">6.2</span>
                        <span class="txt">{{ language.translate("terms_confidentiality62") }}</span>
                    </p>

                    <p>
                        <span class="n">6.3</span>
                        <span class="txt">{{ language.translate("terms_confidentiality63") }}</span>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">6.3.1</span>
                            <span class="txt">{{ language.translate("terms_confidentiality631")}}</span>
                        </p>

                        <p>
                            <span class="n">6.3.2</span>
                            <span class="txt">{{ language.translate("terms_confidentiality632") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.3</span>
                            <span class="txt">{{ language.translate("terms_confidentiality633") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.4</span>
                            <span class="txt">{{ language.translate("terms_confidentiality634") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.5</span>
                            <span class="txt">{{ language.translate("terms_confidentiality635") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.6</span>
                            <span class="txt">{{ language.translate("terms_confidentiality636") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.7</span>
                            <span class="txt">{{ language.translate("terms_confidentiality637") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.8</span>
                            <span class="txt">{{ language.translate("terms_confidentiality638") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.9</span>
                            <span class="txt">{{ language.translate("terms_confidentiality639") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.10</span>
                            <span class="txt">{{ language.translate("terms_confidentiality6310") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">6.4</span>
                        <span class="txt">{{ language.translate("terms_confidentiality64") }}</span>
                    </p>

                    <p>
                        <span class="n">6.5</span>
                        <span class="txt">{{ language.translate("terms_confidentiality65") }}</span>
                    </p>
                </div>

                <h2 class="h3">7. {{ language.translate("terms_paidParticipation7") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">7.1</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation71") }}</span></strong>
                    </p>


                    <div class="gap">
                        <p>
                            <span class="n">7.1.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation711") }}</span>
                        </p>

                        <p>
                            <span class="n">7.1.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation712") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.2</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation72") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.2.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation721") }}</span>
                        </p>

                        <p>
                            <span class="n">7.2.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation722") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.3</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation73") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.3.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation731") }}</span>
                        </p>

                        <p>
                            <span class="n">7.3.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation732") }}</span>
                        </p>

{#                      <p>
                            <span class="n">7.3.3</span>
                            <span class="txt">{{ language.app('When a participant sends the Ticket(s) into play, the transaction gets a unique Transaction ID, with a "Not Paid" Status and gets forwarded for payment. From this moment on, the transaction is available for review under the My Transactions menu.')}}</span>
                        </p> #}

                       <p>
                            <span class="n">7.3.3</span>
                            <span class="txt">{{ language.translate('terms_paidParticipation733') }}</span>
                        </p>

                        <p>
                            <span class="n">7.3.4</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation734") }}</span>
                        </p>

{#                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.app('The Status of the transaction gets updated to "Paid" under the My Transactions menu;') }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.app('The Tickets, each played on separate dates, will be available for review under the My Bets menu. Their status will be "Placement Pending" while they are forwarded for placement.')}}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.app('A "Transaction Notification"')}} {{ language.app("confirmation email will be sent out to the participants' email, separately for each game, containing a unique Ticket ID for each Ticket, and all the played numbers and their settings.") }}</span>
                            </p>
                        </div> #}

                        <div class="gap">
{#                             <p>
                                <span class="n">7.3.4.1</span>
                                <span class="txt">{{ language.app("The participant has the responsibility to preserve the Ticket ID, and to present it to the Euromillions.com website upon its request. The Euromillions.com website reserves the right to reject all claims, including but not limited to winnings, if the participant is not able to present at least the valid Ticket ID and draw date. A Ticket ID and draw date are considered valid if the data completely match with the Euromillions.com website's records along with the Identification and Payment data of the user. In case of disputes, the Euromillions.com website's records are considered the official and valid records.") }}</span>
                            </p> #}

                            <p>
                                <span class="n">7.3.4.1</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation7341") }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.2</span>
                                <span class="txt">{{ language.translate('terms_paidParticipation7342') }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.3</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation7343") }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.4</span>
                                <span class="txt">{{ language.translate('terms_paidParticipation7344') }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.5</span>
                                <span class="txt">{{ language.translate('terms_paidParticipation7345')}}</span>
                            </p>
                        </div>
                    </div>

                    <p>
                        <span class="n">7.4</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation74") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.4.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation741") }}</span>
                        </p>

                        <p>
                            <span class="n">7.4.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation742") }}</span>
                        </p>

                        <p>
                            <span class="n">7.4.3</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation743") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.5</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation75") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.5.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation751") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation752") }}</span>
                        </p>

                        <p class="gap">
                            <span class="n">7.5.2.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation7521") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.3</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation753") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">7.5.3.1</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation7531") }}</span>
                            </p>

                            <p>
                                <span class="n">7.5.3.2</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation7532") }}</span>
                            </p>
                        </div>

                        <p>
                            <span class="n">7.5.4</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation754") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.5</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation755") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.6</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation756")}}</span>
                        </p>

                        <p>
                            <span class="n">7.5.7</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation757") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.6</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation76")}}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.6.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation761") }}</span>
                        </p>

                        <p>
                            <span class="n">7.6.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation762") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.7</span>
                        <strong><span class="txt">{{ language.translate("terms_paidParticipation77") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.7.1</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation771") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.2</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation772") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.3</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation773") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation773a") }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation773b") }}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation773c") }}</span>
                            </p>
                        </div>
                        <p>
                            <span class="n">7.7.4</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation774") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.5</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation775") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.6</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation776") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation776a") }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation776b") }}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation776c") }}</span>
                            </p>

                            <p>
                                <span class="n">d.</span>
                                <span class="txt">{{ language.translate("terms_paidParticipation776d") }}</span>
                            </p>
                        </div>

                        <p>
                            <span class="n">7.7.7</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation777") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.8</span>
                            <span class="txt">{{ language.translate("terms_paidParticipation778") }}</span>
                        </p>
                    </div>
                </div>

                <p>
                    <span class="n">8.</span>
                    <strong><span class="txt">{{ language.translate("terms_prices8") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">8.1</span>
                        <span class="txt">{{ language.translate("terms_prices81") }}</span>
                    </p>

                    <p>
                        <span class="n">8.2</span>
                        <span class="txt">{{ language.translate("terms_prices82") }}</span>
                    </p>

                    <p>
                        <span class="n">8.3</span>
                        <span class="txt">{{ language.translate("terms_prices83") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">9.</span>
                    <strong><span class="txt">{{ language.translate("terms_currency9") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">9.1</span>
                        <span class="txt">{{ language.translate("terms_currency91") }}</span>
                    </p>

                    <p>
                        <span class="n">9.2</span>
                        <span class="txt">{{ language.translate("terms_currency92") }}</span>
                    </p>

                    <p>
                        <span class="n">9.3</span>
                        <span class="txt">{{ language.translate("terms_currency93") }}</span>
                    </p>

                    <p>
                        <span class="n">9.4</span>
                        <span class="txt">{{ language.translate("terms_currency94") }}</span>
                    </p>

                    <p>
                        <span class="n">9.5</span>
                        <span class="txt">{{ language.translate("terms_currency95") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">10.</span>
                    <strong><span class="txt">{{ language.translate("terms_information101") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">10.1</span>
                        <span class="txt">{{ language.translate("terms_information101") }}</span>
                    </p>

                    <p>
                        <span class="n">10.2</span>
                        <span class="txt">{{ language.translate("terms_information102") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">11.</span>
                    <strong><span class="txt">{{ language.translate("terms_reservations11") }}</span></strong>
                </p>
                
                <div class="gap">
                    <p>
                        <span class="n">11.1</span>
                        <span class="txt">{{ language.translate("terms_reservations111") }}</span>
                    </p>

                    <p>
                        <span class="n">11.2</span>
                        <span class="txt">{{ language.translate("terms_reservations112") }}</span>
                    </p>

                    <p>
                        <span class="n">11.3</span>
                        <span class="txt">{{ language.translate("terms_reservations113") }}</span>
                    </p>

                    <p>
                        <span class="n">11.4</span>
                        <span class="txt">{{ language.translate("terms_reservations114") }}</span>

                    <p>
                        <span class="n">11.5</span>
                        <span class="txt">{{ language.translate("terms_reservations115") }}</span>
                    </p>

                    <p>
                        <span class="n">11.6</span>
                        <span class="txt">{{ language.translate("terms_reservations116")}}</span>
                    </p>

                    <p>
                        <span class="n">11.7</span>
                        <span class="txt">{{ language.translate('terms_reservations117') }}</span>
                    </p>

                    <p>
                        <span class="n">11.8</span>
                        <span class="txt">{{ language.translate("terms_reservations118") }}</span>
                    </p>

                    <p>
                        <span class="n">11.9</span>
                        <span class="txt">{{ language.translate("terms_reservations119") }}</span>
                    </p>
                </div>

                <h2 class="h3">12. {{ language.translate("terms_intellectual12") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">12.1</span>
                        <span class="txt">{{ language.translate("terms_intellectual121") }}</span>
                    </p>

                    <p>
                        <span class="n">12.2</span>
                        <span class="txt">{{ language.translate("terms_intellectual122") }}</span>
                    </p>

                    <p>
                        <span class="n">12.3</span>
                        <span class="txt">{{ language.translate("terms_intellectual123") }}</span>
                    </p>

                    <p>
                        <span class="n">12.4</span>
                        <span class="txt">{{ language.translate("terms_intellectual124") }}</span>
                    </p>
                </div>

                <p>
                    <h2 class="h3">13. {{ language.translate("terms_law13") }}</h2>
                </p> 

                <div class="gap">
                    <p>
                        <span class="n">13.1</span>
                        <span class="txt">{{ language.translate("terms_law131")}}</span>
                    </p>

                    <p>
                        <span class="n">13.2</span>
                        <span class="txt">{{ language.translate("terms_law132") }}</span>
                    </p>
                </div>

            </div>
        </div>
    </div>
</main>
{% endblock %}
