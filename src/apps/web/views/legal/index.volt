{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
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
                        <strong><span class="txt">{{ language.translate("Participation Flow") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.3.1</span>
                            <span class="txt">{{ language.translate("Participation is done by purchasing a Ticket, that will be filled in according to the official rules of the given lottery game.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.3.2</span>
                            <span class="txt">{{ language.translate("From time to time, mostly but not limited to, prior to the official lottery draws as per their rules, the Euromillions.com website might block the Tickets for a short period of time. Along with and during the time of such blocking, the Euromillions.com website stops providing its services for the currently upcoming draw or offers the possibility to send the Tickets to play for the very next upcoming draw.") }}</span>
                        </p>

{#                      <p>
                            <span class="n">7.3.3</span>
                            <span class="txt">{{ language.app('When a participant sends the Ticket(s) into play, the transaction gets a unique Transaction ID, with a "Not Paid" Status and gets forwarded for payment. From this moment on, the transaction is available for review under the My Transactions menu.')}}</span>
                        </p> #}

                       <p>
                            <span class="n">7.3.3</span>
                            <span class="txt">{{ language.translate('In case of an unsuccessful payment, the Transaction status under the My Transactions menu will be classed as "Payment Failed", the Tickets included in that transaction will not be processed further on, and will not participate in any draws. These Tickets will not be shown on the Tickets page, as they never got paid.') }}</span>
                        </p>

                        <p>
                            <span class="n">7.3.4</span>
                            <span class="txt">{{ language.translate("In case of a successful payment:") }}</span>
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
                                <span class="txt">{{ language.translate("The payment for the Tickets is performed either by debiting the participants’ player account balance or by charging the participants credit card by the Euromillions.com website's payment processor. Payments and purchases are final and cannot be cancelled or refunded as they will be forwarded along in the placement flow.") }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.2</span>
                                <span class="txt">{{ language.translate('Only Tickets with the Transaction status of "Paid" will be processed further on.') }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.3</span>
                                <span class="txt">{{ language.translate("A Ticket that for any reason, including but not limited to technical malfunctioning, technical failure, human error, risk assessment filtering, potential fraud filtering, external forces or anything else, does not get successfully placed, will be automatically reimbursed to the Participant's player account, and will not entitle the participant who filled out the Ticket any rights, including but not limited to appeal against the cause or its consequences.") }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.4</span>
                                <span class="txt">{{ language.translate('The Euromillions.com website and its system is entitled to retroactively cancel and recall a Ticket, if it is found by Euromillions.com that the placement for the Ticket has not been fully settled for any reason, including but not limited to technical malfunctioning, technical failure, human error, risk assessment filtering, potential fraud filtering, external forces, or any other reason. In such an event, the Ticket will be considered as having been invalid from the start and will be considered recalled.') }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.5</span>
                                <span class="txt">{{ language.translate('Immediately after the winning numbers, the number of winners, and the winning sums in each of the official lottery draws will be published by the official operator of the lottery draw (the "Official Results"), the Euromillions.com website will conduct a comprehensive examination of all Valid Tickets. The Euromillions.com website will examine whether, amongst the Valid Tickets, there are selections that entitle a participant or any participants to prizes.')}}</span>
                            </p>
                        </div>
                    </div>

                    <p>
                        <span class="n">7.4</span>
                        <strong><span class="txt">{{ language.translate("Group Games") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.4.1</span>
                            <span class="txt">{{ language.translate("The participation rules of Group Games, which are available from time to time on Euromillions.com, are the same as described for individual games in these Terms and Conditions.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.4.2</span>
                            <span class="txt">{{ language.translate("The individual rules of the certain Group Game are listed on the Game's webpage on Euromillions.com, as is.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.4.3</span>
                            <span class="txt">{{ language.translate("Euromillions.com reserves the right to make partial, immediate or future, complete or partial changes or suspensions to Group Games at its sole discretion without the obligation of any prior notice to the Euromillions.com website's registrants.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.5</span>
                        <strong><span class="txt">{{ language.translate("Results, Winnings and Payouts") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.5.1</span>
                            <span class="txt">{{ language.translate("Results of a game will be published on the Euromillions.com website. In parallel and immediately with the publication of the Results on the Euromillions.com website, an optional notification e-mail will be sent to the electronic address that was given by the Participant.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.2</span>
                            <span class="txt">{{ language.translate("In the event that a validated bet includes a winning selection or selections that entitle a prize up to the amount the official lottery operator does not require personal identification, the Euromillions.com website will transfer the winning sum to the Participant's player account shortly after the publication of the results on the Euromillions.com website. The Participant can withdraw the winning sum at any time as long as the amount of winnings or the accumulated amount of winnings is equal or higher than €25. An optional notification e-mail is sent to the Participant, containing the details of the deposited amount.") }}</span>
                        </p>

                        <p class="gap">
                            <span class="n">7.5.2.1</span>
                            <span class="txt">{{ language.translate("Winnings will be credited to the player account in Euro, and will bear no interest or linkage differentials from the winning date until the actual crediting of the winning sums or thereafter. Non-Euro prizes will be converted to Euro according to the rules detailed in 9.4.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.3</span>
                            <span class="txt">{{ language.translate("When a validated bet includes a winning selection or selections that entitle a prize above the amount for which the official lottery operator requires personal identification of the winner, e.g.: the Jackpot, or the second prize, Euromillions.com will have the right to choose the manner of the prize collection through:") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">7.5.3.1</span>
                                <span class="txt">{{ language.translate("either instructing the winner to take part of the identification proceeding, and collect the amount from the official lottery operator directly;") }}</span>
                            </p>

                            <p>
                                <span class="n">7.5.3.2</span>
                                <span class="txt">{{ language.translate("or Euromillions.com shall collect the prize on the winner’s behalf at the winner’s costs, and forward to the winner the amount of the prize left after deduction of costs.") }}</span>
                            </p>
                        </div>

                        <p>
                            <span class="n">7.5.4</span>
                            <span class="txt">{{ language.translate("In cases where the winner collects the winnings in person, the Euromillions.com website’s representative will hand over the winning ticket to the customer.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.5</span>
                            <span class="txt">{{ language.translate("In cases where the Euromillions.com website collects the winning sum on the winner’s behalf, Euromillions.com will transfer the winning sum primarily to the Participant's player account; and in case of first or second prize category, the amounts left after costs deduction, to his/her Bank account via a Bank Transfer. The Euromillions.com website will transfer the winning sum according to the Participant's instructions, deducting any transfer costs, within 30 days from the receipt of the winning sum from the official draw operator.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.6</span>
                            <span class="txt">{{ language.translate("The participant alone will be responsible for the payment of the taxes that will apply, if at all, on the winning sums. However, the Euromillions.com website will be entitled, but not obliged, to deduct from the winning sums any tax that will apply to the winning sums concerning a withholding tax obligation.")}}</span>
                        </p>

                        <p>
                            <span class="n">7.5.7</span>
                            <span class="txt">{{ language.translate("The Participant is solely responsible for complying with the provisions of any and all law concerning him/her according to his/her citizenship.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.6</span>
                        <strong><span class="txt">{{ language.translate("Refunds, Returns and Cancellations")}}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.6.1</span>
                            <span class="txt">{{ language.translate("The payment for the purchase of a ticket is performed either by debiting the participant’s player balance, or by charging the participants credit card via the Euromillions.com website's payment processor. After paying for the ticket(s), the Euromillions.com website forwards the payment to our ticket provider with whom all transactions are final, therefore payments and purchases are final and cannot be cancelled or refunded.</br></br> The EU Consumer Rights legislation requiring a 14 day cooling off period for online purchases does not apply to services for specific dates or event tickets. Therefore, the services supplied by the company do not fall  under the remit of this legislation.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.6.2</span>
                            <span class="txt">{{ language.translate("As per 11.3, the Euromillions.com website reserves the right to cancel, terminate, modify or suspend any of the tickets if for any reason, the ticket purchase cannot be conducted as planned for any reason, including infection by computer virus, bugs, tampering or unauthorized intervention, fraud, technical failures or any other causes beyond the control of the Euromillions.com website.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.7</span>
                        <strong><span class="txt">{{ language.translate("Withdrawal") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.7.1</span>
                            <span class="txt">{{ language.translate("As a fraud prevention measure, only winnings from purchased tickets can be withdrawn. The withdrawable balance is visible in your player account. Withdrawals can only be made if the player account and payee account have the same name.") }}</span> 
                        </p>

                        <p>
                            <span class="n">7.7.2</span>
                            <span class="txt">{{ language.translate("Every withdrawal is manually verified in compliance with anti-money laundering policies.  Bank transaction fees apply on payouts; these can vary depending on the transferred amount and destination country of the bank.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.3</span>
                            <span class="txt">{{ language.translate("The minimum withdrawal amount is €25. For withdrawals over €250, the user may be requested to present his/her ID with a photograph, name and address. The Company may also request a copy of his/her ID that is certified by two (2) witnesses, the names, addresses and ID numbers of the certifier witnesses must be clearly indicated. The copy must be certified as a “true copy of the original seen and the photograph being of a reasonable likeness to the bearer of the ID document”. In addition to the mentioned ID, a certified copy and data of either of the following documents may also be requested:") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate("Document which indicates the full name of the user together with his residential address; or") }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate("A scanned of a utility bill which indicates the full name of the user together with his residential address on it.") }}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate("All data on the scanned documents must match the user’s data in our system.") }}</span>
                            </p>
                        </div>
                        <p>
                            <span class="n">7.7.4</span>
                            <span class="txt">{{ language.translate("History and data of the withdrawal transactions are accessible by logged in users under the My Transactions.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.5</span>
                            <span class="txt">{{ language.translate("In case of withdrawal, all legal titles of the transactions shall be indicated on the financial statement sent by Euromillions.com to the participant’s registered e-mail address.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.6</span>
                            <span class="txt">{{ language.translate("Euromillions.com keeps record of all transactions been made on the participant’s player account, wherein Euromillions.com defines the legal title of the transactions. Following type of legal titles are used:") }}</span> 
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate("cash deposit: lodged money to the player account;") }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate("payment: purchasing lottery ticket;") }}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate("winning payout: prize payment; and") }}</span>
                            </p>

                            <p>
                                <span class="n">d.</span>
                                <span class="txt">{{ language.translate("withdrawal: withdrawing money from the player account.") }}</span>
                            </p>
                        </div>

                        <p>
                            <span class="n">7.7.7</span>
                            <span class="txt">{{ language.translate("The participant shall be solely and fully liable for the reporting and payment of any and all taxes and charges arising from or imposed on the amounts deposited, paid, transferred or withdrawn by his/her player account.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.8</span>
                            <span class="txt">{{ language.translate("Participant is aware of and acknowledges that the official lottery operators might retain a portion of the prize and forward it to the competent taxing authority on his/her behalf.") }}</span>
                        </p>
                    </div>
                </div>

                <p>
                    <span class="n">8.</span>
                    <strong><span class="txt">{{ language.translate("Prices") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">8.1</span>
                        <span class="txt">{{ language.translate("The price of participation in a Game may be more than the participation price that is collected by the official operator for a single draw.") }}</span>
                    </p>

                    <p>
                        <span class="n">8.2</span>
                        <span class="txt">{{ language.translate("Participation price of each Game via the Euromillions.com website may change from one draw to another.") }}</span>
                    </p>

                    <p>
                        <span class="n">8.3</span>
                        <span class="txt">{{ language.translate("In every event, the participation price via the Euromillions.com website is the price appearing on the Ticket and in the Basket. The final debiting sum for the participation is the sum appearing in the Payment confirmation form and in the Participant's Account.") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">9.</span>
                    <strong><span class="txt">{{ language.translate("Currency") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">9.1</span>
                        <span class="txt">{{ language.translate("The operating currency of the Euromillions.com website is EURO. Debit transactions of the participation price and payment of the prize moneys to the winners will be made in EURO.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.2</span>
                        <span class="txt">{{ language.translate("In any event in which an official lottery draw is conducted in a currency different than EURO, then for the publication on the Euromillions.com website of the prize sums in that draw, an approximate conversion of the prizes will be made from the currency in which they are supposed to be paid in to EURO.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.3</span>
                        <span class="txt">{{ language.translate("All the exchanges from various currencies to EURO will be performed using current conversion rates that will be periodically published by banking institutions.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.4</span>
                        <span class="txt">{{ language.translate("Winnings in currencies other than EURO will be converted to EURO based on the latest periodically published exchange rate, and then credited to the player’s account.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.5</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website will be entitled to deduct reasonable fees arising from currency exchange costs.") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">10.</span>
                    <strong><span class="txt">{{ language.translate("Information") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">10.1</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website provides various official information about the worldwide leading lottery draws, including winning numbers, official rules, and official results.") }}</span>
                    </p>

                    <p>
                        <span class="n">10.2</span>
                        <span class="txt">{{ language.translate("The publication of the Information on the Euromillions.com website is given only as a service to the participants, and nothing more. The information feed into the Euromillions.com website's operating system may include errors and mistakes made in bona fide.") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">11.</span>
                    <strong><span class="txt">{{ language.translate("Reservations Concerning Responsibilities") }}</span></strong>
                </p>
                
                <div class="gap">
                    <p>
                        <span class="n">11.1</span>
                        <span class="txt">{{ language.translate("Euromillions.com is not responsible for the damages arisen from the breach of contract by any of the official lottery game organizers or operators.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.2</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website does and will, to the best of its ability, prevent any malfunctioning in the Euromillions.com website's operations and activity. However, in any event of a technical failure in the Euromillions.com website's operating or computer system, human error or external force, that as a result thereof, for example, an offline ticket, has not been purchased, or confusion regarding the transfer of the selection that was marked on a validated bet, the Euromillions.com website will be entitled to cancel any validated bet, concerning which the malfunctioning has occurred. In such an event, the responsibility of the Euromillions.com website and/or its operator and/or owner and/or anyone else who has acted on their behalf or for them will be limited only to the participation fees sum that was paid to the Euromillions.com website by the participant for the validated bet.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.3</span>
                        <span class="txt">{{ language.translate("In addition to the above, the Euromillions.com website and/or anyone else who has acted on their behalf are not responsible for any error, omission, interruption, deletion, default, defect, delay in operation or transmission, communications line failure, theft, destruction, unauthorized access to, or alteration of data or information. The Euromillions.com website is not responsible for any problems or technical malfunction of any telephone network or lines, computers, on-line systems, servers or providers, computer equipment, software failure of email on account of technical problems or traffic congestion on the Internet or at the Euromillions.com website. The Euromillions.com website reserves the right to cancel, terminate, modify or suspend the Games if for any reason, the Games cannot be conducted as planned, including infection by computer virus, bugs, tampering or unauthorized intervention, fraud, technical failures or any other causes beyond the control of the Euromillions.com website.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.4</span>
                        <span class="txt">{{ language.translate("In no event shall the Euromillions.com website and/or the Euromillions.com website and/or anyone else who has acted on their behalf (including the accountants, auditors, etc.) be liable for any direct, indirect, incidental, special or consequential damages or damages for loss of profits, revenue, data or use incurred by any participant or third party, whether in an action for contract or tort, arising from the access to, or use of, the Euromillions.com website.") }}</span>

                    <p>
                        <span class="n">11.5</span>
                        <span class="txt">{{ language.translate("The responsibility of the Euromillions.com website for the payment of the prize due to participation via the Euromillions.com website in any lottery draw will only arise and be limited only to any sum that the Euromillions.com website will receive from the operator of the official draw.") }}</span> 
                    </p>

                    <p>
                        <span class="n">11.6</span>
                        <span class="txt">{{ language.translate("In any event, the Euromillions.com website and/or anyone on their behalf will not have to pay the participant any sum due to a win of a first prize if the Euromillions.com website will fail, in spite of its reasonable efforts, to collect this prize from the operator of the official draw, for any reason whatsoever.")}}</span>
                    </p>

                    <p>
                        <span class="n">11.7</span>
                        <span class="txt">{{ language.translate('Euromillions.com and/or its agents make no representations about the suitability, reliability, availability, timeliness and accuracy of the information, software, products and services contained on the Euromillions.com website for any purpose. All information, software, products and services are provided "as is" without warranty of any kind. Euromillions.com hereby disclaims all warranties with respect to this information, whether expressed or implied.') }}</span>
                    </p>

                    <p>
                        <span class="n">11.8</span>
                        <span class="txt">{{ language.translate("In any event, no liability will apply to the Euromillions.com website and/or anyone operating in their name or on their behalf due to a damage that was caused due to reliance, of any type, on the Information, as defined above, or on any other publication appearing on the Euromillions.com website, and the participants or any one surfing on the Euromillions.com website are invited to verify the information published on the Euromillions.com website via the terminals and the official publications of the official lottery games operators.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.9</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website reserves the right to immediately ban further access of any participants and their payment vehicles with a certain number without prior notice who has broken these Terms and Conditions and especially the rules set in the referred laws and regulations in the Participation requirements presumably or whose presence and/or participation cause legal risk or action against the Euromillions.com website. In case of such banning, the Euromillions.com website will pay out any positive balances within a thirty (30) day period to the participant.") }}</span>
                    </p>
                </div>

                <h2 class="h3">12. {{ language.translate("Intellectual Property") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">12.1</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website is not related, connected or affiliated to any of the official game organizers. All logos and trademarks used on the Euromillions.com website are the properties of such game organizers and the Euromillions.com website only uses them for informational purposes only.") }}</span>
                    </p>

                    <p>
                        <span class="n">12.2</span>
                        <span class="txt">{{ language.translate("All the rights, including the intellectual property rights (i.e., patents, copyright, trademarks, service marks, logos, trade names, know-how or any other intellectual property or right) concerning this Euromillions.com website or relating to it are owned by the Euromillions.com website and shall be the exclusive property of the Euromillions.com website. No one shall use any of the Rights without the expressed written approval of Euromillions.com.") }}</span>
                    </p>

                    <p>
                        <span class="n">12.3</span>
                        <span class="txt">{{ language.translate("No one should copy or duplicate, in any form whatsoever and in any media whatsoever, any part of this Terms and Conditions, or of the rules the Euromillions.com website, and the Games proposed therein.") }}</span>
                    </p>

                    <p>
                        <span class="n">12.4</span>
                        <span class="txt">{{ language.translate("Each violation of the Euromillions.com website's rights will be severely handled, and Euromillions.com will be strict in extracting its rights against anyone who will violate them.") }}</span>
                    </p>
                </div>

                <p>
                    <h2 class="h3">13. {{ language.translate("Applicable Law") }}</h2>
                </p> 

                <div class="gap">
                    <p>
                        <span class="n">13.1</span>
                        <span class="txt">{{ language.translate("In any event of a clarification, complaint, dispute, reservation, claim or action resulting from participation in any activity on the Euromillions.com website, which is not resolved between the Euromillions.com website and the registrant, the dispute will be transferred to the exclusive decision of the legal jurisdiction where the Euromillions.com company is registered.")}}</span>
                    </p>

                    <p>
                        <span class="n">13.2</span>
                        <span class="txt">{{ language.translate("The legal relationship between the registrant and Euromillions.com shall be governed by law of the legal jurisdiction where the Euromillions.com company is registered, with exclusion of the reference norm of the international civil rights. All disputes in connection with or arising out of any game shall only be settled by the competent court having local and subject matter jurisdiction in the place where the Euromillions.com company is registered.") }}</span>
                    </p>
                </div>

            </div>
        </div>
    </div>
</main>
{% endblock %}
